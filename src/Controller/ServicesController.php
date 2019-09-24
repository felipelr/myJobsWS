<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ServicesController extends AppController
{ 
    public function index()
    {
        $services = $this->Services->find('all')
        ->where(['Services.active = ' => true]);

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);
    }

    public function getBySubcategory($idSubcategory = null)
    { 
        $connection = ConnectionManager::get('default');
        $services = $connection->execute(
        "   SELECT  * FROM services 
            WHERE subcategory_id = $idSubcategory
            ORDER BY title ")
        ->fetchAll('assoc');

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);

    }
   
}
