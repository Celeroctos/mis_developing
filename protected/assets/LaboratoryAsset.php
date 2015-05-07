<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/laboratory/laboratory.js",
		"js/laboratory/printer.js",
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 