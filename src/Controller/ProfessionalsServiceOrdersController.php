<?php

namespace App\Controller;

use App\Controller\AppController;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Exception;

/**
 * ProfessionalsServiceOrders Controller
 *
 * @property \App\Model\Table\ProfessionalsServiceOrdersTable $ProfessionalsServiceOrders
 *
 * @method \App\Model\Entity\ProfessionalsServiceOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfessionalsServiceOrdersController extends AppController
{
    public function accept()
    {
        $errorMessage = '';
        $professionalsServiceOrder = $this->ProfessionalsServiceOrders->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $professionalsServiceOrder = $this->ProfessionalsServiceOrders->patchEntity($professionalsServiceOrder, $this->request->getData());

                if ($this->ProfessionalsServiceOrders->save($professionalsServiceOrder)) {
                    //sucesso
                    $errorMessage = '';

                    $clientsServiceOrders = $this->ProfessionalsServiceOrders->ClientsServiceOrders->get(
                        $professionalsServiceOrder->clients_service_orders_id,
                        [
                            'contain' => ['Clients', 'Clients.Users'],
                        ]
                    );

                    $professional = $this->ProfessionalsServiceOrders->Professionals->get($professionalsServiceOrder->professional_id);

                    $tokenApp = $clientsServiceOrders->client->user->fcm_token == null ? '' : $clientsServiceOrders->client->user->fcm_token;
                    $title = $clientsServiceOrders->client->name;
                    if ($tokenApp != '') {
                        try {
                            $factory = (new Factory())
                                ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                            $messaging = $factory->createMessaging();

                            $messageFCM = CloudMessage::withTarget('token', $tokenApp)
                                ->withNotification([
                                    'title' => $title,
                                    'body' => 'Um orçamento disponibilizado por ' . $professional->name,
                                    'icon' => 'ic_strab',
                                ])
                                ->withData([
                                    'message' => json_encode([
                                        'type' => 'service_order_new_budget',
                                        'to' => 'client',
                                        'professional_id' => $professionalsServiceOrder->professional_id,
                                        'client_id' => $clientsServiceOrders->client_id,
                                        'clients_service_orders_id' => $professionalsServiceOrder->clients_service_orders_id,
                                    ])
                                ]);

                            $messaging->send($messageFCM);
                        } catch (Exception $ex) {
                        }
                    }
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar a aceitação do orçamento.' . json_encode($professionalsServiceOrder->getErrors());
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'professionalsServiceOrder' => $professionalsServiceOrder,
                '_serialize' => ['professionalsServiceOrder']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function getByProfessional($id = null)
    {
        $errorMessage = '';
        $professionalsServiceOrders = $this->ProfessionalsServiceOrders->find('all')
            ->where(['professional_id' => $id])
            ->order(['created' => 'DESC'])
            ->all();

        if ($errorMessage == '') {
            $this->set([
                'professionalsServiceOrders' => $professionalsServiceOrders,
                '_serialize' => ['professionalsServiceOrders']
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
