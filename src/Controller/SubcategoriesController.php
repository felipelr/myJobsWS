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
    public function view($idCategoria = null)
    {
        $subcategories = $this->Subcategories->find('all')
        ->where(['Subcategories.active = ' => true])
        ->andWhere(['Subcategories.category_id = ' => $idCategoria]);

        $this->set([
            'subcategories' => $subcategories,
            '_serialize' => ['subcategories']
        ]);
    }
    
}
