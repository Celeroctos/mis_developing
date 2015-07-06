<?php

class MedcardCategoryWidget extends Widget {

	/**
	 * @var MedcardCategoryEx|array instance of category
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

    /**
     * @var MedcardCategoryWidget|MedcardTemplateWidget instance of parent category widget
     */
    public $parent = null;

    /**
     * @var int render mode, by default MODE_DEFAULT will be
     *  used
     */
    public $mode;

	public function init() {
        if (!$this->mode) {
            $this->mode = MedcardElementWidget::MODE_DEFAULT;
        }
		if (empty($this->category)) {
			throw new CException('Medcard category must not be empty');
		}
		if (empty($this->name)) {
			if (is_object($this->category) && $this->category->hasAttribute('categorie_name') || isset($this->category['categorie_name'])) {
				$this->name = $this->category['categorie_name'];
			} else {
				$this->name = $this->category['name'];
			}
		}
        if ($this->parent != null) {
            if ($this->parent instanceof MedcardTemplateWidget) {
                $this->_parent = $this->parent->template['id'];
            } else if ($this->parent instanceof MedcardCategoryWidget) {
                if (isset($this->parent->category['copy_id'])) {
                    $this->_parent = $this->parent->category['copy_id'];
                } else {
                    $this->_parent = $this->parent->category['id'];
                }
            } else {
                $this->_parent = null;
            }
        } else {
            $this->_parent = null;
        }
        $parent = $this->parent;
        while ($parent != null && !$parent instanceof MedcardTemplateWidget) {
            $parent = $parent->parent;
        }
        if ($parent != null) {
            $this->_greeting = $parent->greetingNumber;
        } else {
            $this->_greeting = null;
        }
        if ($this->category != null) {
            /* $this->mergeWithHistoryElement(); */
        }
        $this->prepareElements();
	}

    protected function mergeWithHistoryElement() {
        $category = MedcardElementPatientEx::model()->fetchHistoryByCategory(
            $this->category['id'], $this->getGreeting()
        );
        if ($category == null) {
            return ;
        }
        if (count($category) > 0) {
            $history = $category[0];
        } else {
            $history = $category;
        }
        $this->category['path'] = $history->{'path'};
        $this->_after = array_splice($category, 1);
    }

	public function run() {
		print Html::openTag('div', [
			'class' => 'accordion medcard-category',
            'id' => $this->createKey('accordion', 'a' . $this->_parent, '')
		]);
		print Html::openTag('div', [
			'class' => 'accordion-group'
		]);
		print Html::tag('div', [ 'class' => 'accordion-heading' ], $this->getLink());
        if ($this->category['is_wrapped'] == 0) {
            $collapse = ' collapse';
        } else {
            $collapse = ' in';
        }
		print Html::openTag('div', [
			'class' => 'accordion-body'.$collapse,
			'id' => $this->createKey('collapse', 'a' . $this->_parent, ''),
			'style' => 'height: auto;',
		]);
		print Html::openTag('div', [
			'class' => 'accordion-inner row no-margin'
		]);
		/* @var $e CActiveRecord */
		$this->openLine();
		foreach ($this->_elements as $e) {
            if (!$e instanceof MedcardCategoryEx && $e['type'] != -1) {
                if ($e['is_wrapped']) {
                    $this->closeLine(true);
                }
                $this->widget('MedcardElementWidget', [
                    'category' => $this,
                    'element' => $e,
                    'mode' => $this->mode
                ]);
            } else {
                $this->widget('MedcardCategoryWidget', [
                    'parent' => $this,
                    'category' => $e,
                    'mode' => $this->mode
                ]);
            }
		}
		$this->closeLine();
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
		print Html::closeTag('div');
	}

