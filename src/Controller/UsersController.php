<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['login', 'add', 'socialMidiaVerify', 'signin']);
    }

    public function logout()
    {
        $this->Auth->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'signin']);
    }

    public function signin()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if ($user['role_id'] == 3) {
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'Home', 'action' => 'index']);
                } else {
                    $this->Auth->logout();
                    $this->Flash->error(__('O email ou a senha estão inválidos.'));
                }
            } else {
                $this->Flash->error(__('O email ou a senha estão inválidos.'));
            }
        } else {
            $this->render();
        }
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Clients', 'Professionals', 'Roles']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    //API WS
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (!$user) {
                throw new UnauthorizedException('O email ou a senha estão inválidos.');
            }
            $this->set([
                'success' => true,
                'data' => [
                    'token' => JWT::encode(
                        [
                            'sub' => $user['id'],
                            'exp' =>  time() + (30 * 24 * 60 * 60), // 30 dias
                            'role' => $user['role']['name']
                        ],
                        Security::salt()
                    )
                ],
                '_serialize' => ['success', 'data']
            ]);
        }
    }

    public function view($id)
    {
        $queryUser = $this->Users->find('all')
            ->where(['Users.id = ' => $id])
            ->limit(1);
        $userRow = $queryUser->first();

        if (isset($userRow)) {
            $Clients = TableRegistry::getTableLocator()->get('Clients');
            $Professionals = TableRegistry::getTableLocator()->get('Professionals');
            $ClientsAddresses = TableRegistry::getTableLocator()->get('ClientsAddresses');
            $ProfessionalsAddresses = TableRegistry::getTableLocator()->get('ProfessionalsAddresses');

            $clientRow = $Clients->find('all')
                ->where(['Clients.user_id = ' => $id])
                ->first();
            if (isset($clientRow['id'])) {
                $clientRow['clientsAddresses'] = $ClientsAddresses->find('all')
                    ->where(['ClientsAddresses.client_id = ' => $clientRow['id']])
                    ->contain(['Cities', 'Cities.States'])
                    ->all();
            }

            $professionalRow = $Professionals->find('all')
                ->where(['Professionals.user_id = ' => $id])
                ->first();
            if (isset($professionalRow['id'])) {
                $professionalRow['professionalsAddresses'] = $ProfessionalsAddresses->find('all')
                    ->where(['ProfessionalsAddresses.professional_id = ' => $professionalRow['id']])
                    ->contain(['Cities', 'Cities.States'])
                    ->all();
            }

            $userRow['client'] = $clientRow;
            $userRow['professional'] = $professionalRow;
        }

        $this->set([
            'user' => $userRow,
            '_serialize' => ['user']
        ]);
    }

    function validateUser($requestData)
    {
        $errorMessage = '';

        if (!(isset($requestData['email']) && $requestData['email'] != '')) {
            $errorMessage = 'O email é inválido.';
        }

        if (!(isset($requestData['password']) && $requestData['password'] != '')) {
            $errorMessage = 'A senha é inválida.';
        }

        if (!(isset($requestData['userType']) && $requestData['userType'] != '')) {
            $errorMessage = 'O tipo de usuário é inválido.';
        }

        return $errorMessage;
    }

    function validateClient($requestData)
    {
        $errorMessage = '';

        if (!(isset($requestData['name']) && $requestData['name'] != '')) {
            $errorMessage = 'O nome é inválido.';
        }

        if (!(isset($requestData['phone']) && $requestData['phone'] != '')) {
            $errorMessage = 'O telefone é inválido.';
        }

        if (!(isset($requestData['document']) && $requestData['document'] != '')) {
            $errorMessage = 'O CPF/CNPJ é inválido.';
        }

        if (!(isset($requestData['date_birth']) && $requestData['date_birth'] != '')) {
            $errorMessage = 'A data de nascimento é inválido.';
        }

        if (!(isset($requestData['gender']) && $requestData['gender'] != '')) {
            $errorMessage = 'O gênero é inválido.';
        }

        return $errorMessage;
    }

    function validateProfessional($requestData)
    {
        $errorMessage = '';

        if (!(isset($requestData['name']) && $requestData['name'] != '')) {
            $errorMessage = 'O nome é inválido.';
        }

        if (!(isset($requestData['phone']) && $requestData['phone'] != '')) {
            $errorMessage = 'O telefone é inválido.';
        }

        /* if (!(isset($requestData['document']) && $requestData['document'] != '')) {
            $errorMessage = 'O CPF/CNPJ é inválido.';
        } */

        if (!(isset($requestData['date_birth']) && $requestData['date_birth'] != '')) {
            $errorMessage = 'A data de nascimento é inválido.';
        }

        if (!(isset($requestData['gender']) && $requestData['gender'] != '')) {
            $errorMessage = 'O gênero é inválido.';
        }

        return $errorMessage;
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        $errorMessage = '';
        $userId = 0;
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $user = $this->Users->patchEntity($user, $requestData);

            //validações do user
            $errorMessage = $this->validateUser($requestData);

            if ($errorMessage == '') {
                if ($requestData['userType'] == '1') {
                    //validações do client
                    $errorMessage = $this->validateClient($requestData);
                } else {
                    //validações do professional
                    $errorMessage =  $this->validateProfessional($requestData);
                }
            }

            $Clients = TableRegistry::getTableLocator()->get('Clients');
            $Professionals = TableRegistry::getTableLocator()->get('Professionals');

            if ($errorMessage == '') {
                $userExistent = $this->Users->find('all')
                    ->where(['Users.email = ' => $user->email])
                    ->limit(1);

                if ($userExistent->count() > 0) {
                    //not ok
                    $errorMessage = 'Já existe um usuário cadastrado com esse email.';
                } else {
                    //fixo por enquanto
                    $user->role_id = $requestData['userType'] == '1' ? 1 : 2;
                    $user->password = password_hash($user->password, PASSWORD_BCRYPT, ["cost" => 10]);

                    if ($this->Users->save($user)) {
                        $client = $Clients->newEntity();
                        $client = $Clients->patchEntity($client, $requestData);

                        $userId = $user->id;
                        $client->user_id = $userId;
                        $client->photo = '';

                        if ($Clients->save($client)) {
                            //ok
                            $errorMessage = '';
                        } else {
                            //not ok
                            $this->Users->delete($user);
                            $errorMessage = 'Não foi possível criar o usuário. ' . json_encode($client->errors());
                        }

                        if ($errorMessage == '') {
                            if ($requestData['userType'] == 2) {
                                $professional = $Professionals->newEntity();
                                $professional = $Professionals->patchEntity($professional, $requestData);

                                $professional->user_id = $userId;
                                $professional->photo = '';
                                $professional->backImage = '';

                                if ($Professionals->save($professional)) {
                                    //ok
                                    $errorMessage = '';
                                } else {
                                    //not ok
                                    $Clients->delete($client);
                                    $this->Users->delete($user);
                                    $errorMessage = 'Não foi possível criar o usuário.' . json_encode($professional->getErrors());
                                }
                            }
                        }
                    } else {
                        //not ok
                        $errorMessage = 'Não foi possível criar o usuário.';
                    }
                }
            }
        }

        if ($errorMessage == '') {
            $newUser = $this->Users->get($userId, [
                'contain' => ['Clients', 'Professionals']
            ]);
            $this->set([
                'newUser' => $newUser,
                '_serialize' => ['newUser']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function socialMidiaVerify()
    {
        $errorMessage = '';
        $userRow = null;
        $clientRow = null;
        $professionalRow = null;
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            if (!(isset($requestData['socialMidiaId']) && $requestData['socialMidiaId'] != '')) {
                $errorMessage = 'Os dados informados estão incompletos.';
            }

            if (!(isset($requestData['socialMidiaType']) && $requestData['socialMidiaType'] != '')) {
                $errorMessage = 'Os dados informados estão incompletos.';
            }

            if ($errorMessage == '') {
                $queryUsers = null;

                if ($requestData['socialMidiaType'] == 'facebook') {
                    $queryUsers = $this->Users->find('all')
                        ->where(['Users.facebook_token = ' => $requestData['socialMidiaId']])
                        ->limit(1);
                } else if ($requestData['socialMidiaType'] == 'google') {
                    $queryUsers = $this->Users->find('all')
                        ->where(['Users.google_token = ' => $requestData['socialMidiaId']])
                        ->limit(1);
                } else {
                    $errorMessage = 'Tipo de social mídia inválido.';
                }

                if ($queryUsers != null) {
                    if ($queryUsers->count() > 0) {
                        $userRow = $queryUsers->first();
                        $Clients = TableRegistry::getTableLocator()->get('Clients');
                        $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                        $ClientsAddresses = TableRegistry::getTableLocator()->get('ClientsAddresses');
                        $ProfessionalsAddresses = TableRegistry::getTableLocator()->get('ProfessionalsAddresses');

                        $clientRow = $Clients->find('all')
                            ->where(['Clients.user_id = ' => $userRow['id']])
                            ->contain(['Users'])
                            ->first();
                        if (isset($clientRow['id'])) {
                            $clientRow['clientsAddresses'] = $ClientsAddresses->find('all')
                                ->where(['ClientsAddresses.client_id = ' => $clientRow['id']])
                                ->contain(['Cities', 'Cities.States'])
                                ->all();
                        }

                        $professionalRow = $Professionals->find('all')
                            ->where(['Professionals.user_id = ' => $userRow['id']])
                            ->contain(['Users'])
                            ->first();
                        if (isset($professionalRow['id'])) {
                            $professionalRow['professionalsAddresses'] = $ProfessionalsAddresses->find('all')
                                ->where(['ProfessionalsAddresses.professional_id = ' => $professionalRow['id']])
                                ->contain(['Cities', 'Cities.States'])
                                ->all();
                        }
                    } else {
                        $errorMessage = 'Usuário não encontrado.';
                    }
                }
            }
        }

        $userRow['client'] = $clientRow;
        $userRow['professional'] = $professionalRow;

        if ($errorMessage == '') {
            $this->set([
                'user' => $userRow,
                '_serialize' => ['user']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                'id' => $requestData['socialMidiaId'],
                'type' => $requestData['socialMidiaType'],
                '_serialize' => ['error', 'errorMessage', 'id', 'type']
            ]);
        }
    }

    public function changePassword()
    {
        $errorMessage = '';
        $user = null;
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');
            $currentPassword = $this->request->getData('currentPassword');
            $newPassword = $this->request->getData('newPassword');

            if (!(isset($id) && isset($currentPassword) && isset($newPassword))) {
                $errorMessage = 'Os dados informados estão incompletos.';
            }

            if ($errorMessage == '') {
                $user = $this->Users->get($id);
                if (isset($user)) {
                    $hashNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, ["cost" => 10]);

                    if (password_verify($currentPassword, $user->password)) {
                        $newUser = $this->Users->newEntity();
                        $newUser->id = $id;
                        $newUser->password = $hashNewPassword;
                        if (!$this->Users->save($newUser)) {
                            $errorMessage = 'Não foi possível alterar a senha.';
                        }
                    } else {
                        $errorMessage = 'A senha atual informada é inválida.';
                    }
                } else {
                    $errorMessage = 'Usuário não localizado.';
                }
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'user' => $user,
                '_serialize' => ['user']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function updateFcmToken()
    {
        $errorMessage = '';
        try {
            if ($this->request->is('post')) {
                $user_id = $this->request->getData('user_id');
                $fcm_token = $this->request->getData('fcm_token');
                $user = $this->Users->get($user_id);

                if (isset($user)) {
                    $user->fcm_token = $fcm_token;
                    if (!$this->Users->save($user)) {
                        $errorMessage = 'Não foi possível alterar o FCM Token.';
                    }
                } else {
                    $errorMessage = 'Usuário não localizado. user_id => ' . $user_id;
                }
            }
        } catch (Exception $ex) {
            $errorMessage = $ex->getMessage() . '\n' . json_encode($this->request->getData());
        }

        if ($errorMessage == '') {
            $this->set([
                'success' => true,
                '_serialize' => ['success']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function validate()
    {
        $this->set([
            'success' => true,
            '_serialize' => ['success']
        ]);
    }
}
