<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Exception;

class ProfessionalsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
     
    public function newSuggest()
    {
        $errorMessage = '';
        if ($this->request->is('post')) {
            $name = $this->request->getData('name');
            $contact = $this->request->getData('contact');
            $phone = $this->request->getData('phone');

            $ProfessionalsSuggestions = TableRegistry::getTableLocator()->get('ProfessionalsSuggestions');
            $entity = $ProfessionalsSuggestions->newEntity();
            $entity->name = $name;
            $entity->contact = $contact;
            $entity->phone = $phone;

            if (!$ProfessionalsSuggestions->save($entity)) {
                $errorMessage = 'Não foi possível registrar a sugestão de professional\empresa.';
            }

            if ($errorMessage == '') {
                $this->set([
                    'newSuggestion' => $entity,
                    '_serialize' => ['newSuggestion']
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

    public function edit($id = null)
    {
        $professional = $this->Professionals->get($id, [
            'contain' => ['Users']
        ]);

        $errorMessage = '';
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $image = $this->request->getData('image');
                $professional = $this->Professionals->patchEntity($professional, $this->request->getData());
                $professionalUpdate = $this->Professionals->newEntity();
                $professionalUpdate->id = $professional['id'];
                $professionalUpdate->name = $professional['name'];
                $professionalUpdate->document = $professional['document'];
                $professionalUpdate->date_birth = $professional['date_birth'];
                $professionalUpdate->description = $professional['description'];

                if (isset($image) && $image != '') {
                    $base64 = $image;
                    $output_file = WWW_ROOT . 'img' . DS . 'professional-' . $professional['id'] . '.jpeg';
                    $dns_path = "http://myjobs.servicos.ws" . DS . 'img' . DS . 'professional-' . $professional['id'] . '.jpeg';

                    $ifp = fopen($output_file, 'wb');
                    fwrite($ifp, base64_decode($base64));
                    fclose($ifp);
                    $professionalUpdate->photo = $dns_path;
                } else {
                    $professionalUpdate->photo = $professional['photo'];
                }

                if ($this->Professionals->save($professionalUpdate)) {
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar os dados do profissional.';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $ProfessionalsAddresses = TableRegistry::getTableLocator()->get('ProfessionalsAddresses');
            $professional['professionalsAddresses'] = $ProfessionalsAddresses->find('all')
                ->where(['ProfessionalsAddresses.professional_id = ' => $professional['id']])
                ->contain(['Cities', 'Cities.States'])
                ->all();

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
