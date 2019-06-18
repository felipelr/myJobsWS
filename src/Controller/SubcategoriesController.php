<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Subcategories Controller
 *
 * @property \App\Model\Table\SubcategoriesTable $Subcategories
 *
 * @method \App\Model\Entity\Subcategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SubcategoriesController extends AppController
{ 
    public function index($idCategoria = null)
    {
        $categories = $this->Categories->find('all')
        ->where(['Subcategories.active = ' => true])
        ->andWhere(['Subcategories.category_id = ' => $idCategoria]);

        $this->set([
            'categories' => $categories,
            '_serialize' => ['categories']
        ]);
    }
    
}
