<?php

class LaboratoryAsset extends AssetBundle {

    public $js = [
        "js/laboratory/laboratory.js",
		"js/laboratory/medcard.js",
		"js/laboratory/treatment.js",
    ];

    public $css = [
        "css/laboratory.css",
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 