<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClientsServiceOrder Entity
 *
 * @property int $id
 * @property int|null $client_id
 * @property int|null $client_address_id
 * @property int|null $service_id
 * @property int|null $quantity
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\ClientsAddress $clients_address
 * @property \App\Model\Entity\Service $service
 */
class ClientsServiceOrder extends Entity
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
        'client_address_id' => true,
        'service_id' => true,
        'quantity' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
        'clients_address' => true,
        'service' => true
    ];
}
