<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * ProfessionalComments Controller
 *
 * @property \App\Model\Table\ProfessionalCommentsTable $ProfessionalComments
 *
 * @method \App\Model\Entity\ProfessionalComment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalCommentsController extends AppController
{
    public function view($professional_id = null, $service_id = null)
    {
        $professionalComments = [];
        $query = $this->ProfessionalComments->find('all')
            ->where([
                'ProfessionalComments.professional_id = ' => $professional_id,
                'ProfessionalComments.service_id = ' => $service_id
            ])
            ->contain(['Clients']);

        foreach ($query as $row) {
            $professionalComments[] = $row;
        }

        $this->set([
            'professionalComments' => $professionalComments,
            '_serialize' => ['professionalComments']
        ]);
    }

    public function add()
    { }

    public function edit($id = null)
    { }

    public function delete($id = null)
    { }
}
