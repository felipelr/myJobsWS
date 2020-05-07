<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Exception;

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
}
