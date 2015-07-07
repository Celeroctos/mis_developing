<?php

class Laboratory_Modal_QueueGuide extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Информация о направлениях',
            'body' => $this->getWidget('Laboratory_Widget_QueueGuide'),
            'id' => 'laboratory-modal-queue-guide'
        ]);
    }
}