<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ServicesController extends AppController
{
    public function index()
    {
        $this->paginate = [
            'contain' => ['Subcategories']
        ];
        $services = $this->paginate($this->Services);

        $this->set(compact('services'));
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

    public function getBySubcategory($idSubcategory = null)
    {
        $connection = ConnectionManager::get('default');
        $services = $connection->execute(
            "   SELECT  s.*, COUNT(p.professional_id) AS Profissionais  FROM services AS s
            LEFT JOIN professional_services AS p ON(s.id = p.service_id)
            WHERE subcategory_id = $idSubcategory
            GROUP BY s.id
            ORDER BY title "
        )
            ->fetchAll('assoc');

        $this->set([
            'services' => $services,
            '_serialize' => ['services']
        ]);
    }
}
