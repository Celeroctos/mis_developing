<?php

class MainAsset extends AssetBundle {

    public $js = [
        "js/main.js",
        "js/timecontrol.js",
        "js/datecontrol.js",
        "js/keyboard.js",
        "js/pagination.js",
        "js/keyboardcnf.js",
        "js/ajaxbutton.js",
    ];

    public $less = [
        "css/main.less"
    ];

    public $css = [
    ];

    public $dependencies = [
        "DefaultAsset"
    ];
} 