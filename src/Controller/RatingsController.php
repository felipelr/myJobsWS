<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

/**
 * Ratings Controller
 *
 * @property \App\Model\Table\RatingsTable $Ratings
 *
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RatingsController extends AppController
{
    public function professional($professional_id)
    {
        $rate = [];
        $query = $this->Ratings->find('all')->where(['Ratings.professional_id = ' => $professional_id]);

        $rate = $query->select([
            'avg' => $query->func()->avg('rate'),
            'count' => $query->func()->count('*'),
        ])->first();

        $this->set([
            'rate' => $rate,
            '_serialize' => ['rate']
        ]);
    }

    public function rate()
    {
        $errorMessage = '';
        $rating = $this->Ratings->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
                if ($this->Ratings->save($rating)) {
                    //sucesso                
                    $errorMessage = '';
                } else {
                    //erro
                    $errorMessage = 'Não foi possível salvar a avaliação.' . json_encode($rating->getErrors());
                }
            } catch (Exception $ex) {
                $errorMessage = $ex->getMessage();
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'rating' => $rating,
                '_serialize' => ['rating']
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
