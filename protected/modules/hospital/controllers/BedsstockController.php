<?php 
class BedsstockController extends Controller {
	public $layout = 'application.modules.hospital.views.layouts.bedsstock';
	public function actionView() {
		$this->render('index', array(
        ));
	}
}

?>