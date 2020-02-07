<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Highlights Controller
 *
 * @property \App\Model\Table\HighlightsTable $Highlights
 *
 * @method \App\Model\Entity\Highlight[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HighlightsController extends AppController
{
    public function index()
    {
        $search = $this->request->getQuery('search', '');
        if ($search != '') {
            $this->paginate = [
                'contain' => ['Professionals', 'Services', 'Subcategories'],
                'conditions' => [
                    'OR' => [
                        'Professionals.name LIKE' => "%$search%",
                        'Subcategories.description LIKE' => "%$search%",
                        'Services.title LIKE' => "%$search%",
                    ]
                ]
            ];
        } else {
            $this->paginate = [
                'contain' => ['Professionals', 'Services', 'Subcategories']
            ];
        }

        $highlights = $this->paginate($this->Highlights);

        $this->set(compact('highlights', 'search'));
    }

    public function view($id = null)
    {
        $highlight = $this->Highlights->get($id, [
            'contain' => ['Professionals', 'Services', 'Subcategories']
        ]);

        $this->set('highlight', $highlight);
    }

    public function add()
    {
        $highlight = $this->Highlights->newEntity();
        if ($this->request->is('post')) {
            $highlight = $this->Highlights->patchEntity($highlight, $this->request->getData());
            if ($this->Highlights->save($highlight)) {
                $this->Flash->success(__('The highlight has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The highlight could not be saved. Please, try again.'));
        }

        $professionals = $this->Highlights->Professionals->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
        ]);

        $services = $this->Highlights->Services->find('list', [
            'keyField' => 'id',
            'valueField' => 'title',
            'groupField' => 'subcategory.description'
        ])->contain(['Subcategories']);

        $subcategories = $this->Highlights->Subcategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'description',
            'groupField' => 'category.description'
        ])->contain(['Categories']);

        $this->set(compact('highlight', 'professionals', 'services', 'subcategories'));
    }

    public function edit($id = null)
    {
        $highlight = $this->Highlights->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $highlight = $this->Highlights->patchEntity($highlight, $this->request->getData());
            if ($this->Highlights->save($highlight)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        $professionals = $this->Highlights->Professionals->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
        ]);

        $services = $this->Highlights->Services->find('list', [
            'keyField' => 'id',
            'valueField' => 'title',
            'groupField' => 'subcategory.description'
        ])->contain(['Subcategories']);

        $subcategories = $this->Highlights->Subcategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'description',
            'groupField' => 'category.description'
        ])->contain(['Categories']);

        $this->set(compact('highlight', 'professionals', 'services', 'subcategories'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $highlight = $this->Highlights->get($id);
        if ($this->Highlights->delete($highlight)) {
            $this->Flash->success(__('The highlight has been deleted.'));
        } else {
            $this->Flash->error(__('The highlight could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function highlights()
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem FROM highlights as h
         INNER JOIN professionals as p ON(h.professional_id = p.id)
         INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
         WHERE p.active = 1
         GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }

    public function highlightsBySubcategory($idSubcategory = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "   SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem 
                FROM highlights as h
                INNER JOIN professionals as p ON(h.professional_id = p.id)
                INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
                WHERE p.active = 1 and h.subcategory_id = $idSubcategory
                GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }

    public function highlightsByService($idService = null)
    {
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "   SELECT p.id, p.name, p.description, count(ps.service_id) as qtdeServices, p.photo as imagem 
                FROM highlights as h
                INNER JOIN professionals as p ON(h.professional_id = p.id)
                INNER JOIN professional_services as ps ON(ps.professional_id = p.id)
                WHERE p.active = 1 and h.service_id = $idService
                GROUP BY p.id"
        )
            ->fetchAll('assoc');

        $this->set([
            'highlights' => $results,
            '_serialize' => ['highlights']
        ]);
    }
}
