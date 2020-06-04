<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use DateTime;
use Exception;
use Cake\Datasource\ConnectionManager;

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
        $photo = $professional->photo;
        $backImage = $professional->backImage;

        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $image = $this->request->getData('image');
                $imageBackground = $this->request->getData('imageBackground');
                $professional = $this->Professionals->patchEntity($professional, $this->request->getData());
                $professionalUpdate = $this->Professionals->newEntity();
                $professionalUpdate->id = $professional['id'];
                $professionalUpdate->name = $professional['name'];
                $professionalUpdate->phone = $professional['phone'];
                $professionalUpdate->document = $professional['document'];
                $professionalUpdate->date_birth = $professional['date_birth'];
                $professionalUpdate->description = $professional['description'];

                $professional = $this->Professionals->get($professional['id']);

                if (isset($image) && $image != '') {
                    try {
                        $explodePhoto = explode("/", $photo);
                        $count = count($explodePhoto);
                        $outputFile = WWW_ROOT . 'img' . DS . $explodePhoto[$count - 1];
                        if (file_exists($outputFile))
                            if (!is_dir($outputFile))
                                unlink($outputFile);
                    } catch (Exception $ex) {
                    }

                    $time = time();
                    $base64 = $image;
                    $output_file = WWW_ROOT . 'img' . DS . 'professional-' . $professional['id'] . '-' . $time . '.jpeg';
                    $dns_path = "http://67.205.160.187/ws" . DS . 'img' . DS . 'professional-' . $professional['id'] . '-' . $time  . '.jpeg';

                    $ifp = fopen($output_file, 'wb');
                    fwrite($ifp, base64_decode($base64));
                    fclose($ifp);
                    $professionalUpdate->photo = $dns_path;
                } else {
                    $professionalUpdate->photo = $professional['photo'];
                }

                if (isset($imageBackground) && $imageBackground != '') {
                    try {
                        $explodePhoto = explode("/", $backImage);
                        $count = count($explodePhoto);
                        $outputFile = WWW_ROOT . 'img' . DS . $explodePhoto[$count - 1];
                        if (file_exists($outputFile))
                            if (!is_dir($outputFile))
                                unlink($outputFile);
                    } catch (Exception $ex) {
                    }

                    $time = time();
                    $base64 = $imageBackground;
                    $output_file = WWW_ROOT . 'img' . DS . 'professional-back-' . $professional['id'] . '-' . $time . '.jpeg';
                    $dns_path = "http://67.205.160.187/ws" . DS . 'img' . DS . 'professional-back-' . $professional['id'] . '-' . $time . '.jpeg';

                    $ifp = fopen($output_file, 'wb');
                    fwrite($ifp, base64_decode($base64));
                    fclose($ifp);
                    $professionalUpdate->backImage = $dns_path;
                } else {
                    $professionalUpdate->backImage = $professional['backImage'];
                }

                if ($this->Professionals->save($professionalUpdate)) {
                    $professional->name = $professionalUpdate->name;
                    $professional->phone =  $professionalUpdate->phone;
                    $professional->document = $professionalUpdate->document;
                    $professional->date_birth = $professionalUpdate->date_birth;
                    $professional->description = $professionalUpdate->description;
                    $professional->photo = $professionalUpdate->photo;
                    $professional->backImage = $professionalUpdate->backImage;
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
            $professional['modified'] = date('Y-m-d H:i:s');
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

    public function getByService($idService = null)
    {
        $connection = ConnectionManager::get('default');
        $professionals = $connection->execute(
            "SELECT p.id, p.name, p.description, ps.rating, ps.amount_ratings, CONCAT('\"', COUNT(c.id), 
            (CASE WHEN COUNT(c.id) <= 1 THEN ' atendimento realizado\"' ELSE ' atendimentos realizados\"' END)) AS info, 
            p.photo, p.backImage, p.phone
            FROM professionals AS p 
            INNER JOIN professional_services AS ps ON(ps.professional_id = p.id AND ps.active = 1)
            INNER JOIN services AS s ON(s.id = ps.service_id)
            LEFT JOIN calls AS c ON (s.id = c.service_id)
            WHERE ps.service_id = $idService
            GROUP BY p.id, p.name, p.description, ps.rating, ps.amount_ratings, p.photo, p.backImage"
        )
            ->fetchAll('assoc');

        $this->set([
            'professionals' => $professionals,
            '_serialize' => ['professionals']
        ]);
    }
}
