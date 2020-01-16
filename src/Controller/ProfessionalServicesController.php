<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * ProfessionalServices Controller
 *
 * @property \App\Model\Table\ProfessionalServicesTable $ProfessionalServices
 *
 * @method \App\Model\Entity\ProfessionalService[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalServicesController extends AppController
{
    public function view($professional_id = null)
    {
        $professionalServices = [];
        $query = $this->ProfessionalServices->find('all')
            ->where(['ProfessionalServices.professional_id = ' => $professional_id])
            ->contain(['Services']);

        foreach ($query as $row) {
            $professionalServices[] = $row;
        }

        $this->set([
            'professionalServices' => $professionalServices,
            '_serialize' => ['professionalServices']
        ]);
    }

    public function add()
    { }

    public function edit($id = null)
    { }

    public function delete($id = null)
    { }
}