    protected function prepareElements() {
        if (isset($this->category['copy_id'])) {
            $id = $this->category['copy_id'];
        } else {
            $id = $this->category['id'];
        }
        $this->_elements = MedcardElementEx::model()->fetchWithGreeting($id);
        if ($this->_greeting != null) {
            $rows = MedcardElementPatientEx::model()->fetchHistoryByCategory(
                $this->category['id'], $this->_greeting
            );
            if (!empty($rows)) {
                $this->_elements = CMap::mergeArray($this->_elements, $rows);
            }
        } else {
            $rows = null;
        }
        if ($this->_greeting == null || empty($rows)) {
            $rows = MedcardCategoryEx::model()->findAllByAttributes([
                'parent_id' => $id
            ]);
            if (!empty($rows)) {
                $this->_elements = CMap::mergeArray($this->_elements, $rows);
            }
        }
        $filter = [];
        $fix = [];
        foreach ($this->_elements as $i => $element) {
            if (isset($fix[$element['path']])) {
                array_splice($this->_elements, $i, 1);
            } else {
                $fix[$element['path']] = true;
            }
            if (isset($element['element_id']) && $element['element_id'] > 0) {
                $filter[] = $element['element_id'];
            } else if (isset($element['id'])) {
                $filter[] = $element['id'];
            }
        }
        if (!empty($filter)) {
            $dependencies = MedcardElementDependencyEx::model()->fetchArray(
                'element_id in ('. implode(',', $filter) .')'
            );
            $this->_dependencies = [];
            foreach ($dependencies as $dependency) {
                if (!isset($this->_dependencies[$dependency['element_id']])) {
                    $this->_dependencies[$dependency['element_id']] = [ $dependency ];
                } else {
                    $this->_dependencies[$dependency['element_id']][] = $dependency;
                }
                $this->_dependent[$dependency['dep_element_id']] = $dependency['action'];
            }
        } else {
            $this->_dependencies = [];
        }
        usort($this->_elements, function($left, $right) {
            $val = -1;
            if ($right['path'] == $left['path']) {
                return 0;
            } else if (!$right = explode('.', $right['path'])) {
                return 0;
            }
            foreach (explode('.', $left['path']) as $i => $p) {
                if (!isset($right[$i])) {
                    return $val;
                }
                if ($right[$i] != $p) {
                    return $right[$i] < $p ? 1 : -1;
                } else {
                    $val = 0;
                }
            }
            return $val;
        });
    }

	protected function openLine() {
		print Html::openTag('div', [ 'class' => 'row no-margin form-group vertical-align medcard-wrapper' ]);
	}

	protected function closeLine($reopen = false) {
		print Html::closeTag('div');
        if ($reopen) {
            $this->openLine();
        }
	}

    protected function createAccordionKey() {
        if (!($card = Yii::app()->getRequest()->getQuery('cardid')) ||
            !($row = Yii::app()->getRequest()->getQuery('rowid'))
        ) {
            return '';
        }
        return $card.'|'.$row.'|'.$this->category['path'].'|'.$this->_parent.'|'.$this->category['id'];
    }

	protected function getLink() {
        if ($this->category['is_dynamic'] && !isset($this->category['copy_id']) && $this->mode == MedcardElementWidget::MODE_DEFAULT) {
            if (isset($this->category['copy_id'])) {
                $class = 'accordion-unclone-btn';
                $icon = 'glyphicon glyphicon-minus';
            } else {
                $class = 'accordion-clone-btn';
                $icon = 'glyphicon glyphicon-plus';
            }
            $button = Html::tag('button', [ 'class' => 'btn btn-default btn-sm '. $class, 'type' => 'button' ],
                Html::tag('span', [ 'class' => $icon ], '').
                Html::tag('span', [ 'class' => 'no-display pr-key' ], $this->createAccordionKey())
            );
        } else {
            $button = '';
        }
		return Html::link($this->getLabel(), $this->createKey('#collapse', 'a' .  $this->_parent, ''), [ 'data-parent' => $this->createKey('#accordion', 'a' . $this->_parent, ''),
			'data-toggle' => 'collapse', 'class' => 'accordion-toggle',
		]) . $button;
	}

	protected function getLabel() {
		return $this->name . Html::tag('div', [
			'class' => 'accordeonToggleAlt'
		], ' ('. $this->getTitle() .')');
	}

	protected function getTitle() {
        if (empty($this->_elements)) {
            return 'Пустая категория';
        } else if ($this->category['is_wrapped'] != 0) {
            return 'Свернуть';
        } else {
            return 'Развернуть';
        }
	}

	private function createKey($prefix, $letter, $glue = '|') {
		return $prefix.'_'.MedcardHtml::createHash($this->category, $this->prefix, $letter, $glue);
	}

    public function getDependencies($id) {
        if (isset($this->_dependencies[$id])) {
            return $this->_dependencies[$id];
        } else {
            return [];
        }
    }

    public function getDependent($id) {
        if (isset($this->_dependent[$id])) {
            return $this->_dependent[$id];
        } else {
            return [];
        }
    }

    public function getGreeting() {
        return $this->_greeting;
    }

    private $_dependent;
    private $_dependencies;
	private $_elements;
    private $_parent;
    private $_greeting;
    private $_after;
}