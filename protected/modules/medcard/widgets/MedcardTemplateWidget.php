<?php

class MedcardTemplateWidget extends Widget {

	/**
	 * @var MedcardTemplateEx model of template element to display
	 */
	public $template;

    public $greetingNumber;
    public $cardNumber;

	public function init() {
        if (empty($this->template)) {
            throw new CException('Medcard template must not be empty');
        } else if (!$this->template instanceof CActiveRecord) {
            throw new CException('Medcard template must be an instance of ActiveRecord class');
        }
        if (!$categories = json_decode($this->template->{'categorie_ids'})) {
            throw new CException('Can\'t decode template categories');
        }
        if (!$this->greetingNumber) {
            $this->greetingNumber = Yii::app()->getRequest()->getQuery('rowid');
        }
        if (!$this->cardNumber) {
            $this->cardNumber = Yii::app()->getRequest()->getQuery('cardid');
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
        print Html::hiddenField('FormTemplateDefault[medcardId]', $this->cardNumber, [
            'id' => 'medcardId', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[greetingId]', $this->greetingNumber, [
            'id' => 'greetingId', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[templateName]', $this->template->{'name'}, [
            'id' => 'templateName', 'class' => 'from-control',
        ]);
        print Html::hiddenField('FormTemplateDefault[templateId]', $this->template->{'id'}, [
            'id' => 'templateId', 'class' => 'from-control',
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