<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Exception;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

/**
 * Ratings Controller
 *
 * @property \App\Model\Table\RatingsTable $Ratings
 *
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RatingsController extends AppController
{
    public function professional($professional_id)
    {
        $rate = [];
        $query = $this->Ratings->find('all')->where(['Ratings.professional_id = ' => $professional_id]);

        $rate = $query->select([
            'avg' => $query->func()->avg('rate'),
            'count' => $query->func()->count('*'),
        ])->first();

        if (isset($rate['avg'])) {
            $rate['avg'] = floor($rate['avg']);
        }

        $this->set([
            'rate' => $rate,
            '_serialize' => ['rate']
        ]);
    }

    public function rate()
    {
        $errorMessage = '';
        $rating = $this->Ratings->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
                $rateExist = $this->Ratings->find('all')
                    ->where([
                        'Ratings.professional_id = ' => $rating->professional_id,
                        'Ratings.client_id' => $rating->client_id,
                        'Ratings.call_id' => $rating->call_id,
                    ])
                    ->count();

                if ($rateExist == 0) {
                    if ($this->Ratings->save($rating)) {
                        //sucesso      
                        $call = $this->Ratings->Calls->get($rating->call_id);
                        $query = $this->Ratings->find('all')
                            ->contain(['Calls'])
                            ->where([
                                'Ratings.professional_id = ' => $rating->professional_id,
                                'Calls.service_id' => $call->service_id
                            ]);
                        $rateService = $query->select([
                            'avg' => $query->func()->avg('rate'),
                            'count' => $query->func()->count('*'),
                        ])->first();
                        $ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices');
                        $ProfessionalServices->updateAll(
                            [
                                'rating' => $rateService['avg'],
                                'amount_ratings' => $rateService['count'],
                            ],
                            [
                                'professional_id' => $rating->professional_id,
                                'service_id' => $call->service_id,
                            ]
                        );

                        //enviar notificacao
                        $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                        $professional = $Professionals->find('all')
                            ->where(['Professionals.id = ' => $rating->professional_id])
                            ->contain(['Users'])
                            ->first();

                        $Clients = TableRegistry::getTableLocator()->get('Clients');
                        $client = $Clients->find('all')
                            ->where(['Clients.id = ' => $rating->client_id])
                            ->first();

                        if (isset($client) && isset($professional)) {
                            $tokenApp = $professional['user']['fcm_token'] == null ? '' : $professional['user']['fcm_token'];
                            if ($tokenApp != '') {
                                try {
                                    $factory = (new Factory())
                                        ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                                    $messaging = $factory->createMessaging();

                                    $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                                        ->withNotification([
                                            'title' => 'Nova avaliação',
                                            'body' => 'Uma nova avaliação foi feita por ' . $client['name'],
                                            'icon' => 'ic_myjobs'
                                        ])
                                        ->withData([
                                            'message' => json_encode([
                                                'type' => 'rating',
                                                'professional_id' => $rating->professional_id,
                                                'client_id' => $rating->client_id,
                                                'rating_id' => $rating->id
                                            ])
                                        ]);

                                    $messaging->send($messageFCM);
                                } catch (Exception $ex) {
                                }
                            }
                        }

                        $errorMessage = '';
                    } else {
                        //erro
                        $errorMessage = 'Não foi possível salvar a avaliação.' . json_encode($rating->getErrors());
                    }
                } else {
                    $errorMessage = 'A avaliação deste chamado já foi realizada.';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'rating' => $rating,
                '_serialize' => ['rating']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function comments($professional_id = null, $service_id = null)
    {
        $comments = [];
        $query = $this->Ratings->find('all')
            ->where([
                'Ratings.professional_id = ' => $professional_id,
                'Calls.service_id = ' => $service_id
            ])
            ->contain(['Clients', 'Calls']);

        foreach ($query as $row) {
            $comments[] = $row;
        }

        $this->set([
            'comments' => $comments,
            '_serialize' => ['comments']
        ]);
    }
}
