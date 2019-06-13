<?php
namespace App\Controller;

use App\Controller\AppController;

class CategoriesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler'); 
    }
 
    public function index()
    {
        $categories = $this->Categories->find('all')
        ->where(['Categories.active = ' => true]);

        $this->set([
            'categories' => $categories,
            '_serialize' => ['categories']
        ]);
    }

}
