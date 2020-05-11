<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rating Entity
 *
 * @property int $id
 * @property int $client_id
 * @property int $professional_id
 * @property int $call_id
 * @property int $rate
 * @property string $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\Call $call
 */
class Rating extends Entity
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
        'call_id' => true,
        'rate' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'client' => true,
        'professional' => true,
        'call' => true,
    ];
}
