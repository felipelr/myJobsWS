<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Call Entity
 *
 * @property int $id
 * @property int $client_id
 * @property int $professional_id
 * @property int $service_id
 * @property string $description
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\Service $service
 */
class Call extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'client_id' => true,
        'professional_id' => true,
        'service_id' => true,
        'description' => true,
        'status' => true, //1 -> aberto, 2-> finalizado, 3-> cancelado
        'created' => true,
        'modified' => true,
        'client' => true,
        'professional' => true,
        'service' => true,
        'confirm' => true, //0 -> Não precisa de Confirmacao, 1-> Precisa de Confirmacao do Profissional, 2-> Confirmado Pelo Profissional, 3-> Rejeitado pelo Profissional
    ];
}
