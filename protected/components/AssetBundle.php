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
     * @var array - Array with dependencies bundles (class name), if you want to register
     *  asset bundle in module, for example 'init' method, then done't forget to set
     *  dependency to parent bundle, cuz else it will be registered in index layout
     *  and scripts will be added after yours, so it might crash your event handlers
     *  or overwrite your styles
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