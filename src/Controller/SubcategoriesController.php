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
        $subcategories = $this->Subcategories->find('all', array(
            'contain' => array('Services'),
            'conditions' => array(
                'Services.subcategory_id = Subcategories.id',
                'Services.active = 1',
            ),
            'order' => 'Services.description DESC'
        ))
        ->where(['Subcategories.active = ' => true])
        ->andWhere(['Subcategories.category_id = ' => $idCategoria]);

        echo(json_decode($subcategories));

        $this->set([
            'subcategories' => $subcategories,
            '_serialize' => ['subcategories']
        ]);
        
    }
    
}
