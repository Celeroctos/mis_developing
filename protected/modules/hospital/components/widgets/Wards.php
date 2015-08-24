<?php
class Wards extends CWidget {
    private $data = array();
    public $showSettingsIcon = false;
    public $demo_free=2;
    public function run() {
        $this->data['show_settings_icon'] = $this->showSettingsIcon;
        $this->render('application.modules.hospital.components.widgets.views.Wards', $this->data);
    }
}

?>