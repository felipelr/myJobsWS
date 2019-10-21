<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * States Controller
 *
 * @property \App\Model\Table\StatesTable $States
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $statesList = [];
        $query = $this->States->find('all')
            ->contain(['Countries']);

        foreach ($query as $row) {
            //debug($row->created);
            $statesList[] = $row;
        }

        $this->set([
            'states' => $statesList,
            '_serialize' => ['states']
        ]);
    }
}
