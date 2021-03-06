<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

use function GuzzleHttp\json_encode;

/**
 * Stories Controller
 *
 * @property \App\Model\Table\StoriesTable $Stories
 *
 * @method \App\Model\Entity\Story[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoriesController extends AppController
{
    public function viewSingle($professional_id = 0)
    {
        $limit = $this->request->getQuery('limit', 10);
        $page = $this->request->getQuery('page', 1);
        $stories = [];
        $query = $this->Stories->find('all', [
            'order' => ['Stories.id' => 'DESC']
        ])
            ->where(['Stories.professional_id = ' => $professional_id])
            ->limit($limit)
            ->page($page);

        foreach ($query as $row) {
            $row['description'] = json_decode($row['description']);
            $stories[] = $row;
        }

        $this->set([
            'stories' => $stories,
            '_serialize' => ['stories']
        ]);
    }

    public function viewAll()
    {
        $limit = $this->request->getQuery('limit', 10);
        $page = $this->request->getQuery('page', 1);
        $stories = [];
        $query = $this->Stories->find('all', [
            'order' => ['Stories.id' => 'DESC']
        ])
            ->limit($limit)
            ->page($page);

        foreach ($query as $row) {
            $row['description'] = json_decode($row['description']);
            $stories[] = $row;
        }

        $this->set([
            'stories' => $stories,
            '_serialize' => ['stories']
        ]);
    }

    public function add()
    {
        $errorMessage = '';
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $image = $this->request->getData('image');
                $story = $this->Stories->newEntity();
                $story->professional_id =  $this->request->getData('professional_id');
                $story->description = json_encode($this->request->getData('description'));

                $base64 = $image;
                $time = round(microtime(true) * 10000);
                $output_file = WWW_ROOT . 'img' . DS . 'story-' . $time . '.jpeg';
                $dns_path = "http://67.205.160.187/ws" . DS . 'img' . DS . 'story-' . $time . '.jpeg';

                $ifp = fopen($output_file, 'wb');
                fwrite($ifp, base64_decode($base64));
                fclose($ifp);
                $story->photo = $dns_path;

                if ($this->Stories->save($story)) {
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar o story.';
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'story' => $story,
                '_serialize' => ['story']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function edit($id = null)
    {
    }

    public function delete($id = null)
    {
    }
}
