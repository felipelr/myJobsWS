<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ServiceSuggestions Controller
 *
 * @property \App\Model\Table\ServiceSuggestionsTable $ServiceSuggestions
 *
 * @method \App\Model\Entity\ServiceSuggestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceSuggestionsController extends AppController
{
    public function index()
    {
        $search = $this->request->getQuery('search', '');
        if ($search != '') {
            $this->paginate = [
                'contain' => ['Professionals', 'Subcategories', 'Subcategories.Categories'],
                'conditions' => [
                    'OR' => [
                        'ServiceSuggestions.title LIKE' => "%$search%",
                        'Professionals.name LIKE' => "%$search%",
                        'Subcategories.description LIKE' => "%$search%",
                        'Subcategories.Categories.description LIKE' => "%$search%",
                    ]
                ]
            ];
        } else {
            $this->paginate = [
                'contain' => ['Professionals', 'Subcategories', 'Subcategories.Categories']
            ];
        }

        $serviceSuggestions = $this->paginate($this->ServiceSuggestions);

        $this->set(compact('serviceSuggestions', 'search'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $serviceSuggestion = $this->ServiceSuggestions->get($id);
        if ($this->ServiceSuggestions->delete($serviceSuggestion)) {
            $this->Flash->success(__('The service suggestion has been deleted.'));
        } else {
            $this->Flash->error(__('The service suggestion could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function accept($id = null)
    {
        $this->request->allowMethod(['post']);
        $serviceSuggestion = $this->ServiceSuggestions->get($id);

        $Services = TableRegistry::getTableLocator()->get('Services');
        $service = $Services->newEntity();
        $service = $Services->patchEntity($service, [
            'title' => $serviceSuggestion->title,
            'description' => $serviceSuggestion->description,
            'subcategory_id' => $serviceSuggestion->subcategory_id,
            'active' => true
        ]);

        if ($Services->save($service)) {
            $this->ServiceSuggestions->delete($serviceSuggestion);
            $this->Flash->success(__('The service suggestion has been accepted.'));
        } else {
            $this->Flash->error(__('The service suggestion could not be accepted. Please, try again.'));
        }
        
        return $this->redirect(['action' => 'index']);
    }

    public function professional($id = null)
    {
        $serviceSuggestions = $this->ServiceSuggestions->find('all', [
            'contain' => ['Subcategories'],
            'conditions' => ['professional_id' => $id]
        ])->all();

        $this->set([
            'serviceSuggestions' => $serviceSuggestions,
            '_serialize' => ['serviceSuggestions']
        ]);
    }

    public function new()
    {
        $errorMessage = '';
        $serviceSuggestion = $this->ServiceSuggestions->newEntity();
        if ($this->request->is('post')) {
            $serviceSuggestion = $this->ServiceSuggestions->patchEntity($serviceSuggestion, $this->request->getData());
            if (!$this->ServiceSuggestions->save($serviceSuggestion)) {
                $errorMessage = 'Não foi possível salvar a sugestão. ' . json_encode($serviceSuggestion->errors());
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'serviceSuggestion' => $serviceSuggestion,
                '_serialize' => ['serviceSuggestion']
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
