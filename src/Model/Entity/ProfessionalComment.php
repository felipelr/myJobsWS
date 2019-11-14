<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProfessionalComment Entity
 *
 * @property int $id
 * @property int|null $professional_id
 * @property int|null $client_id
 * @property int|null $service_id
 * @property string|null $comment
 * @property int|null $rating
 * @property int|null $amount_ratings
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Professional $professional
 * @property \App\Model\Entity\Client $client
 */
class ProfessionalComment extends Entity
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
        'service_id' => true,
        'comment' => true,
        'rating' => true,
        'amount_ratings' => true,
        'created' => true,
        'modified' => true,
        'professional' => true,
        'client' => true
    ];
}
