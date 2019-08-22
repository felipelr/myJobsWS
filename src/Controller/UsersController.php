<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use Cake\ORM\TableRegistry;

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
        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['login', 'add', 'socialMidiaVerify']);
    }

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

    public function index()
    {
        $users = $this->Users->find('all')
            ->where(['Users.active = ' => true])
            ->limit(10);

        $this->set([
            'users' => $users,
            '_serialize' => ['users']
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

    public function add()
    {
        $user = $this->Users->newEntity();
        $errorMessage = '';
        $newUser = null;
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
                }
            }

            if ($errorMessage == '') {
                $userExistent = $this->Users->find('all')
                    ->where(['Users.email = ' => $user['email']])
                    ->limit(1);

                if ($userExistent->count() > 0) {
                    //not ok
                    $errorMessage = 'Já existe um usuário cadastrado com esse email.';
                } else {
                    //fixo por enquanto
                    $user['role_id'] = $requestData['userType'] == '1' ? 1 : 2;
                    $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT, ["cost" => 10]);

                    if ($this->Users->save($user)) {
                        if ($requestData['userType'] == 1) {
                            $Clients = TableRegistry::getTableLocator()->get('Clients');
                            $client = $Clients->newEntity();
                            $client = $Clients->patchEntity($client, $requestData);

                            $client['user_id'] = $user['id'];

                            if ($client['name'] == '') {
                                //not ok
                                $errorMessage = 'O nome não foi informado.';
                            } else if ($Clients->save($client)) {
                                //ok
                                $errorMessage = '';
                                $newUser = $Clients->get($client['id'], [
                                    'contain' => ['Users']
                                ]);
                            } else {
                                //not ok
                                $this->Users->delete($user);
                                $errorMessage = 'Não foi possível criar o usuário.' . json_encode($client->getErrors());
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
        $clientRow = null;
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            if (!(isset($requestData['socialMidiaId']) && $requestData['socialMidiaId'] != '')) {
                $errorMessage = 'Os dados informados estão incompletos.';
            }

            if (!(isset($requestData['socialMidiaType']) && $requestData['socialMidiaType'] != '')) {
                $errorMessage = 'Os dados informados estão incompletos.';
            }

            if ($errorMessage == '') {
                if ($requestData['socialMidiaType'] == 'facebook') {
                    $queryUsers = $this->Users->find('all')
                        ->where(['Users.facebook_id = ' => $requestData['socialMidiaId']])
                        ->limit(1);

                    if ($queryUsers->count() > 0) {
                        $userRow = $queryUsers->first();
                        $Clients = TableRegistry::getTableLocator()->get('Clients');
                        $queryClient = $Clients->find('all')
                            ->where(['Clients.user_id = ' => $userRow['id']])
                            ->contain(['Users'])
                            ->limit(1);
                        $clientRow = $queryClient->first();
                    } else {
                        $errorMessage = 'Usuário não encontrado.';
                    }
                } else if ($requestData['socialMidiaType'] == 'google') {
                    $queryUsers = $this->Users->find('all')
                        ->where(['Users.google_id = ' => $requestData['socialMidiaId']])
                        ->limit(1);

                    if ($queryUsers->count() > 0) {
                        $userRow = $queryUsers->first();
                        $Clients = TableRegistry::getTableLocator()->get('Clients');
                        $queryClient = $Clients->find('all')
                            ->where(['Clients.user_id = ' => $userRow['id']])
                            ->contain(['Users'])
                            ->limit(1);
                        $clientRow = $queryClient->first();
                    } else {
                        $errorMessage = 'Usuário não encontrado.';
                    }
                } else {
                    $errorMessage = 'Tipo de social mídia inválido.';
                }
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'user' => $clientRow,
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
}
