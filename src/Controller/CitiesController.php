<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Cities Controller
 *
 * @property \App\Model\Table\CitiesTable $Cities
 *
 * @method \App\Model\Entity\City[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CitiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($state_id)
    {
        $citiesList = [];
        $query = $this->Cities->find('all')
            ->where(['Cities.state_id = ' => $state_id])
            ->contain(['States']);

        foreach ($query as $row) {
            $citiesList[] = $row;
        }

        $this->set([
            'cities' => $citiesList,
            '_serialize' => ['cities']
        ]);
    }
}
