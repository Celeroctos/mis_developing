<?php
class PrintController extends Controller {
    public $layout = 'print';
    public $responseData = array();

    public function actionGetForDayFish()
    {

        // ������� ����� � �������� "����� �� ����"
        $this->render('workForDayPrint', array(
        ));
    }

}