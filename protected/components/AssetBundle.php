<?php

abstract class AssetBundle extends CComponent {

    /**
     * @var array - Array with javascript scripts (relative path, like 'js/main.js')
     */
    public $js = [];

    /**
     * @var array - Array with less styles
     */
    public $less = [];

    /**
     * @var array - Array with style scripts
     */
    public $css = [];

    /**
     * @var array - Array with dependencies bundles (class name)
     */
    public $dependencies = [];

    /**
     * Use that help method to register current bundle
     * @throws CException - (@see AssetBundleManager::register)
     */
    public final static function register() {
        AssetBundleManager::getManager()->register(get_called_class());
    }
} 