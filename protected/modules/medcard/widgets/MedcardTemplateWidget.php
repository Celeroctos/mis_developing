<?php

class MedcardTemplateWidget extends Widget {

	/**
	 * @var MedcardTemplateEx model of template element to display
	 */
	public $template;

	public function init() {
        if (empty($this->template)) {
            throw new CException('Medcard template must not be empty');
        } else if (!$this->template instanceof CActiveRecord) {
            throw new CException('Medcard template must be an instance of ActiveRecord class');
        }
        if (!$categories = json_decode($this->template->{'categorie_ids'})) {
            throw new CException('Can\'t decode template categories');
        }
        $this->_categories = MedcardCategoryEx::model()->findAll('id in ('. implode(', ', $categories) .')');
	}

	public function run() {
        print Html::openTag('div', [
            'class' => 'medcard-template'
        ]);
        foreach ($this->_categories as $category) {
            $this->widget('MedcardCategoryWidget', [
                'category' => $category
            ]);
        }
        print Html::closeTag('div');
    }

    private $_categories;
}