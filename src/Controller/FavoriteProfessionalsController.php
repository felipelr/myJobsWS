<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * FavoriteProfessionals Controller
 *
 * @property \App\Model\Table\FavoriteProfessionalsTable $FavoriteProfessionals
 *
 * @method \App\Model\Entity\FavoriteProfessional[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavoriteProfessionalsController extends AppController
{
    public function addFavorite()
    {
        $errorMessage = '';
        $favoriteProfessional = $this->FavoriteProfessionals->newEntity();
        if ($this->request->is('post')) {
            $favoriteProfessional = $this->FavoriteProfessionals->patchEntity($favoriteProfessional, $this->request->getData());
            if ($this->FavoriteProfessionals->save($favoriteProfessional)) {
                $Professionals = TableRegistry::getTableLocator()->get('Professionals');
                $ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices');

                $favoriteProfessional['professional'] = $Professionals->get($favoriteProfessional->professional_id);

                $query = $ProfessionalServices->find('all')
                    ->where([
                        'ProfessionalServices.professional_id = ' => $favoriteProfessional->professional_id,
                    ]);
                $rateProfessional = $query->select([
                    'sumRate' => $query->func()->sum('rating'),
                    'sumAmount' => $query->func()->sum('amount_ratings'),
                    'count' => $query->func()->count('*'),
                ])->first();

                $favoriteProfessional['professional']['rating'] = round($rateProfessional['sumRate'] / ($rateProfessional['count'] == 0 ? 1 : $rateProfessional['count']), 1);
                $favoriteProfessional['professional']['amount_ratings'] = $rateProfessional['sumAmount'];

                $errorMessage = '';
            } else {
                //erro
                $errorMessage = 'Não foi possível registrar o favorito.' . json_encode($favoriteProfessional->getErrors());
            }
        }

        if ($errorMessage == '') {
            $this->set([
                'favorite' => $favoriteProfessional,
                '_serialize' => ['favorite']
            ]);
        } else {
            $this->set([
                'error' => true,
                'errorMessage' => $errorMessage,
                '_serialize' => ['error', 'errorMessage']
            ]);
        }
    }

    public function user($user_id = null)
    {
        $favorities = [];
        $favorities = $this->FavoriteProfessionals->find('all')
            ->where([
                'FavoriteProfessionals.user_id = ' => $user_id,
            ])
            ->contain(['Professionals'])
            ->all();

        $ProfessionalServices = TableRegistry::getTableLocator()->get('ProfessionalServices');
        foreach ($favorities as $item) {
            $query = $ProfessionalServices->find('all')
                ->where([
                    'ProfessionalServices.professional_id = ' => $item->professional_id,
                ]);
            $rateProfessional = $query->select([
                'sumRate' => $query->func()->sum('rating'),
                'sumAmount' => $query->func()->sum('amount_ratings'),
                'count' => $query->func()->count('*'),
            ])->first();

            $item['professional']['rating'] = round($rateProfessional['sumRate'] / ($rateProfessional['count'] == 0 ? 1 : $rateProfessional['count']), 1);
            $item['professional']['amount_ratings'] = $rateProfessional['sumAmount'];
        }

        $this->set([
            'favorities' => $favorities,
            '_serialize' => ['favorities']
        ]);
    }

    public function removeFavorite($id)
    {
        $errorMessage = '';
        $favorite = $this->FavoriteProfessionals->get($id);
        if ($this->FavoriteProfessionals->delete($favorite)) {
            $errorMessage = '';
        } else {
            $errorMessage = 'Não foi possível excluir o favorito.' . json_encode($favorite->getErrors());
        }

        if ($errorMessage == '') {
            $this->set([
                'favorite' => $favorite,
                '_serialize' => ['favorite']
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
