<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property int|null $subcategory_id
 * @property string|null $title
 * @property string|null $description
 * @property bool|null $active
 *
 * @property \App\Model\Entity\Subcategory $subcategory
 * @property \App\Model\Entity\ProfessionalService[] $professional_services
 */
class Service extends Entity
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
        'subcategory_id' => true,
        'title' => true,
        'description' => true,
        'active' => true,
        'subcategory' => true,
        'professional_services' => true
    ];
}
