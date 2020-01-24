<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChatMessage Entity
 *
 * @property int $id
 * @property int|null $professional_id
 * @property int|null $client_id
 * @property \Cake\I18n\FrozenTime|null $date_time
 * @property string|null $message
 * @property string|null $msg_from
 *
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\Client $client
 */
class ChatMessage extends Entity
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
        'client_id' => true,
        'date_time' => true,
        'message' => true,
        'msg_from' => true,
        'professional' => true,
        'client' => true
    ];
}
