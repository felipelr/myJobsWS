<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * ServiceSuggestions Controller
 *
 * @property \App\Model\Table\ServiceSuggestionsTable $ServiceSuggestions
 *
 * @method \App\Model\Entity\ServiceSuggestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServiceSuggestionsController extends AppController
{
    public function professional($id = null)
    {
        $serviceSuggestions = $this->ServiceSuggestions->find('all', [
            'contain' => ['Subcategories'],
            'conditions' => ['professional_id' => $id]
        ])->all();

        $this->set('serviceSuggestions', $serviceSuggestions);
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
