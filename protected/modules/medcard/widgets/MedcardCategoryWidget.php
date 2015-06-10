<?php

class MedcardCategoryWidget extends Widget {

	/**
	 * @var MedcardCategoryEx|MedcardElementPatientEx instance of category
	 * 	class
	 */
	public $category;

	/**
	 * @var string displayed name of category, by default it takes
	 * 	name from category model
	 */
	public $name;

	/**
	 * @var string prefix for category identifier
	 *  generator
	 */
	public $prefix = '';

	public function init() {
		if (empty($this->category)) {
			throw new CException('Medcard category must not be empty');
		} else if (!$this->category instanceof CActiveRecord) {
			throw new CException('Medcard category must be an instance of ActiveRecord class');
		}
		if (empty($this->name)) {
			if ($this->category->hasAttribute('categorie_name')) {
				$this->name = $this->category->{'categorie_name'};
			} else {
				$this->name = $this->category->{'name'};
			}
		}
		if ($this->category->hasAttribute('categorie_name')) {
			$model = MedcardElementPatientEx::model();
		} else {
			$model = MedcardElementEx::model();
		}
		$this->_elements = $model->findAllByAttributes([
			'categorie_id' => $this->category->{'id'}
		]);
	}

	public function run() {
		print Html::openTag('div', [
			'class' => 'accordion'
		]);
		print Html::openTag('div', [
			'class' => 'accordion-group'
		]);
		print Html::tag('div', [ 'class' => 'accordion-heading' ], $this->getLink());
		print Html::openTag('div', [
			'class' => 'accordion-body in',
			'id' => $this->createKey('collapse', 'a'),
			'style' => 'height: auto;',
		]);
		print Html::openTag('div', [
			'class' => 'accordion-inner row no-margin'
		]);
		/* @var $e CActiveRecord */
		$this->openLine();
		foreach ($this->_elements as $e) {
			if ($e->{'is_wrapped'}) {
				$this->closeLine();
				$this->openLine();
			}
			if ($e->hasAttribute('element_id') && $e->{'element_id'} != -1 || !$e->hasAttribute('element_id')) {
				$this->widget('MedcardElementWidget', [ 'element' => $e ]);
			} else {
				print "CATEGORY";
			}
		}
		$this->closeLine();
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
	}

	protected function openLine() {
		print Html::openTag('div', [ 'class' => 'row form-group no-margin', 'style' => 'margin-bottom: 10px' ]);
	}

	protected function closeLine() {
		print Html::closeTag('div');
	}

	protected function getLink() {
		return Html::link($this->getLabel(), $this->createKey('#collapse', 'a'), [ 'data-parent' => $this->createKey('#accordion', 'a'),
			'data-toggle' => 'collapse', 'class' => 'accordion-toggle',
		]);
	}

	protected function getLabel() {
		return $this->name . Html::tag('div', [
			'class' => 'accordeonToggleAlt'
		], ' ('. $this->getTitle() .')');
	}

	protected function getTitle() {
		if (count($this->_elements) > 0) {
			if ($this->category->{'is_wrapped'}) {
				return 'Свернуть';
			} else {
				return 'Развернуть';
			}
		} else {
			return 'Пустая категория';
		}
	}

	private function createKey($prefix, $letter) {
		return $prefix.'_'.MedcardHtml::createHash($this->category, $this->prefix, $letter);
	}

	private $_elements;
}