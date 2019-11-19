<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Stories Controller
 *
 * @property \App\Model\Table\StoriesTable $Stories
 *
 * @method \App\Model\Entity\Story[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoriesController extends AppController
{
    public function view($professional_id = null)
    {
        $stories = [];
        $query = $this->Stories->find('all', [
            'order' => ['Stories.id' => 'DESC']
        ])
            ->where(['Stories.professional_id = ' => $professional_id]);

        foreach ($query as $row) {
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
                $story->description = $this->request->getData('description');

                $base64 = $image;
                $time = round(microtime(true) * 10000);
                $output_file = WWW_ROOT . 'img' . DS . 'story-' . $time . '.jpeg';
                $dns_path = "http://myjobs.servicos.ws" . DS . 'img' . DS . 'story-' . $time . '.jpeg';

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
    { }

    public function delete($id = null)
    { }
}
