<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class ProfessionalsController extends AppController
{
    public function highlights()
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem FROM highlights as h
         INNER JOIN professionals as p ON(h.professional_id = p.id)
         INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
         WHERE p.active = 1
         GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }

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
