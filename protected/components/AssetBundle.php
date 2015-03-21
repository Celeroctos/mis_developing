<?php

abstract class AssetBundle extends CComponent {

    /**
     * Use that help method to register current bundle
     * @see AssetBundleManager::register
     * @throws CException
     */
    public static function register() {
        AssetBundleManager::getManager()->register(get_called_class());
    }

    /**
     * @var array - Array with javascript scripts (relative path, like 'js/main.js'), be
     *  careful with dependencies
     * @see AssetBundle::dependecies
     */
    public $js = [];

    /**
     * @var array - Array with style scripts, be
     *  careful with dependencies
     * @see AssetBundle::dependecies
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
} 