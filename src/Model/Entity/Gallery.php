<?php
namespace DejwCake\Media\Model\Entity;

use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\ORM\Behavior\Translate\TranslateTrait;

/**
 * Gallery Entity
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property string $enabled_in_locales
 * @property int $sort
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $deleted
 */
class Gallery extends Entity
{
    use TranslateTrait;
    use HasMediaTrait;

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
        '*' => true,
        'id' => false
    ];

    /**
     * @return string
     */
    protected function _getEnabledInLocalesText()
    {
        $enabledInLocalesArray = '';
        if(empty($this->_properties['enabled_in_locales'])) {
            return $enabledInLocalesArray;
        }
        if(!is_array($this->_properties['enabled_in_locales'])) {
            return $this->_properties['enabled_in_locales'];
        }
        foreach (Configure::read('App.supportedLanguages') as $language => $languageSettings) {
            if(in_array($languageSettings['locale'], $this->_properties['enabled_in_locales'])) {
                $enabledInLocalesArray[] = $languageSettings['title'];
            }
        }
        return implode(', ', $enabledInLocalesArray);
    }
}
