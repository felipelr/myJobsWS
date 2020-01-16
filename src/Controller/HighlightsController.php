<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Highlights Controller
 *
 * @property \App\Model\Table\HighlightsTable $Highlights
 *
 * @method \App\Model\Entity\Highlight[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HighlightsController extends AppController
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

    public function highlightsBySubcategory($idSubcategory = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "   SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem 
                FROM highlights as h
                INNER JOIN professionals as p ON(h.professional_id = p.id)
                INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
                WHERE p.active = 1 and h.subcategory_id = $idSubcategory
                GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }

    public function highlightsByService($idService = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "   SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem 
                FROM highlights as h
                INNER JOIN professionals as p ON(h.professional_id = p.id)
                INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
                WHERE p.active = 1 and h.service_id = $idService
                GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }

}
