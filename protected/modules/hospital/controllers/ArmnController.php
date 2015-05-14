<?php
class ArmnController extends Controller {
    public $layout = 'application.modules.hospital.views.layouts.armn';
    public function actionView() {
        $this->render('index', array(
        ));
    }
}

?>