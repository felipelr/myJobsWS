<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

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

    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => ['Users', 'ClientsAddress']
        ]);

        $this->set('client', $client);
    }

    public function edit($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);

        $errorMessage = '';
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $image = $this->request->getData('image');
                $client = $this->Clients->patchEntity($client, $this->request->getData());
                if (isset($image)) {
                    $base64 = $image;
                    $output_file = WWW_ROOT . 'img' . DS . 'client-' . $client['id'] . '.jpeg';
                    $ifp = fopen($output_file, 'wb');
                    fwrite($ifp, base64_decode($base64));
                    fclose($ifp);
                    $client['image_path'] = $output_file;
                }
                else{
                    $client['image_path'] = $image;
                }

                if ($this->Clients->save($client)) {
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
