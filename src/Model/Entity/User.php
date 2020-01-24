<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property int $role_id
 * @property string $email
 * @property string $password
 * @property string $facebook_token
 * @property string $google_token
 * @property string $fcm_token
 * @property bool|null $active
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Facebook $facebook
 * @property \App\Model\Entity\Google $google
 * @property \App\Model\Entity\Call[] $calls
 * @property \App\Model\Entity\Client[] $clients
 * @property \App\Model\Entity\Professional[] $professionals
 * @property \App\Model\Entity\Rating[] $ratings
 */
class User extends Entity
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
        'role_id' => true,
        'email' => true,
        'password' => true,
        'facebook_token' => true,
        'google_token' => true,
        'fcm_token' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'facebook' => true,
        'google' => true,
        'calls' => true,
        'clients' => true,
        'professionals' => true,
        'ratings' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
