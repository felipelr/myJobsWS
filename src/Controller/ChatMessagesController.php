<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Client;
use Kreait\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

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
        $client_id = $this->request->query('client_id');
        $professional_id = $this->request->query('professional_id');
        $last_id = $this->request->query('last_id');
        if ($last_id == null) {
            $last_id = 0;
        }

        $chatMessages = [];
        $query = $this->ChatMessages->find('all')
            ->where([
                'ChatMessages.client_id = ' => $client_id,
                'ChatMessages.professional_id = ' => $professional_id,
                'ChatMessages.id > ' => $last_id,
            ]);

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
        //$tokenApp = 'cu2p1FNtnYQ:APA91bH069tsZsUOJxdbxod6-p17MZgwJHTVbcNSoA-TS-USknDhp6A9yZNY2-cw-dK35vhVdADPjDQe8FQsEjkjrhHtPM8a1mGV4mDrntH5U7iI07dPdIp8Fm8vqgIKBSNXzXQ9RoEb';
        //$projectId = 'myjobstest-719a9';
        //$apiKey = 'AIzaSyBFwPPqTVHIJYgKwoViNwlkC7QcQDbvII4';

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

        if ($tokenApp != '') {
            $factory = (new Factory())
                ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
            $messaging = $factory->createMessaging();

            $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                ->withNotification([
                    'title' => 'MyJobs',
                    'body' => $message['message'],
                    'icon' => 'ic_launcher'
                ])
                ->withData([
                    'message' => $message
                ]);

            $messaging->send($messageFCM);
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
}