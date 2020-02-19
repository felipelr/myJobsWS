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
    public function addCall()
    {
        $errorMessage = '';
        $call = $this->Calls->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $calls = $this->Calls->patchEntity($call, $this->request->getData());
 
                if ($this->Calls->save($calls)) {
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar o chamado.' + $calls;
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'calls' => $calls,
                '_serialize' => ['calls']
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
