<?php

class MedcardMenuWidget extends Widget {

    /**
     * @var array with templates, if empty then personal templates
     *  will be used
     */
    public $templates;

    /**
     * @var int personal identification number, if empty then current
     *  user's personal will be used
     */
    public $personal;

    public function init() {
        if (empty($this->personal)) {
            $this->personal = Yii::app()->{'user'}->getState('medworkerId');
        }
        if (empty($this->templates)) {
            $this->templates = $this->getTemplates($this->personal);
        }
        usort($this->templates, function($left, $right) {
            return $left->{'index'} - $right->{'index'};
        });
        $this->_menu = [];
        $js = <<< JS
        var c = $(this).parents(".medcard-menu:eq(0)");
        c.find("[id^=tab-]").hide();
        c.find("#" + $(this).attr("data-tab")).show();
        c.find("> ul > li").removeClass("active");
        $(this).parent("li").addClass("active");
JS;
        $js = preg_replace('/[\r\n\t ]+/', ' ', $js);
        foreach ($this->templates as $template) {
            $this->_menu[$template->{'id'}] = [ 'data-tab' => UniqueGenerator::generate('tab'),
                'label' => $template->{'name'}, 'onclick' => $js,
            ];
        }
    }

    public function run() {
        if (!empty($this->templates)) {
            $active = $this->templates[0]->{'id'};
        } else {
            $active = null;
        }
        print Html::openTag('div', [
            'class' => 'medcard-menu'
        ]);
        $this->widget('TabMenu', [
            'items' => $this->_menu,
            'active' => $active,
        ]);
        print Html::tag('br');
        foreach ($this->templates as $template) {
            if ($template->{'id'} == $active) {
                $style = 'display: block;';
            } else {
                $style = 'display: none;';
            }
            print Html::openTag('div', [
                'id' => $this->_menu[$template->{'id'}]['data-tab'],
                'style' => $style,
            ]);
            $this->widget('MedcardTemplateWidget', [
                'template' => $template
            ]);
            print Html::closeTag('div');
        }
        print Html::tag('br');
        $this->widget('TabMenu', [
            'items' => $this->_menu,
            'active' => $active,
        ]);
        print Html::closeTag('div');
    }

    private function getTemplates($id) {
        $templates = Medpersonal_templates::model()->findAllByAttributes([
            'id_medpersonal' => $id
        ]);
        $templates = array_values(CHtml::listData($templates, 'id', 'id_template'));
        return MedcardTemplateEx::model()->findAll('id in ('. implode(',', $templates) .')');
    }

    private $_menu;
}