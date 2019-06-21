<?php

namespace panix\ext\multipleinput\assets;

use yii\web\AssetBundle;

/**
 * Class MultipleInputAsset
 * @package panix\ext\multipleinput\assets
 */
class MultipleInputSortableAsset extends AssetBundle
{
    public $depends = [
        'panix\ext\multipleinput\assets\MultipleInputAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/src/';

        $this->js = [
            YII_DEBUG ? 'js/jquery-sortable.js' : 'js/jquery-sortable.min.js'
        ];

        $this->css = [
            YII_DEBUG ? 'css/sorting.css' : 'css/sorting.min.css'
        ];

        parent::init();
    }
} 