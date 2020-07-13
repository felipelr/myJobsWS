<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Exception;

/**
 * Calls Controller
 *
 * @property \App\Model\Table\CallsTable $Calls
 *
 * @method \App\Model\Entity\Call[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CallsController extends AppController
{
    public function view($call_id = null)
    {
        $call = $this->Calls->get($call_id, [
            'contain' => ['Professionals', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories', 'Ratings']
        ]);

        $this->set([
            'call' => $call,
            '_serialize' => ['call']
        ]);
    }

    public function client($client_id = null)
    {
        $type = $this->request->getQuery('type', 0);
        $calls = [];
        if ($type == 0) {
            $calls = $this->Calls->find('all')
                ->where([
                    'Calls.client_id = ' => $client_id,
                    'Calls.status = ' => 1,
                ])
                ->contain(['Professionals', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories', 'Ratings'])
                ->order(['Calls.modified' => 'DESC'])
                ->all();
        } else {
            $calls = $this->Calls->find('all')
                ->where([
                    'Calls.client_id = ' => $client_id,
                    'Calls.status = ' => $type,
                ])
                ->contain(['Professionals', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories', 'Ratings'])
                ->order(['Calls.modified' => 'DESC'])
                ->all();
        }

        $this->set([
            'calls' => $calls,
            '_serialize' => ['calls']
        ]);
    }

    public function professional($professional_id = null)
    {
        $type = $this->request->getQuery('type', 0);
        $calls = [];
        if ($type == 0) {
            $calls = $this->Calls->find('all')
                ->where([
                    'Calls.professional_id = ' => $professional_id,
                    'Calls.status = ' => 1,
                ])
                ->contain(['Clients', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories'])
                ->order(['Calls.modified' => 'DESC'])
                ->all();
        } else {
            $calls = $this->Calls->find('all')
                ->where([
                    'Calls.professional_id = ' => $professional_id,
                    'Calls.status = ' => $type,
                ])
                ->contain(['Clients', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories'])
                ->order(['Calls.modified' => 'DESC'])
                ->all();
        }

        $this->set([
            'calls' => $calls,
            '_serialize' => ['calls']
        ]);
    }

    public function addCall()
    {
        $errorMessage = '';
        $call = $this->Calls->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $call = $this->Calls->patchEntity($call, $this->request->getData());
                $call->status = 1;

                if ($this->Calls->save($call)) {
                    //sucesso      
                    $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                    $professional = $Professionals->find('all')
                        ->where(['Professionals.id = ' => $call->professional_id])
                        ->first();

                    $Clients = TableRegistry::getTableLocator()->get('Clients');
                    $client = $Clients->find('all')
                        ->where(['Clients.id = ' => $call->client_id])
                        ->contain(['Users'])
                        ->first();

                    if (isset($client) && isset($professional)) {
                        $tokenApp = $client['user']['fcm_token'] == null ? '' : $client['user']['fcm_token'];
                        $title = $client['name'];
                        if ($tokenApp != '') {
                            try {
                                $factory = (new Factory())
                                    ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                                $messaging = $factory->createMessaging();

                                $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                                    ->withNotification([
                                        'title' => $title,
                                        'body' => 'Um novo chamado foi aberto por ' . $professional['name'],
                                        'icon' => 'ic_myjobs'
                                    ])
                                    ->withData([
                                        'message' => json_encode([
                                            'type' => 'call',
                                            'professional_id' => $call->professional_id,
                                            'client_id' => $call->client_id,
                                            'call_id' => $call->id
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
                    $errorMessage = 'Não foi possível salvar o chamado.' . json_encode($call->getErrors());
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'call' => $call,
                '_serialize' => ['call']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function finish()
    {
        $errorMessage = '';
        $call = $this->Calls->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $call = $this->Calls->get($this->request->getData('id'));
                $call->status = 2;

                if ($this->Calls->save($call)) {
                    //sucesso     
                    $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                    $professional = $Professionals->find('all')
                        ->where(['Professionals.id = ' => $call->professional_id])
                        ->first();

                    $Clients = TableRegistry::getTableLocator()->get('Clients');
                    $client = $Clients->find('all')
                        ->where(['Clients.id = ' => $call->client_id])
                        ->contain(['Users'])
                        ->first();

                    if (isset($client) && isset($professional)) {
                        $tokenApp = $client['user']['fcm_token'] == null ? '' : $client['user']['fcm_token'];
                        $title = $client['name'];
                        if ($tokenApp != '') {
                            try {
                                $factory = (new Factory())
                                    ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                                $messaging = $factory->createMessaging();

                                $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                                    ->withNotification([
                                        'title' => $title,
                                        'body' => 'Chamado finalizado por ' . $professional['name'],
                                        'icon' => 'ic_myjobs'
                                    ])
                                    ->withData([
                                        'message' => json_encode([
                                            'type' => 'call_finished',
                                            'professional_id' => $call->professional_id,
                                            'client_id' => $call->client_id,
                                            'call_id' => $call->id
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
                    $errorMessage = 'Não foi possível finalizar o chamado.' . json_encode($call->getErrors());
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'call' => $call,
                '_serialize' => ['call']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function teste()
    {
        $client_id = $this->request->query('client_id');
        $professional_id = $this->request->query('professional_id');
        $from = $this->request->query('from');
        $call_id = $this->request->query('call_id');

        $tokenApp = '';

        if ($from == 'client') {
            $Professionals = TableRegistry::getTableLocator()->get('Professionals');
            $professional = $Professionals->find('all')
                ->where(['Professionals.id = ' => $professional_id])
                ->contain(['Users'])
                ->first();
            if (isset($professional)) {
                $tokenApp = $professional['user']['fcm_token'] == null ? '' : $professional['user']['fcm_token'];
                $title = $professional['name'];
            }
        } else {
            $Clients = TableRegistry::getTableLocator()->get('Clients');
            $client = $Clients->find('all')
                ->where(['Clients.id = ' => $client_id])
                ->contain(['Users'])
                ->first();
            if (isset($client)) {
                $tokenApp = $client['user']['fcm_token'] == null ? '' : $client['user']['fcm_token'];
                $title = $client['name'];
            }
        }

        $message = json_encode([
            'type' => 'call',
            'professional_id' => $professional_id,
            'client_id' => $client_id,
            'call_id' => $call_id,
        ]);

        try {
            $factory = (new Factory())
                ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
            $messaging = $factory->createMessaging();

            $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                ->withNotification([
                    'title' => $title,
                    'body' => 'Teste de mensagem',
                    'icon' => 'ic_launcher'
                ])
                ->withData([
                    'message' => $message
                ]);

            $messaging->send($messageFCM);
        } catch (Exception $ex) {
        }

        $this->set([
            'chatMessages' => $message,
            '_serialize' => ['chatMessages']
        ]);
    }
}
