<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Highlights Controller
 *
 * @property \App\Model\Table\HighlightsTable $Highlights
 *
 * @method \App\Model\Entity\Highlight[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HighlightsController extends AppController
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
        $highlights = $this->paginate($this->Highlights);

        $this->set(compact('highlights'));
    }

    /**
     * View method
     *
     * @param string|null $id Highlight id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $highlight = $this->Highlights->get($id, [
            'contain' => ['Professionals']
        ]);

        $this->set('highlight', $highlight);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $highlight = $this->Highlights->newEntity();
        if ($this->request->is('post')) {
            $highlight = $this->Highlights->patchEntity($highlight, $this->request->getData());
            if ($this->Highlights->save($highlight)) {
                $this->Flash->success(__('The highlight has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The highlight could not be saved. Please, try again.'));
        }
        $professionals = $this->Highlights->Professionals->find('list', ['limit' => 200]);
        $this->set(compact('highlight', 'professionals'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Highlight id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $highlight = $this->Highlights->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $highlight = $this->Highlights->patchEntity($highlight, $this->request->getData());
            if ($this->Highlights->save($highlight)) {
                $this->Flash->success(__('The highlight has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The highlight could not be saved. Please, try again.'));
        }
        $professionals = $this->Highlights->Professionals->find('list', ['limit' => 200]);
        $this->set(compact('highlight', 'professionals'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Highlight id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $highlight = $this->Highlights->get($id);
        if ($this->Highlights->delete($highlight)) {
            $this->Flash->success(__('The highlight has been deleted.'));
        } else {
            $this->Flash->error(__('The highlight could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
