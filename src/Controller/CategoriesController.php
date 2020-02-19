<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class CategoriesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function all()
    {
        $categories = $this->Categories->find('all')
            ->where(['Categories.active = ' => true]);

        $this->set([
            'categories' => $categories,
            '_serialize' => ['categories']
        ]);
    }

    
    public function getByIdProfessional($idProfessional = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
        "SELECT categories.* FROM categories INNER JOIN professionals on(categories.id = professionals.categorie_id) WHERE professionals.id = $idProfessional"
        )
            ->fetchAll('assoc');

        $this->set([
            'categories' => $results,
            '_serialize' => ['categories']
        ]);
    }

}
