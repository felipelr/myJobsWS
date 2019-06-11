<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

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
                    'token' => JWT::encode([
                        'sub' => $user['id'],
                        'exp' =>  time() + 3600, // 1 hour
                        'role' => $user['role']['name']
                    ],
                    Security::salt())
                ],
                '_serialize' => ['success', 'data']
            ]);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
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
}
