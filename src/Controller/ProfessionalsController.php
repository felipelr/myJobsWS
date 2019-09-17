<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

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
}
