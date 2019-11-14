<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Stories Controller
 *
 * @property \App\Model\Table\StoriesTable $Stories
 *
 * @method \App\Model\Entity\Story[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoriesController extends AppController
{
    public function view($professional_id = null)
    {
        $stories = [];
        $query = $this->Stories->find('all')
            ->where(['Stories.professional_id = ' => $professional_id]);

        foreach ($query as $row) {
            $stories[] = $row;
        }

        $this->set([
            'stories' => $stories,
            '_serialize' => ['stories']
        ]);
    }

    public function add()
    {
        
    }
    
    public function edit($id = null)
    {
        
    }
    
    public function delete($id = null)
    {
        
    }
}
