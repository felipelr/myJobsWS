<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * City Entity
 *
 * @property int $id
 * @property int|null $state_id
 * @property string|null $name
 * @property int|null $ibge_code
 *
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\ClientsAddres[] $clients_address
 * @property \App\Model\Entity\ProfessionalsAddres[] $professionals_address
 */
class City extends Entity
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
        'state_id' => true,
        'name' => true,
        'ibge_code' => true,
        'state' => true,
        'clients_address' => true,
        'professionals_address' => true
    ];
}
