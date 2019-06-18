<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Highlight Entity
 *
 * @property int|null $professional_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $finish
 * @property int|null $position
 *
 * @property \App\Model\Entity\Professional $professional
 */
class Highlight extends Entity
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
        'created' => true,
        'finish' => true,
        'position' => true,
        'professional' => true
    ];
}
