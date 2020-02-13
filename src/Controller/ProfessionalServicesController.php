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
    public function services($professional_id = null)
    {
        $type = $this->request->getQuery('type', 0);
        $professionalServices = [];

        if ($type == 0) {
            $query = $this->ProfessionalServices->find('all')
                ->where(['ProfessionalServices.professional_id = ' => $professional_id])
                ->contain(['Services']);

            foreach ($query as $row) {
                $professionalServices[] = $row;
            }
        } else {
            $query = $this->ProfessionalServices->find('all')
                ->where(['ProfessionalServices.professional_id = ' => $professional_id])
                ->contain(['Services', 'Services.Subcategories', 'Services.Subcategories.Categories']);

            foreach ($query as $row) {
                $professionalServices[] = $row;
            }
        }

        $this->set([
            'professionalServices' => $professionalServices,
            '_serialize' => ['professionalServices']
        ]);
    }
}
