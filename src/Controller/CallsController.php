<?php

namespace App\Controller;

use App\Controller\AppController;
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
                ->all();
        } else {
            $calls = $this->Calls->find('all')
                ->where([
                    'Calls.professional_id = ' => $professional_id,
                    'Calls.status = ' => $type,
                ])
                ->contain(['Clients', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories'])
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
}
