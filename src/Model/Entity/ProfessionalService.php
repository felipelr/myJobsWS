<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProfessionalService Entity
 *
 * @property int $id
 * @property int|null $professional_id
 * @property int|null $service_id
 * @property int|null $rating
 * @property int|null $amount_ratings
 * @property int|null $active
 *
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\Service $service
 */
class ProfessionalService extends Entity
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
        'service_id' => true,
        'rating' => true,
        'amount_ratings' => true,
        'active' => true,
        'professional' => true,
        'service' => true
    ];
}
