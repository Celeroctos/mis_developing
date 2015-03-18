<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/laboratory/core.js",
        "js/laboratory/laboratory.js",
        "js/laboratory/form.js",
        "js/laboratory/multiple.js",
        "js/laboratory/message.js",
        "js/laboratory/address.js",
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 