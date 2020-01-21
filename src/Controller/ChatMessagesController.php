<?php

namespace App\Controller;

use App\Controller\AppController;

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
            $row['date'] = date('d/m/Y', strtotime($row['date']));
            $row['time'] = date('H:i:s', strtotime($row['time']));
            $chatMessages[] = $row;
        }

        $this->set([
            'chatMessages' => $chatMessages,
            '_serialize' => ['chatMessages']
        ]);
    }
}
