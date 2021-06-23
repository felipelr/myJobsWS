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

                //validar se já não excedeu o limite de orçamentos solicitados pelo cliente
                $clientsServiceOrders = $this->ProfessionalsServiceOrders->ClientsServiceOrders->get(
                    $professionalsServiceOrder->clients_service_orders_id,
                    [
                        'contain' => ['Clients', 'Clients.Users'],
                    ]
                );

                if ($clientsServiceOrders->quantity_professionals < $clientsServiceOrders->quantity) {
                    if ($this->ProfessionalsServiceOrders->save($professionalsServiceOrder)) {
                        //sucesso
                        $errorMessage = '';

                        //atualizar quantidade de profissionals q enviaram orçamentos
                        $clientsServiceOrders->quantity_professionals = $clientsServiceOrders->quantity_professionals + 1;
                        $this->ProfessionalsServiceOrders->ClientsServiceOrders->save($clientsServiceOrders);

                        //enviar notificação para o cliente
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
                                        'body' => 'O profissional ' . $professional->name . ' deseja enviar uma proposta de orçamento.',
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
                        $errorMessage = 'Não foi possível abrir a solicitação para ver os dados do cliente.';
                    }
                } else {
                    $errorMessage = 'Este cliente já recebeu a quantidade de orçamentos que ele solicitou.';
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
