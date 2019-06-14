<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Professionals Controller
 *
 * @property \App\Model\Table\ProfessionalsTable $Professionals
 *
 * @method \App\Model\Entity\Professional[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Cities']
        ];
        $professionals = $this->paginate($this->Professionals);

        $this->set(compact('professionals'));
    }

    /**
     * View method
     *
     * @param string|null $id Professional id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $professional = $this->Professionals->get($id, [
            'contain' => ['Cities', 'ProfessionalPhones', 'Ratings']
        ]);

        $this->set('professional', $professional);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $professional = $this->Professionals->newEntity();
        if ($this->request->is('post')) {
            $professional = $this->Professionals->patchEntity($professional, $this->request->getData());
            if ($this->Professionals->save($professional)) {
                $this->Flash->success(__('The professional has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professional could not be saved. Please, try again.'));
        }
        $cities = $this->Professionals->Cities->find('list', ['limit' => 200]);
        $this->set(compact('professional', 'cities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Professional id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $professional = $this->Professionals->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $professional = $this->Professionals->patchEntity($professional, $this->request->getData());
            if ($this->Professionals->save($professional)) {
                $this->Flash->success(__('The professional has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professional could not be saved. Please, try again.'));
        }
        $cities = $this->Professionals->Cities->find('list', ['limit' => 200]);
        $this->set(compact('professional', 'cities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Professional id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $professional = $this->Professionals->get($id);
        if ($this->Professionals->delete($professional)) {
            $this->Flash->success(__('The professional has been deleted.'));
        } else {
            $this->Flash->error(__('The professional could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
