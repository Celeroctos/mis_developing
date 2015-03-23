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
        if (in_array($bundle, $this->names)) {
            return false;
        }
        $b = new $bundle();
        $this->names[] = $bundle;
        foreach ($b->dependencies as $dependency) {
            if (!in_array($dependency, $this->names)) {
                $this->register($dependency);
            }
        }
        $this->bundles[] = $b;
        return true;
    }

    /**
     * Prepare all bundles to render, it will load all scripts
     * and combine it together based on it's types
     */
    private function prepare() {
        $url = Yii::app()->getBaseUrl();
        if (strrpos($url, "/") != strlen($url)) {
            $url = $url . "/";
		} else if (empty($url)) {
			$url = "/";
		}
        if ($this->prepared != null) {
            return ;
        } else {
            $this->prepared = [
                "css" => [],
				"less" => [],
                "js" => [],
            ];
        }
        foreach ($this->bundles as $bundle) {
            foreach ($bundle->css as $name) {
                $this->prepared["css"][] = $this->registerStyle($url . $name);
            }
            foreach ($bundle->js as $name) {
                $this->prepared["js"][] = $this->registerJavaScript($url . $name);
            }
        }
    }

    /**
     * Render current registered bundle by writing to output buffer
     * @param string|array $collection - String with collection name, like (js, css, less) or
     *  array with collections names, like ([js, css, less]) to render
     * @throws CException
     * @return string - Rendered result
     */
    public function render($collection = null) {
        if ($this->prepared == null) {
            $this->prepare();
        }
        if ($collection != null) {
            if (is_array($collection)) {
                foreach ($collection as $c) {
                    $this->render($c);
                }
            } else if (is_string($collection)) {
                if (!isset($this->prepared[$collection])) {
                    throw new CException("Unresolved collection name \"{$collection}\"");
                }
                foreach ($this->prepared[$collection] as $s) {
                    print $s;
                }
            } else {
                throw new CException("Invalid collection type, found \"" . gettype($collection) . "\"");
            }
        } else {
            foreach ($this->prepared as $key => $ignore) {
                $this->render($key);
            }
        }
    }

    /**
     * Write to output stream javascript tag
     * @param string $path - Path to file
     * @return string
     */
    private function registerJavaScript($path) {
        return "<script type=\"text/javascript\" src=\"{$path}\"></script>\r\n";
    }

    /**
     * Write to output stream css tag
     * @param string $path - Path to file
     * @return string
     */
    private function registerStyle($path) {
		if (preg_match("/^.*\\.less$/", $path)) {
			return "<link href=\"{$path}\" rel=\"stylesheet\" type=\"text/less\" media=\"screen\"/>\r\n";
		} else {
			return "<link href=\"{$path}\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>\r\n";
		}
    }

	/**
	 * Get asset manager component
	 * @return CAssetManager - Component instance
	 */
	public static function getAssetManager() {
		return Yii::app()->{"assetManager"};
	}

	/**
	 * Get client script component
	 * @return CClientScript - Component instance
	 */
	public static function getClientScript() {
		return Yii::app()->{"clientScript"};
	}

    /**
     * @var array - Array with prepared to render components
     */
    private $prepared = null;

    /**
     * @var string[] - Array with names of all registered asset bundles
     */
    private $names = [];

    /**
     * @var AssetBundle[] - Array with registered asset bundles
     */
    private $bundles = [];
} 