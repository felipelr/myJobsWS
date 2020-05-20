<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Exception;

use App\Controller\AppController;

class InstagramController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['auth', 'unauthorized', 'delete']);
    }

    public function auth()
    {
        $code = $this->request->getQuery('code', ''); //autorizacao do instagram
        $state = $this->request->getQuery('state', ''); //id user table
        return $this->redirect("myjobsapp://myjobs?code=$code&state=$state");
    }

    public function unauthorized()
    {
        $this->render();
    }

    public function delete()
    {
        $this->render();
    }

    public function add()
    {
        $errorMessage = '';
        $story = null;
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $InstagramStories = TableRegistry::getTableLocator()->get('InstagramStories');

                $story = $InstagramStories->newEntity();
                $story->professional_id =  $this->request->getData('professional_id');
                $story->json = json_encode($this->request->getData('json'));

                $storyRow = $InstagramStories->find('all')
                    ->where(['InstagramStories.professional_id = ' => $story->professional_id])
                    ->first();

                if (isset($storyRow['id'])) {
                    //atualizar
                    $storyRow->json = $story->json;
                    if ($InstagramStories->save($storyRow)) {
                        //sucesso                
                        $errorMessage = '';
                    } else {
                        //erro
                        $errorMessage = 'Não foi possível salvar o story do instagram.';
                    }
                } else {
                    //salvar novo
                    if ($InstagramStories->save($story)) {
                        //sucesso                
                        $errorMessage = '';
                    } else {
                        //erro
                        $errorMessage = 'Não foi possível salvar o story do instagram.';
                    }
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

    public function view($professional_id)
    {
        $errorMessage = '';
        $story = null;
        try {
            $InstagramStories = TableRegistry::getTableLocator()->get('InstagramStories');

            $story = $InstagramStories->find('all')
                ->where(['InstagramStories.professional_id = ' => $professional_id])
                ->first();

            if (isset($story['id'])) {
                $story->json = json_decode($story->json);
            }
        } catch (Exception $ex) {
            $errorMessage = $ex->getMessage();
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
}
