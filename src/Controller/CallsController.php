<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Calls Controller
 *
 * @property \App\Model\Table\CallsTable $Calls
 *
 * @method \App\Model\Entity\Call[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CallsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Professionals', 'Services']
        ];
        $calls = $this->paginate($this->Calls);

        $this->set(compact('calls'));
    }

    /**
     * View method
     *
     * @param string|null $id Call id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $call = $this->Calls->get($id, [
            'contain' => ['Users', 'Professionals', 'Services']
        ]);

        $this->set('call', $call);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $call = $this->Calls->newEntity();
        if ($this->request->is('post')) {
            $call = $this->Calls->patchEntity($call, $this->request->getData());
            if ($this->Calls->save($call)) {
                $this->Flash->success(__('The call has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The call could not be saved. Please, try again.'));
        }
        $users = $this->Calls->Users->find('list', ['limit' => 200]);
        $professionals = $this->Calls->Professionals->find('list', ['limit' => 200]);
        $services = $this->Calls->Services->find('list', ['limit' => 200]);
        $this->set(compact('call', 'users', 'professionals', 'services'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Call id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $call = $this->Calls->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $call = $this->Calls->patchEntity($call, $this->request->getData());
            if ($this->Calls->save($call)) {
                $this->Flash->success(__('The call has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The call could not be saved. Please, try again.'));
        }
        $users = $this->Calls->Users->find('list', ['limit' => 200]);
        $professionals = $this->Calls->Professionals->find('list', ['limit' => 200]);
        $services = $this->Calls->Services->find('list', ['limit' => 200]);
        $this->set(compact('call', 'users', 'professionals', 'services'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Call id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $call = $this->Calls->get($id);
        if ($this->Calls->delete($call)) {
            $this->Flash->success(__('The call has been deleted.'));
        } else {
            $this->Flash->error(__('The call could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
