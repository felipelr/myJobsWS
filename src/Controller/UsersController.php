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
        $this->Auth->allow(['login']);
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

    public function add()
    {
        $user = $this->Users->newEntity();
        $errorMessage = '';
        $newUser = null;
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $user = $this->Users->patchEntity($user, $requestData);

            if ($user['email'] == '') {
                $errorMessage = 'O email não foi informado.';
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
                    $user['role_id'] = 1;

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
}
