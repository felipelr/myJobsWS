<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ServicesController extends AppController
{ 
    public function index()
    {
        $this->paginate = [
            'contain' => ['Subcategories']
        ];
        $services = $this->paginate($this->Services);

        $this->set(compact('services'));
    }

    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Subcategories']
        ]);

        $this->set('service', $service);
    }

    public function getBySubcategory($idSubcategory = null)
    { 
        $connection = ConnectionManager::get('default');
        $services = $connection->execute(
        "   SELECT  s.*, COUNT(p.professional_id) AS Profissionais  FROM services AS s
            LEFT JOIN professional_services AS p ON(s.id = p.service_id)
            WHERE subcategory_id = $idSubcategory
            GROUP BY s.id
            ORDER BY title ")
        ->fetchAll('assoc');

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);

    }
   
}
