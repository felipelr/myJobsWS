<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserAddress Controller
 *
 * @property \App\Model\Table\UserAddressTable $UserAddress
 *
 * @method \App\Model\Entity\UserAddres[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserAddressController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Cities']
        ];
        $userAddress = $this->paginate($this->UserAddress);

        $this->set(compact('userAddress'));
    }

    /**
     * View method
     *
     * @param string|null $id User Addres id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userAddres = $this->UserAddress->get($id, [
            'contain' => ['Users', 'Cities']
        ]);

        $this->set('userAddres', $userAddres);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userAddres = $this->UserAddress->newEntity();
        if ($this->request->is('post')) {
            $userAddres = $this->UserAddress->patchEntity($userAddres, $this->request->getData());
            if ($this->UserAddress->save($userAddres)) {
                $this->Flash->success(__('The user addres has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user addres could not be saved. Please, try again.'));
        }
        $users = $this->UserAddress->Users->find('list', ['limit' => 200]);
        $cities = $this->UserAddress->Cities->find('list', ['limit' => 200]);
        $this->set(compact('userAddres', 'users', 'cities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User Addres id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userAddres = $this->UserAddress->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userAddres = $this->UserAddress->patchEntity($userAddres, $this->request->getData());
            if ($this->UserAddress->save($userAddres)) {
                $this->Flash->success(__('The user addres has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user addres could not be saved. Please, try again.'));
        }
        $users = $this->UserAddress->Users->find('list', ['limit' => 200]);
        $cities = $this->UserAddress->Cities->find('list', ['limit' => 200]);
        $this->set(compact('userAddres', 'users', 'cities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User Addres id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userAddres = $this->UserAddress->get($id);
        if ($this->UserAddress->delete($userAddres)) {
            $this->Flash->success(__('The user addres has been deleted.'));
        } else {
            $this->Flash->error(__('The user addres could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
