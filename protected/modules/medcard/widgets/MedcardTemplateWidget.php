<?php

class MedcardTemplateWidget extends Widget {

	/**
	 * @var MedcardTemplateEx model of template element to display
	 */
	public $template;

    public $greetingNumber;
    public $cardNumber;

    public $saveUrl = 'doctors/shedule/patientedit';

	public function init() {
        if (empty($this->template)) {
            throw new CException('Medcard template must not be empty');
        } else if (!$this->template instanceof CActiveRecord) {
            throw new CException('Medcard template must be an instance of ActiveRecord class');
        }
        if (!$this->greetingNumber && !($this->greetingNumber = Yii::app()->getRequest()->getQuery('rowid'))) {
            throw new CException('Medcard template widget requires greeting number');
        }
        if (!$this->cardNumber && !($this->cardNumber = Yii::app()->getRequest()->getQuery('cardid'))) {
            throw new CException('Medcard template widget requires card number');
        }
        $this->_categories = $this->template->fetchCategories();
	}

	public function run() {
        print Html::openTag('form', [
            'class' => 'medcard-template form-horizontal col-xs-12 template-edit-form',
            'role' => 'form',
            'id' => UniqueGenerator::generate('form'),
            'action' => Yii::app()->createUrl($this->saveUrl),
            'method' => 'post',
        ]);
        print Html::hiddenField('FormTemplateDefault[medcardId]', $this->cardNumber, [
            'id' => 'medcardId', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[greetingId]', $this->greetingNumber, [
            'id' => 'greetingId', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[templateName]', $this->template['name'], [
            'id' => 'templateName', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[templateId]', $this->template['id'], [
            'id' => 'templateId', 'class' => 'from-control',
        ]);
        foreach ($this->_categories as $category) {
            $this->widget('MedcardCategoryWidget', [
                'category' => $category
            ]);
        }
        print Html::tag('div', [ 'class' => 'submitEditPatient' ], Html::ajaxSubmitButton('', Yii::app()->createUrl($this->saveUrl), [], [
            'class' => 'templateContentSave'
        ]));
        print Html::closeTag('form');
    }

    private $_categories;
}