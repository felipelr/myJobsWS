<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Subcategories Controller
 *
 * @property \App\Model\Table\SubcategoriesTable $Subcategories
 *
 * @method \App\Model\Entity\Subcategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubcategoriesController extends AppController
{
    public function view($idCategoria = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute("SELECT subcategories.id, subcategories.category_id, 
            subcategories.description, subcategories.icon, subcategories.active, COUNT(services.id) AS countServices 
            FROM subcategories 
            INNER JOIN services ON (subcategories.id = services.subcategory_id) 
            WHERE subcategories.category_id = $idCategoria 
            GROUP BY subcategories.id, subcategories.category_id, subcategories.description, subcategories.icon, subcategories.active")
            ->fetchAll('assoc');

        $this->set([
            'subcategories' => $results,
            '_serialize' => ['subcategories']
        ]);
    }
}
