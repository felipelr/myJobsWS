<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Subcategories Controller
 *
 * @property \App\Model\Table\SubcategoriesTable $Subcategories
 *
 * @method \App\Model\Entity\Subcategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubcategoriesController extends AppController
{
    public function category($id = null)
    {
        $subcategories = $this->Subcategories->find('all')
            ->where(['Subcategories.category_id = ' => $id]);

        $this->set([
            'subcategories' => $subcategories,
            '_serialize' => ['subcategories']
        ]);
    }

    public function getByCategory($category_id = null)
    {
        $professional_id = $this->request->getQuery('professional_id', 0);
        if ($professional_id == 0) {
            $connection = ConnectionManager::get('default');
            $results = $connection->execute(
                "SELECT subcategories.id, subcategories.category_id, 
                    subcategories.description, subcategories.icon, subcategories.active, COUNT(DISTINCT(services.id)) AS countServices, 
                    COUNT(DISTINCT(professional_services.professional_id)) as countProfessionals, COUNT(DISTINCT(calls.id)) as Atendimentos
                FROM subcategories 
                LEFT JOIN services ON (subcategories.id = services.subcategory_id)
                LEFT JOIN professional_services on (professional_services.service_id = services.id AND professional_services.active = 1) 
                LEFT JOIN calls ON(calls.service_id = services.id)
                WHERE subcategories.category_id = $category_id 
                GROUP BY subcategories.id, subcategories.category_id, subcategories.description, subcategories.icon, subcategories.active"
            )
                ->fetchAll('assoc');

            $this->set([
                'subcategories' => $results,
                '_serialize' => ['subcategories']
            ]);
        } else {
            $connection = ConnectionManager::get('default');
            $results = $connection->execute(
                "SELECT subcategories.id, subcategories.category_id, 
                    subcategories.description, subcategories.icon, subcategories.active, COUNT(DISTINCT(services.id)) AS countServices, 
                    COUNT(DISTINCT(professional_services.professional_id)) as countProfessionals, COUNT(DISTINCT(calls.id)) as Atendimentos
                FROM subcategories 
                LEFT JOIN services ON (subcategories.id = services.subcategory_id)
                LEFT JOIN professional_services on (professional_services.service_id = services.id AND professional_services.active = 1) 
                LEFT JOIN calls ON(calls.service_id = services.id)
                WHERE subcategories.category_id = $category_id AND professional_services.professional_id = $professional_id
                GROUP BY subcategories.id, subcategories.category_id, subcategories.description, subcategories.icon, subcategories.active"
            )
                ->fetchAll('assoc');

            $this->set([
                'subcategories' => $results,
                '_serialize' => ['subcategories']
            ]);
        }
    }
}
