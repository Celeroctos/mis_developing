<?php

class AssetBundleManager extends CComponent {

    private function __construct() {
        /* Locked */
    }

    public static function getManager() {
        if (self::$instance == null) {
            return (self::$instance = new AssetBundleManager());
        } else {
            return self::$instance;
        }
    }

    private static $instance = null;

    /**
     * Register new bundle, if bundle with that name already exists, then
     * that action will be ignored
     * @param string $bundle - Bundle class name, for example 'MainAsset'
     * @throws CException
     * @return bool - False if bundle hasn't been added
     */
    public function register($bundle) {
        if (!is_string($bundle)) {
            throw new CException("Bundle must be string name, found \"" . gettype($bundle) . "\"");
        }
        if (isset($this->bundles[$bundle])) {
            return false;
        }
        $b = ($this->bundles[$bundle] = new $bundle());
        $this->names[] = $bundle;
        foreach ($b->dependencies as $dependency) {
            if (!in_array($bundle, $this->names)) {
                $this->register($dependency);
            }
        }
        return true;
    }

    /**
     * Render current registered bundle by writing to output buffer
     * @return string - Rendered result
     */
    public function render() {
        $url = Yii::app()->getBaseUrl();
        if (strrpos($url, "/") != strlen($url)) {
            $url = $url . "/";
        }
        ob_start();
        foreach ($this->bundles as $bundle) {
            foreach ($bundle->js as $name) {
                $this->registerJavaScript($url . $name);
            }
            foreach ($bundle->css as $name) {
                $this->registerStyle($url . $name);
            }
            foreach ($bundle->less as $name) {
                $this->registerLess($url . $name);
            }
        }
        return ob_get_clean();
    }

    /**
     * Write to output stream javascript tag
     * @param string $path - Path to file
     */
    private function registerJavaScript($path) {
        print "<script type=\"text/javascript\" src=\"{$path}\"></script>\r\n";
    }

    /**
     * Write to output stream css tag
     * @param string $path - Path to file
     */
    private function registerStyle($path) {
        print "<link href=\"{$path}\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\r\n";
    }

    /**
     * Write to output stream less tag
     * @param string $path - Path to file
     */
    private function registerLess($path) {
        print "<link href=\"{$path}\" rel=\"stylesheet\" type=\"text/less\" media=\"screen\"/>\r\n";
    }

    /**
     * @var string[] - Array with names of all registered asset bundles
     */
    private $names = [];

    /**
     * @var AssetBundle[] - Array with registered asset bundles
     */
    private $bundles = [];
} 