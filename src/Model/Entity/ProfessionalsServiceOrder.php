<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProfessionalsServiceOrder Entity
 *
 * @property int $id
 * @property int|null $professional_id
 * @property int|null $clients_service_orders_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\ClientsServiceOrder $clients_service_order
 */
class ProfessionalsServiceOrder extends Entity
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
        'professional_id' => true,
        'clients_service_orders_id' => true,
        'created' => true,
        'modified' => true,
        'professional' => true,
        'clients_service_order' => true,
    ];
}
