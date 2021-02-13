<?php

namespace panix\ext\multipleinput;

use unclead\multipleinput\MultipleInput as BaseMultipleInput;

/**
 * Widget for rendering multiple input for an attribute of model.
 *
 */
class MultipleInput extends BaseMultipleInput
{

    const ICONS_SOURCE_CORE = 'icon';


    /**
     * @var array
     * --icon library classes mapped for various controls
     */
    public $iconMap = [
        self::ICONS_SOURCE_CORE => [
            'drag-handle' => 'icon-sort',
            'remove' => 'icon-delete',
            'add' => 'icon-add',
            'clone' => 'icon-copy',
        ],
    ];

    public $iconSource = self::ICONS_SOURCE_CORE;
    public $theme = self::THEME_BS;

    public function init()
    {
        if(!isset($this->addButtonOptions['class'])){
            $this->addButtonOptions['class'] = 'btn btn-sm btn-success';
        }
        if(!isset($this->removeButtonOptions['class'])){
            $this->removeButtonOptions['class'] = 'btn btn-sm btn-danger';
        }
        if(!isset($this->cloneButtonOptions['class'])){
            $this->cloneButtonOptions['class'] = 'btn btn-sm btn-primary';
        }
        parent::init();
    }

}
