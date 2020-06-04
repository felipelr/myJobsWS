<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;
use Cake\ORM\TableRegistry;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function edit($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => ['Users']
        ]);

        $errorMessage = '';
        $photo = $client->photo;

        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $image = $this->request->getData('image');
                $client = $this->Clients->patchEntity($client, $this->request->getData());
                $clientUpdate = $this->Clients->newEntity();
                $clientUpdate->id = $client['id'];
                $clientUpdate->name = $client['name'];
                $clientUpdate->phone = $client['phone'];
                $clientUpdate->document = $client['document'];
                $clientUpdate->date_birth = $client['date_birth'];
                $clientUpdate->gender = $client['gender'];

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
                    $output_file = WWW_ROOT . 'img' . DS . 'client-' . $client['id'] . '-' . $time . '.jpeg';
                    $dns_path = "http://67.205.160.187/ws" . DS . 'img' . DS . 'client-' . $client['id'] . '-' . $time . '.jpeg';

                    $ifp = fopen($output_file, 'wb');
                    fwrite($ifp, base64_decode($base64));
                    fclose($ifp);
                    $clientUpdate->photo = $dns_path;
                } else {
                    $clientUpdate->photo = $client['photo'];
                }

                if ($this->Clients->save($clientUpdate)) {
                    $client->name = $clientUpdate->name;
                    $client->phone =  $clientUpdate->phone;
                    $client->document = $clientUpdate->document;
                    $client->date_birth = $clientUpdate->date_birth;
                    $client->gender = $clientUpdate->gender;
                    $client->photo = $clientUpdate->photo;
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar os dados do cliente.';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $ClientsAddresses = TableRegistry::getTableLocator()->get('ClientsAddresses');
            $client['modified'] = date('Y-m-d H:i:s');
            $client['clientsAddresses'] = $ClientsAddresses->find('all')
                ->where(['ClientsAddresses.client_id = ' => $client['id']])
                ->contain(['Cities', 'Cities.States'])
                ->all();

            $this->set([
                'client' => $client,
                '_serialize' => ['client']
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
