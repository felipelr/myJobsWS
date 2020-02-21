<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $document
 * @property \Cake\I18n\FrozenDate $date_birth
 * @property string $gender
 * @property string $phone
 * @property string $photo
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property bool $active
 * @property int $websocket
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ClientsAddress[] $clients_addresses
 */
class Client extends Entity
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
        'name' => true,
        'document' => true,
        'date_birth' => true,
        'gender' => true,
        'phone' => true,
        'photo' => true,
        'created' => true,
        'modified' => true,
        'active' => true,
        'websocket' => true,
        'user' => true,
        'clients_addresses' => true
    ];
}
