<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProfessionalsAddresses Controller
 *
 * @property \App\Model\Table\ProfessionalsAddressesTable $ProfessionalsAddresses
 *
 * @method \App\Model\Entity\ProfessionalsAddress[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalsAddressesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Professionals', 'Cities']
        ];
        $professionalsAddresses = $this->paginate($this->ProfessionalsAddresses);

        $this->set(compact('professionalsAddresses'));
    }

    /**
     * View method
     *
     * @param string|null $id Professionals Address id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $professionalsAddress = $this->ProfessionalsAddresses->get($id, [
            'contain' => ['Professionals', 'Cities']
        ]);

        $this->set('professionalsAddress', $professionalsAddress);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $professionalsAddress = $this->ProfessionalsAddresses->newEntity();
        if ($this->request->is('post')) {
            $professionalsAddress = $this->ProfessionalsAddresses->patchEntity($professionalsAddress, $this->request->getData());
            if ($this->ProfessionalsAddresses->save($professionalsAddress)) {
                $this->Flash->success(__('The professionals address has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professionals address could not be saved. Please, try again.'));
        }
        $professionals = $this->ProfessionalsAddresses->Professionals->find('list', ['limit' => 200]);
        $cities = $this->ProfessionalsAddresses->Cities->find('list', ['limit' => 200]);
        $this->set(compact('professionalsAddress', 'professionals', 'cities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Professionals Address id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $professionalsAddress = $this->ProfessionalsAddresses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $professionalsAddress = $this->ProfessionalsAddresses->patchEntity($professionalsAddress, $this->request->getData());
            if ($this->ProfessionalsAddresses->save($professionalsAddress)) {
                $this->Flash->success(__('The professionals address has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professionals address could not be saved. Please, try again.'));
        }
        $professionals = $this->ProfessionalsAddresses->Professionals->find('list', ['limit' => 200]);
        $cities = $this->ProfessionalsAddresses->Cities->find('list', ['limit' => 200]);
        $this->set(compact('professionalsAddress', 'professionals', 'cities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Professionals Address id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $professionalsAddress = $this->ProfessionalsAddresses->get($id);
        if ($this->ProfessionalsAddresses->delete($professionalsAddress)) {
            $this->Flash->success(__('The professionals address has been deleted.'));
        } else {
            $this->Flash->error(__('The professionals address could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
