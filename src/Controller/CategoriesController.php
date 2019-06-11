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
        $users = $this->Users->find('all')
        ->where(['Users.active = ' => true])
        ->limit(10);

        $this->set([
            'users' => $users,
            '_serialize' => ['users']
        ]);
    }

}
