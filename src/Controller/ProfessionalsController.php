<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class ProfessionalsController extends AppController
{
     
    public function newSuggest()
    {
        $errorMessage = '';
        if ($this->request->is('post')) {
            $name = $this->request->getData('name');
            $contact = $this->request->getData('contact');
            $phone = $this->request->getData('phone');

            $ProfessionalsSuggestions = TableRegistry::getTableLocator()->get('ProfessionalsSuggestions');
            $entity = $ProfessionalsSuggestions->newEntity();
            $entity->name = $name;
            $entity->contact = $contact;
            $entity->phone = $phone;

            if (!$ProfessionalsSuggestions->save($entity)) {
                $errorMessage = 'Não foi possível registrar a sugestão de professional\empresa.';
            }

            if ($errorMessage == '') {
                $this->set([
                    'newSuggestion' => $entity,
                    '_serialize' => ['newSuggestion']
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
}
