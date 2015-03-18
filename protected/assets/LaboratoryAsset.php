<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/core.js",
        "js/laboratory.js",
        "js/form.js",
        "js/multiple.js",
        "js/message.js",
        "js/address.js",
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 