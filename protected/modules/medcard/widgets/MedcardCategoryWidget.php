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
        if ($this->category instanceof MedcardCategorie) {
            $this->_elements = CMap::mergeArray($this->_elements, MedcardCategoryEx::model()->findAllByAttributes([
                'parent_id' => $this->category->{'id'}
            ]));
        }
        $this->prepareElements();
	}

	public function run() {
		print Html::openTag('div', [
			'class' => 'accordion medcard-category'
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
            if (!$e instanceof MedcardCategorie) {
                if ($e->{'is_wrapped'}) {
                    $this->closeLine(true);
                }
                $this->widget('MedcardElementWidget', [ 'element' => $e ]);
            } else {
                $this->widget('MedcardCategoryWidget', [ 'category' => $e ]);
            }
		}
		$this->closeLine();
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
	}

    protected function prepareElements() {
        usort($this->_elements, function($left, $right) {
            return strcmp($left->{'path'}, $right->{'path'});
        });
    }

	protected function openLine() {
		print Html::openTag('div', [ 'class' => 'row form-group no-margin vertical-align medcard-wrapper', 'style' => 'margin-bottom: 10px' ]);
	}

	protected function closeLine($reopen = false) {
		print Html::closeTag('div');
        if ($reopen) {
            print Html::tag('hr', [
                'class' => 'medcard-hr'
            ]);
            $this->openLine();
        }
	}

	protected function getLink() {
        if ($this->category->{'is_dynamic'}) {
            $button = Html::tag('button', [ 'class' => 'btn btn-default btn-sm accordion-clone-btn' ],
                Html::tag('span', [ 'class' => 'glyphicon glyphicon-plus' ], '')
            );
        } else {
            $button = '';
        }
		return Html::link($this->getLabel(), $this->createKey('#collapse', 'a'), [ 'data-parent' => $this->createKey('#accordion', 'a'),
			'data-toggle' => 'collapse', 'class' => 'accordion-toggle',
		]) . $button;
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