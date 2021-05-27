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
                        //salvar imagens 
                        $json = json_decode($story->json, true);
                        foreach ($json as $item) {
                            $output_file_temp = WWW_ROOT . 'img' . DS . 'story-temp.jpeg';
                            $output_file = WWW_ROOT . 'img' . DS . 'instagram-' . $item['id'] . '.jpeg';
                            $url = $item['media_url'];
                            $altura = 600;
                            $largura = 600;

                            file_put_contents($output_file_temp, file_get_contents($url));
                            $image_temp = imagecreatefromjpeg($output_file_temp);
                            $height_temp = imagesy($image_temp);
                            $width_temp = imagesx($image_temp);

                            if ($height_temp > $width_temp) {
                                $ratio = $height_temp / $width_temp;
                                $diff = $height_temp - $altura;
                                $largura = $width_temp - ($diff / $ratio);
                            } else if ($width_temp > $height_temp) {
                                $ratio = $width_temp / $height_temp;
                                $diff = $width_temp - $largura;
                                $altura = $height_temp - ($diff / $ratio);
                            }

                            $image_resized = imagecreatetruecolor($largura, $altura);

                            imagecopyresampled($image_resized, $image_temp, 0, 0, 0, 0, $largura, $altura, $width_temp, $height_temp);
                            imagejpeg($image_resized, $output_file);
                            imagedestroy($image_temp);
                            imagedestroy($image_resized);
                            unlink($output_file_temp);
                        }

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
