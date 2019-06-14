<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProfessionalPhones Controller
 *
 * @property \App\Model\Table\ProfessionalPhonesTable $ProfessionalPhones
 *
 * @method \App\Model\Entity\ProfessionalPhone[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalPhonesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Professionals']
        ];
        $professionalPhones = $this->paginate($this->ProfessionalPhones);

        $this->set(compact('professionalPhones'));
    }

    /**
     * View method
     *
     * @param string|null $id Professional Phone id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $professionalPhone = $this->ProfessionalPhones->get($id, [
            'contain' => ['Professionals']
        ]);

        $this->set('professionalPhone', $professionalPhone);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $professionalPhone = $this->ProfessionalPhones->newEntity();
        if ($this->request->is('post')) {
            $professionalPhone = $this->ProfessionalPhones->patchEntity($professionalPhone, $this->request->getData());
            if ($this->ProfessionalPhones->save($professionalPhone)) {
                $this->Flash->success(__('The professional phone has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professional phone could not be saved. Please, try again.'));
        }
        $professionals = $this->ProfessionalPhones->Professionals->find('list', ['limit' => 200]);
        $this->set(compact('professionalPhone', 'professionals'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Professional Phone id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $professionalPhone = $this->ProfessionalPhones->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $professionalPhone = $this->ProfessionalPhones->patchEntity($professionalPhone, $this->request->getData());
            if ($this->ProfessionalPhones->save($professionalPhone)) {
                $this->Flash->success(__('The professional phone has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The professional phone could not be saved. Please, try again.'));
        }
        $professionals = $this->ProfessionalPhones->Professionals->find('list', ['limit' => 200]);
        $this->set(compact('professionalPhone', 'professionals'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Professional Phone id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $professionalPhone = $this->ProfessionalPhones->get($id);
        if ($this->ProfessionalPhones->delete($professionalPhone)) {
            $this->Flash->success(__('The professional phone has been deleted.'));
        } else {
            $this->Flash->error(__('The professional phone could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
