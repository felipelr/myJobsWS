<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

/**
 * ProfessionalServices Controller
 *
 * @property \App\Model\Table\ProfessionalServicesTable $ProfessionalServices
 *
 * @method \App\Model\Entity\ProfessionalService[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalServicesController extends AppController
{
    public function config()
    {
        $errorMessage = '';
        if ($this->request->is('post')) {
            $professional_id = $this->request->getData('professional_id');
            $category_id = $this->request->getData('category_id');
            $services = $this->request->getData('services');

            try {
                $this->ProfessionalServices->deleteAll(
                    [
                        'amount_ratings' => 0,
                        'professional_id' => $professional_id
                    ]
                );
                $this->ProfessionalServices->updateAll(
                    [
                        'active' => 0
                    ],
                    [
                        'professional_id' => $professional_id
                    ]
                );

                $professional = $this->ProfessionalServices->Professionals->get($professional_id);
                $professional->categorie_id = $category_id;
                $this->ProfessionalServices->Professionals->save($professional);

                foreach ($services as $item) {
                    if ($item['subcategory']['category_id'] == $category_id) {
                        $row = $this->ProfessionalServices->find('all', [
                            'conditions' => [
                                'ProfessionalServices.professional_id' => $professional_id,
                                'ProfessionalServices.service_id' => $item['id'],
                            ]
                        ])->first();

                        if ($row != null) {
                            $row->active = 1;
                            $this->ProfessionalServices->save($row);
                        } else {
                            $newEntity = [
                                'professional_id' => $professional_id,
                                'service_id' => $item['id'],
                                'rating' => 0,
                                'amount_ratings' => 0,
                                'active' => 1
                            ];
                            $professionalServices = $this->ProfessionalServices->newEntity();
                            $professionalServices = $this->ProfessionalServices->patchEntity($professionalServices, $newEntity);
                            $this->ProfessionalServices->save($professionalServices);
                        }
                    }
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($errorMessage == '') {
            $config = [];
            $query = $this->ProfessionalServices->find('all')
                ->where([
                    'ProfessionalServices.professional_id = ' => $professional_id,
                    'ProfessionalServices.active' => 1
                ])
                ->contain(['Services', 'Services.Subcategories', 'Services.Subcategories.Categories']);

            foreach ($query as $row) {
                $config[] = $row;
            }

            $this->set([
                'config' => $config,
                '_serialize' => ['config']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function services($professional_id = null)
    {
        $type = $this->request->getQuery('type', 0);
        $professionalServices = [];

        if ($type == 0) {
            $query = $this->ProfessionalServices->find('all')
                ->where([
                    'ProfessionalServices.professional_id = ' => $professional_id,
                    'ProfessionalServices.active' => 1
                ])
                ->contain(['Services']);

            foreach ($query as $row) {
                $professionalServices[] = $row;
            }
        } else {
            $query = $this->ProfessionalServices->find('all')
                ->where([
                    'ProfessionalServices.professional_id = ' => $professional_id,
                    'ProfessionalServices.active' => 1
                ])
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
