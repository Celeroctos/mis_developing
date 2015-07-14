<?php

class Panel extends Widget {

    public $title = null;
    public $id = null;
    public $body = null;
    public $collapse = null;

    public function init() {
        parent::init();
        if ($this->body instanceof Widget && !empty($this->body)) {
            $this->body = $this->body->call();
        }
		if (empty($this->id)) {
			$this->id = Yii::app()->getSecurityManager()->generateRandomString(5);
		}
		ob_start();
        $this->render(__CLASS__, [], false);
    }

    public function run() {
        print ob_get_clean().CHtml::closeTag("div").CHtml::closeTag("div");
    }
} 