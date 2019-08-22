<?php
namespace App\Controller;

use App\Controller\AppController;

class ServicesController extends AppController
{ 
    public function index($idSubcategory = null)
    {
        $services = $this->Service->find('all')
        ->where(['Services.active = ' => true])
        ->andWhere(['Services.subcategory_id = ' => $idSubcategory]);

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);
    }

   
}
