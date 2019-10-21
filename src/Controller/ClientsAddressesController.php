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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients', 'Cities']
        ];
        $clientsAddresses = $this->paginate($this->ClientsAddresses);

        $this->set(compact('clientsAddresses'));
    }

    /**
     * View method
     *
     * @param string|null $id Clients Address id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
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

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
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
        }
        else {
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

    /**
     * Edit method
     *
     * @param string|null $id Clients Address id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $clientsAddress = $this->ClientsAddresses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $clientsAddress = $this->ClientsAddresses->patchEntity($clientsAddress, $this->request->getData());
            if ($this->ClientsAddresses->save($clientsAddress)) {
                $this->Flash->success(__('The clients address has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The clients address could not be saved. Please, try again.'));
        }
        $clients = $this->ClientsAddresses->Clients->find('list', ['limit' => 200]);
        $cities = $this->ClientsAddresses->Cities->find('list', ['limit' => 200]);
        $this->set(compact('clientsAddress', 'clients', 'cities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Clients Address id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $clientsAddress = $this->ClientsAddresses->get($id);
        if ($this->ClientsAddresses->delete($clientsAddress)) {
            $this->Flash->success(__('The clients address has been deleted.'));
        } else {
            $this->Flash->error(__('The clients address could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
