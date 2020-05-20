<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InstagramStory Entity
 *
 * @property int $id
 * @property int|null $professional_id
 * @property string|null $json
 *
 * @property \App\Model\Entity\Professional $professional
 */
class InstagramStory extends Entity
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
        'json' => true,
        'professional' => true
    ];
}
