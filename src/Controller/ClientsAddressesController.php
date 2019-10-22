<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ClientsAddresses Controller
 *
 * @property \App\Model\Table\ClientsAddressesTable $ClientsAddresses
 *
 * @method \App\Model\Entity\ClientsAddress[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsAddressesController extends AppController
{
    public function view($client_id = null)
    {
        $clientsAddresses = [];
        $query = $this->ClientsAddresses->find('all')
            ->where(['ClientsAddresses.client_id = ' => $client_id])
            ->contain(['Cities', 'Cities.States']);

        foreach ($query as $row) {
            //debug($row->created);
            $clientsAddresses[] = $row;
        }

        $this->set([
            'clientsAddresses' => $clientsAddresses,
            '_serialize' => ['clientsAddresses']
        ]);
    }

    public function add()
    {
        $errorMessage = '';
        $client = null;
        if ($this->request->is('post')) {
            $clientsAddress = $this->ClientsAddresses->newEntity();
            $clientsAddress = $this->ClientsAddresses->patchEntity($clientsAddress, $this->request->getData());
            if ($this->ClientsAddresses->save($clientsAddress)) {
                $Clients = TableRegistry::getTableLocator()->get('Clients');
                $client = $Clients->find('all')
                    ->where(['Clients.id = ' => $clientsAddress['client_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($client['id'])) {
                    $client['clientsAddresses'] = $this->ClientsAddresses->find('all')
                        ->where(['ClientsAddresses.client_id = ' => $client['id']])
                        ->contain(['Cities', 'Cities.States'])
                        ->all();
                }
            } else {
                $errorMessage = 'Não foi possível inserir o endereço.' . json_encode($clientsAddress->getErrors());
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($errorMessage == '') {
            $this->set([
                'client' => $client,
                '_serialize' => ['client']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function edit($id = null)
    {
        $errorMessage = '';
        $client = null;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $clientsAddress = $this->ClientsAddresses->newEntity();
            $clientsAddress = $this->ClientsAddresses->patchEntity($clientsAddress, $this->request->getData());
            $clientsAddress->id = $id;
            unset($clientsAddress['city']);

            if (!isset($clientsAddress['latitude'])) {
                $clientsAddress['latitude'] = 0;
                $clientsAddress['longitude'] = 0;
            }

            if ($this->ClientsAddresses->save($clientsAddress)) {
                $Clients = TableRegistry::getTableLocator()->get('Clients');
                $client = $Clients->find('all')
                    ->where(['Clients.id = ' => $clientsAddress['client_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($client['id'])) {
                    $client['clientsAddresses'] = $this->ClientsAddresses->find('all')
                        ->where(['ClientsAddresses.client_id = ' => $client['id']])
                        ->contain(['Cities', 'Cities.States'])
                        ->all();
                }
            } else {
                $errorMessage = 'Não foi possível alterar o endereço.' . json_encode($clientsAddress->getErrors());
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($errorMessage == '') {
            $this->set([
                'client' => $client,
                '_serialize' => ['client']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function delete($id = null)
    {
        $errorMessage = '';
        $client = null;
        $this->request->allowMethod(['post', 'delete']);
        $clientsAddress = $this->ClientsAddresses->get($id);
        if ($this->ClientsAddresses->delete($clientsAddress)) {
            $Clients = TableRegistry::getTableLocator()->get('Clients');
            $client = $Clients->find('all')
                ->where(['Clients.id = ' => $clientsAddress['client_id']])
                ->contain(['Users'])
                ->first();
            if (isset($client['id'])) {
                $client['clientsAddresses'] = $this->ClientsAddresses->find('all')
                    ->where(['ClientsAddresses.client_id = ' => $client['id']])
                    ->contain(['Cities', 'Cities.States'])
                    ->all();
            }
        } else {
            $errorMessage = 'Não foi possível excluir o endereço.' . json_encode($clientsAddress->getErrors());
        }

        if ($errorMessage == '') {
            $this->set([
                'client' => $client,
                '_serialize' => ['client']
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
