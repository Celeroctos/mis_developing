<?php
class ArmdController extends Controller {
    public $layout = 'application.modules.hospital.views.layouts.armd';
    public function actionView() {
        $this->render('index', array(
        ));
    }
}

?>