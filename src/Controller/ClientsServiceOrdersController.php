<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

/**
 * ClientsServiceOrders Controller
 *
 * @property \App\Model\Table\ClientsServiceOrdersTable $ClientsServiceOrders
 *
 * @method \App\Model\Entity\ClientsServiceOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsServiceOrdersController extends AppController
{
    public function add()
    {
        $errorMessage = '';
        $clientsServiceOrders = $this->ClientsServiceOrders->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $clientsServiceOrders = $this->ClientsServiceOrders->patchEntity($clientsServiceOrders, $this->request->getData());

                if ($this->ClientsServiceOrders->save($clientsServiceOrders)) {
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar a ordem de orçamento.';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'clientsServiceOrders' => $clientsServiceOrders,
                '_serialize' => ['clientsServiceOrders']
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
