<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProfessionalPhone Entity
 *
 * @property int|null $phone
 * @property int|null $professional_id
 * @property string|null $phone_string
 * @property string|null $description
 *
 * @property \App\Model\Entity\Professional $professional
 */
class ProfessionalPhone extends Entity
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
        'phone' => true,
        'professional_id' => true,
        'phone_string' => true,
        'description' => true,
        'professional' => true
    ];
}
