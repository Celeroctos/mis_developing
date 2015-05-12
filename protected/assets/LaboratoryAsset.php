<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/laboratory/core.js"
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 