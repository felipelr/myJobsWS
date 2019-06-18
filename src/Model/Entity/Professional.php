<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Professional Entity
 *
 * @property int $id
 * @property int|null $city_id
 * @property int|null $subcategoria_id
 * @property int|null $user_id
 * @property string $name
 * @property int|null $description
 * @property \Cake\I18n\FrozenDate|null $date_birth
 * @property string|null $email
 * @property string|null $photo
 * @property string $street
 * @property string|null $street_number
 * @property string $neighborhood
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $active
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\ProfessionalPhone[] $professional_phones
 * @property \App\Model\Entity\Rating[] $ratings
 */
class Professional extends Entity
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
        'city_id' => true,
        'subcategoria_id' => true,
        'user_id' => true,
        'name' => true,
        'description' => true,
        'date_birth' => true,
        'email' => true,
        'photo' => true,
        'street' => true,
        'street_number' => true,
        'neighborhood' => true,
        'latitude' => true,
        'longitude' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'city' => true,
        'professional_phones' => true,
        'ratings' => true
    ];
}
