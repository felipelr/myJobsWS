<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClientsAddress Entity
 *
 * @property int $id
 * @property int $client_id
 * @property int $city_id
 * @property string $street
 * @property string $street_number
 * @property string $neighborhood
 * @property string $latitude
 * @property string $longitude
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\City $city
 */
class ClientsAddress extends Entity
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
        'city_id' => true,
        'street' => true,
        'street_number' => true,
        'neighborhood' => true,
        'latitude' => true,
        'longitude' => true,
        'client' => true,
        'city' => true
    ];
}
