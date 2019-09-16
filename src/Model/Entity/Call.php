<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Call Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $professional_id
 * @property int $service_id
 * @property string $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
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
        'user_id' => true,
        'professional_id' => true,
        'service_id' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'professional' => true,
        'service' => true
    ];
}
