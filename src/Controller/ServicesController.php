<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ServicesController extends AppController
{
    public function index()
    {
        $search = $this->request->getQuery('search', '');
        if ($search != '') {
            $this->paginate = [
                'contain' => ['Subcategories'],
                'conditions' => [
                    'OR' => [
                        'Services.description LIKE' => "%$search%",
                        'Services.title LIKE' => "%$search%",
                        'Subcategories.description LIKE' => "%$search%",
                    ]
                ]
            ];
        } else {
            $this->paginate = [
                'contain' => ['Subcategories']
            ];
        }

        $services = $this->paginate($this->Services);

        $this->set(compact('services', 'search'));
    }

    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Subcategories']
        ]);

        $this->set('service', $service);
    }

    public function add()
    {
        $service = $this->Services->newEntity();
        if ($this->request->is('post')) {
            $service = $this->Services->patchEntity($service, $this->request->getData());
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }
        $subcategories = $this->Services->Subcategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'description',
            'groupField' => 'category.description'
        ])->contain(['Categories']);
        $this->set(compact('service', 'subcategories'));
    }

    public function edit($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, $this->request->getData());
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }
        $subcategories = $this->Services->Subcategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'description',
            'groupField' => 'category.description'
        ])->contain(['Categories']);
        $this->set(compact('service', 'subcategories'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $service = $this->Services->get($id);
        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function subcategory($id = null)
    {
        $services = $this->Services->find('all')
            ->contain(['Subcategories'])
            ->where(['Services.subcategory_id = ' => $id]);

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);
    }

    public function getBySubcategory($idSubcategory = null)
    {
        $professional_id = $this->request->getQuery('professional_id', 0);
        if ($professional_id == 0) {
            $connection = ConnectionManager::get('default');
            $services = $connection->execute(
                "SELECT s.*, COUNT(p.professional_id) AS Profissionais 
                FROM services AS s
                LEFT JOIN professional_services AS p ON(s.id = p.service_id AND p.active = 1)
                WHERE subcategory_id = $idSubcategory
                GROUP BY s.id
                ORDER BY s.title"
            )
                ->fetchAll('assoc');

            $this->set([
                'services' => $services,
                '_serialize' => ['services']
            ]);
        } else {
            $connection = ConnectionManager::get('default');
            $services = $connection->execute(
                "SELECT s.*, COUNT(p.professional_id) AS Profissionais 
                FROM services AS s
                INNER JOIN professional_services AS p ON(s.id = p.service_id AND p.active = 1)
                WHERE subcategory_id = $idSubcategory AND p.professional_id = $professional_id
                GROUP BY s.id
                ORDER BY s.title"
            )
                ->fetchAll('assoc');

            $this->set([
                'services' => $services,
                '_serialize' => ['services']
            ]);
        }
    }
}
