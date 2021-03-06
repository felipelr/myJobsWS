<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Exception;

/**
 * ChatMessages Controller
 *
 * @property \App\Model\Table\ChatMessagesTable $ChatMessages
 *
 * @method \App\Model\Entity\ChatMessage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChatMessagesController extends AppController
{
    public function messages()
    {
        $queryParams = $this->request->getQueryParams();
        $client_id = $queryParams['client_id'];
        $professional_id = $queryParams['professional_id'];
        $last_id = $queryParams['last_id'];

        if ($last_id == null) {
            $last_id = 0;
        }

        $chatMessages = [];
        $query = $this->ChatMessages->find('all')
            ->where([
                'ChatMessages.client_id = ' => $client_id,
                'ChatMessages.professional_id = ' => $professional_id,
                'ChatMessages.id > ' => $last_id,
            ])
            ->order(['ChatMessages.date_time' => 'ASC']);

        foreach ($query as $row) {
            $row['date'] = date('d/m/Y', strtotime($row['date_time']));
            $row['time'] = date('H:i:s', strtotime($row['date_time']));
            $chatMessages[] = $row;
        }

        $this->set([
            'chatMessages' => $chatMessages,
            '_serialize' => ['chatMessages']
        ]);
    }

    public function add()
    {
        $errorMessage = '';
        $message = null;
        $tokenApp = '';
        $to = '';

        if ($this->request->is('post')) {
            $newMessage = $this->ChatMessages->newEntity();
            $newMessage = $this->ChatMessages->patchEntity($newMessage, $this->request->getData());
            $tokenApp = $this->request->getData('fcmToken');
            if ($this->ChatMessages->save($newMessage)) {
                $message = $newMessage;
            } else {
                $errorMessage = 'Não foi possível salvar a mensagem.' . json_encode($newMessage->getErrors());
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($message != null) {
            $title = 'Strab';
            if ($message['msg_from'] == 'client') {
                $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                $professional = $Professionals->find('all')
                    ->where(['Professionals.id = ' => $message['professional_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($professional)) {
                    $tokenApp = $professional['user']['fcm_token'] == null ? '' : $professional['user']['fcm_token'];
                    $to = 'professional';
                    $title = $professional['name'];
                }
            } else {
                $Clients = TableRegistry::getTableLocator()->get('Clients');
                $client = $Clients->find('all')
                    ->where(['Clients.id = ' => $message['client_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($client)) {
                    $tokenApp = $client['user']['fcm_token'] == null ? '' : $client['user']['fcm_token'];
                    $to = 'client';
                    $title = $client['name'];
                }
            }

            if ($tokenApp != '') {
                try {
                    $factory = (new Factory())
                        ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                    $messaging = $factory->createMessaging();

                    $message['type'] = 'chat_message';
                    $message['to'] = $to;

                    $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                        ->withNotification([
                            'title' => $title,
                            'body' => $message['message'],
                            'icon' => 'ic_launcher',
                        ])
                        ->withData([
                            'message' => $message
                        ]);

                    $messaging->send($messageFCM);
                } catch (Exception $ex) {
                }
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'message' => $message,
                '_serialize' => ['message']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function professionalChats()
    {
        $professional_id = $this->request->getQuery('professional_id');

        $chatMessages = [];
        $query = $this->ChatMessages->find('all')
            ->where([
                'ChatMessages.professional_id = ' => $professional_id,
            ])
            ->contain(['Clients'])
            ->group(['ChatMessages.client_id'])
            ->order(['MAX(date_time)' => 'DESC']);

        foreach ($query as $row) {
            $row['date'] = date('d/m/Y', strtotime($row['date_time']));
            $row['time'] = date('H:i:s', strtotime($row['date_time']));
            $chatMessages[] = $row;
        }

        $this->set([
            'chatMessages' => $chatMessages,
            '_serialize' => ['chatMessages']
        ]);
    }

    public function clientChats()
    {
        $client_id = $this->request->getQuery('client_id');

        $chatMessages = [];
        $query = $this->ChatMessages->find('all')
            ->where([
                'ChatMessages.client_id = ' => $client_id,
            ])
            ->contain(['Professionals'])
            ->group(['ChatMessages.professional_id'])
            ->order(['MAX(date_time)' => 'DESC']);

        foreach ($query as $row) {
            $row['date'] = date('d/m/Y', strtotime($row['date_time']));
            $row['time'] = date('H:i:s', strtotime($row['date_time']));
            $chatMessages[] = $row;
        }

        $this->set([
            'chatMessages' => $chatMessages,
            '_serialize' => ['chatMessages']
        ]);
    }
}
