<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/laboratory/main.js"
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 