<?php

class Laboratory_Widget_AnalyzerHelper extends Widget {

    public $analyzers;

    public function init() {
        if (empty($this->analyzers)) {
            $this->analyzers = $this->getAnalyzerTabs();
        }
    }

    public function run() {
        return $this->render('Laboratory_Widget_AnalyzerHelper', [
            'analyzers' => $this->analyzers,
        ]);
    }

    private function getAnalyzerTabs() {
        $analyzers = Analyzer::model()->listTabs();
        if (empty($analyzers)) {
            return [ 'empty' => [
                'label' => 'Нет доступных анализаторов',
                'disabled' => 'true'
            ] ];
        } else {
            return $analyzers;
        }
    }
}