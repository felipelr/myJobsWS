<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Professional Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $description
 * @property string|null $document
 * @property \Cake\I18n\FrozenDate|null $date_birth
 * @property string|null $photo
 * @property string|null $backImage
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $active
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Call[] $calls
 * @property \App\Model\Entity\Highlight[] $highlights
 * @property \App\Model\Entity\ProfessionalPhone[] $professional_phones
 * @property \App\Model\Entity\ProfessionalService[] $professional_services
 * @property \App\Model\Entity\ProfessionalsAddress[] $professionals_addresses
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
        'user_id' => true,
        'name' => true,
        'description' => true,
        'document' => true,
        'date_birth' => true,
        'photo' => true,
        'backImage' => true,
        'created' => true,
        'modified' => true,
        'active' => true,
        'user' => true,
        'calls' => true,
        'highlights' => true,
        'professional_phones' => true,
        'professional_services' => true,
        'professionals_addresses' => true,
        'ratings' => true
    ];
}
