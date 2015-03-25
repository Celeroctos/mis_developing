<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 2015-02-18
 * Time: 11:21
 */

class Collapsible extends Widget {

    public $title = null;
    public $id = null;
    public $body = null;

    public function init() {
        if (empty($this->id)) {
			$this->id = UniqueGenerator::generate("collapsiable");
		}
		ob_start();
        $this->render(__CLASS__, [], false);
    }

    public function run() {
        print ob_get_clean().CHtml::closeTag("div").CHtml::closeTag("div");
    }
} 