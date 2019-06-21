<?php

namespace panix\ext\multipleinput\assets;

use yii\web\AssetBundle;

/**
 * Class MultipleInputAsset
 * @package panix\ext\multipleinput\assets
 */
class MultipleInputAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function __construct($config = [])
    {
        $config = array_merge([
            'sourcePath' => __DIR__ . '/src/',
            'js' => [
                YII_DEBUG ? 'js/jquery.multipleInput.js' : 'js/jquery.multipleInput.min.js'
            ],
            'css' => [
                YII_DEBUG ? 'css/multiple-input.css' : 'css/multiple-input.min.css'
            ],
        ], $config);

        parent::__construct($config);
    }


} 
