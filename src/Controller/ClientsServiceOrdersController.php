<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;
use Cake\ORM\TableRegistry;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

/**
 * ClientsServiceOrders Controller
 *
 * @property \App\Model\Table\ClientsServiceOrdersTable $ClientsServiceOrders
 *
 * @method \App\Model\Entity\ClientsServiceOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsServiceOrdersController extends AppController
{
    public function add()
    {
        $errorMessage = '';
        $clientsServiceOrders = $this->ClientsServiceOrders->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $clientsServiceOrders = $this->ClientsServiceOrders->patchEntity($clientsServiceOrders, $this->request->getData());

                if ($this->ClientsServiceOrders->save($clientsServiceOrders)) {
                    //sucesso                
                    $errorMessage = '';

                    try {
                        $ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices');
                        $arrProfessionals = $ProfessionalServices->find('all')
                            ->contain(['Professionals', 'Professionals.Users', 'Services'])
                            ->where(['service_id' => $clientsServiceOrders->service_id])
                            ->all();

                        $deviceTokens = [];
                        $service_name = '';
                        $service_id = 0;
                        foreach ($arrProfessionals as $row) {
                            if ($row->professional->user->fcm_token != null &&  $row->professional->user->fcm_token != '') {
                                $deviceTokens[] = $row->professional->user->fcm_token;
                                $service_name =  $row->service->title;
                                $service_id = $row->service->id;
                            }
                        }

                        $factory = (new Factory())
                            ->withServiceAccount(WWW_ROOT . 'myjobstest-719a9-firebase-adminsdk-bjq4h-db0fea2767.json');
                        $messaging = $factory->createMessaging();

                        $messageFCM = CloudMessage::new()
                            ->withNotification([
                                'title' => 'Proposta de orçamento',
                                'body' => 'Há uma nova proposta de orçamento disponível para o serviço ' . $service_name,
                                'icon' => 'ic_strab',
                            ])
                            ->withData([
                                'message' => json_encode([
                                    'type' => 'service_order_add',
                                    'to' => 'professional',
                                    'service_id' => $service_id,
                                ])
                            ]);

                        $messaging->sendMulticast($messageFCM, $deviceTokens);
                    } catch (Exception $ex) {
                    }
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar a ordem de orçamento.' . json_encode($clientsServiceOrders->getErrors());
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'clientsServiceOrders' => $clientsServiceOrders,
                '_serialize' => ['clientsServiceOrders']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function accept($id)
    {
        $errorMessage = '';
        $clientsServiceOrder = [];
        try {
            $professional_id = $this->request->getData('professional_id');
            $clientsServiceOrder = $this->ClientsServiceOrders->get($id);
            $clientsServiceOrder->professional_selected = $professional_id;
            $clientsServiceOrder->date_selected = strtotime('now');
            $clientsServiceOrder->status = 'accepted';

            if ($this->ClientsServiceOrders->save($clientsServiceOrder)) {
                $errorMessage = '';
                $Calls = TableRegistry::getTableLocator()->get('Calls');
                $call = $Calls->newEntity();
                $call->professional_id = $clientsServiceOrder->professional_selected;
                $call->client_id = $clientsServiceOrder->client_id;
                $call->service_id = $clientsServiceOrder->service_id;
                $call->description = $clientsServiceOrder->description;
                $call->status = 1;
                $call->confirm = 1;
                $Calls->save($call);
            } else {
                $errorMessage = 'Não foi possível aceitar o orçamento: ' . json_encode($clientsServiceOrder->getErrors());
            }
        } catch (Exception $ex) {
            $errorMessage = $ex->getMessage();
        }

        if ($errorMessage == '') {
            $this->set([
                'clientsServiceOrder' => $clientsServiceOrder,
                '_serialize' => ['clientsServiceOrder']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function getByProfessional($id)
    {
        $ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices');
        $arrServices = $ProfessionalServices->find('all')
            ->where([
                'professional_id' => $id,
                'active' => 1
            ])->all();

        $orders = [];
        $services = [];
        if (isset($arrServices)) {
            if (count($arrServices) > 0) {
                foreach ($arrServices as $row) {
                    $services[] = $row->service_id;
                }
            }
        }

        if (count($services) > 0) {
            $orders = $this->ClientsServiceOrders->find('all')
                ->where([
                    'service_id IN ' => $services,
                    'status' => 'opened'
                ])
                ->contain(['Clients', 'Services', 'Services.Subcategories', 'Services.Subcategories.Categories'])
                ->order(['ClientsServiceOrders.created' => 'DESC'])
                ->all();
        }

        $this->set([
            'orders' => $orders,
            '_serialize' => ['orders']
        ]);
    }

    public function getByClient($id)
    {
        $orders = $this->ClientsServiceOrders->find('all')
            ->where([
                'client_id' => $id,
                'status' => 'opened'
            ])
            ->contain(['ProfessionalsServiceOrders', 'ProfessionalsServiceOrders.Professionals', 'Services'])
            //->matching('ProfessionalsServiceOrders')
            ->order(['ClientsServiceOrders.created' => 'DESC'])
            ->all();

        $this->set([
            'orders' => $orders,
            '_serialize' => ['orders']
        ]);
    }
}
