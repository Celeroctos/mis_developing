<?php

class Laboratory_Modal_AboutMedcard extends Widget {

    public function run() {
        $this->widget('Modal', [
            'title' => 'Медицинская карта',
            'body' => '<h4 class="text-center no-margin">Медкарта не выбрана</h4>',
            'buttons' => [
            ],
            'id' => 'laboratory-modal-about-medcard'
        ]);
    }
}