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
        print Html::openTag('form', [
            'class' => 'medcard-template form-horizontal col-xs-12 template-edit-form',
            'role' => 'form',
            'id' => UniqueGenerator::generate('form'),
            'action' => Yii::app()->createUrl('doctors/shedule/patientedit'),
            'method' => 'post',
        ]);
        foreach ($this->_categories as $category) {
            $this->widget('MedcardCategoryWidget', [
                'category' => $category
            ]);
        }
        print Html::tag('div', [ 'class' => 'submitEditPatient' ], Html::ajaxSubmitButton('', Yii::app()->createUrl('doctors/shedule/patientedit'), [], [
            'class' => 'templateContentSave'
        ]));
        print Html::closeTag('form');
    }

    private $_categories;
}