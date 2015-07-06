<?php

class MedcardTemplateWidget extends Widget {

	/**
	 * @var MedcardTemplateEx|array model of template element to display
	 */
	public $template;

    /**
     * @var int greeting identification number
     */
    public $greetingNumber;

    /**
     * @var string card identification number
     */
    public $cardNumber;

    /**
     * @var string url to save template
     */
    public $saveUrl = 'doctors/shedule/patientedit';

    /**
     * @var int render mode, by default MODE_DEFAULT will be
     *  used
     */
    public $mode;

	public function init() {
        if (!$this->mode) {
            $this->mode = MedcardElementWidget::MODE_DEFAULT;
        }
        if (empty($this->template)) {
            throw new CException('Medcard template must not be empty');
        } else if (is_scalar($this->template) && !$this->template = MedcardTemplateEx::model()->findByPk($this->template)) {
            throw new CException('Unresolved template identification number');
        }
        if (!$this->greetingNumber && !($this->greetingNumber = Yii::app()->getRequest()->getQuery('rowid'))) {
            throw new CException('Medcard template widget requires greeting number');
        }
        if (!$this->cardNumber && !($this->cardNumber = Yii::app()->getRequest()->getQuery('cardid'))) {
            throw new CException('Medcard template widget requires card number');
        }
        if ($this->template instanceof MedcardTemplateEx) {
            $this->_categories = $this->template->fetchCategories();
        } else {
            $this->_categories = MedcardTemplateEx::model()->fetchCategories($this->template['id']);
        }
	}

	public function run() {
        print Html::openTag('form', [
            'class' => 'medcard-template form-horizontal col-xs-12 template-edit-form no-padding',
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
                'category' => $category,
                'parent' => $this,
                'mode' => $this->mode,
            ]);
        }
        print Html::tag('div', [ 'class' => 'submitEditPatient' ], Html::ajaxSubmitButton('', Yii::app()->createUrl($this->saveUrl), [], [
            'class' => 'templateContentSave'
        ]));
        print Html::closeTag('form');
    }

    private $_categories;
}