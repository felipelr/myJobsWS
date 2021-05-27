<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ProfessionalsAddresses Controller
 *
 * @property \App\Model\Table\ProfessionalsAddressesTable $ProfessionalsAddresses
 *
 * @method \App\Model\Entity\ProfessionalsAddress[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalsAddressesController extends AppController
{
    public function view($professional_id = null)
    {
        $professionalsAddress = [];
        $query = $this->ProfessionalsAddresses->find('all')
            ->where([
                'ProfessionalsAddresses.professional_id = ' => $professional_id,
                'ProfessionalsAddresses.active' => 1
            ])
            ->contain(['Cities', 'Cities.States']);

        foreach ($query as $row) {
            $professionalsAddress[] = $row;
        }

        $this->set([
            'professionalsAddresses' => $professionalsAddress,
            '_serialize' => ['professionalsAddresses']
        ]);
    }

    public function add()
    {
        $errorMessage = '';
        $professional = null;
        if ($this->request->is('post')) {
            $professionalsAddress = $this->ProfessionalsAddresses->newEntity();
            $professionalsAddress = $this->ProfessionalsAddresses->patchEntity($professionalsAddress, $this->request->getData());
            $professionalsAddress->active = 1;
            if ($this->ProfessionalsAddresses->save($professionalsAddress)) {
                $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                $professional = $Professionals->find('all')
                    ->where(['Professionals.id = ' => $professionalsAddress['professional_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($professional['id'])) {
                    $professional['professionalsAddresses'] = $this->ProfessionalsAddresses->find('all')
                        ->where([
                            'ProfessionalsAddresses.professional_id = ' => $professional->id,
                            'ProfessionalsAddresses.active' => 1
                        ])
                        ->contain(['Cities', 'Cities.States'])
                        ->all();
                }
            } else {
                $errorMessage = 'Não foi possível inserir o endereço.' . json_encode($professionalsAddress->getErrors());
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($errorMessage == '') {
            $this->set([
                'professional' => $professional,
                '_serialize' => ['professional']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function edit($id = null)
    {
        $errorMessage = '';
        $professional = null;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $professionalsAddress = $this->ProfessionalsAddresses->newEntity();
            $professionalsAddress = $this->ProfessionalsAddresses->patchEntity($professionalsAddress, $this->request->getData());
            $professionalsAddress->id = $id;
            $professionalsAddress->active = 1;
            unset($professionalsAddress['city']);

            if (!isset($professionalsAddress['latitude'])) {
                $professionalsAddress['latitude'] = 0;
                $professionalsAddress['longitude'] = 0;
            }

            if ($this->ProfessionalsAddresses->save($professionalsAddress)) {
                $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                $professional = $Professionals->find('all')
                    ->where(['Professionals.id = ' => $professionalsAddress['professional_id']])
                    ->contain(['Users'])
                    ->first();
                if (isset($professional['id'])) {
                    $professional['professionalsAddresses'] = $this->ProfessionalsAddresses->find('all')
                        ->where([
                            'ProfessionalsAddresses.professional_id = ' => $professional->id,
                            'ProfessionalsAddresses.active' => 1
                        ])
                        ->contain(['Cities', 'Cities.States'])
                        ->all();
                }
            } else {
                $errorMessage = 'Não foi possível alterar o endereço.' . json_encode($professionalsAddress->getErrors());
            }
        } else {
            $errorMessage = 'Método não implementado.';
        }

        if ($errorMessage == '') {
            $this->set([
                'professional' => $professional,
                '_serialize' => ['professional']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function delete($id = null)
    {
        $errorMessage = '';
        $professional = null;
        $this->request->allowMethod(['post', 'delete']);
        $professionalsAddress = $this->ProfessionalsAddresses->get($id);
        $professionalsAddress->active = 0;
        if ($this->ProfessionalsAddresses->save($professionalsAddress)) {
            $Professionals = TableRegistry::getTableLocator()->get('Professionals');
            $professional = $Professionals->find('all')
                ->where(['Professionals.id = ' => $professionalsAddress['professional_id']])
                ->contain(['Users'])
                ->first();
            if (isset($professional['id'])) {
                $professional->professionalsAddresses = $this->ProfessionalsAddresses->find('all')
                    ->where([
                        'ProfessionalsAddresses.professional_id = ' => $professional->id,
                        'ProfessionalsAddresses.active' => 1
                    ])
                    ->contain(['Cities', 'Cities.States'])
                    ->all();
            }
        } else {
            $errorMessage = 'Não foi possível excluir o endereço.' . json_encode($professionalsAddress->getErrors());
        }

        if ($errorMessage == '') {
            $this->set([
                'professional' => $professional,
                '_serialize' => ['professional']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }
}
