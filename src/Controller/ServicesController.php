<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $services = $this->Services->find('all')
        ->where(['Services.active = ' => 1])
        ->andWhere(['Services.subcategory_id = ' => $idSubcategory]);

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);
    }
   
}
