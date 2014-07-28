<?php
class OmsFormWidget extends CWidget {
    public $model;
    public $form;
    public $typesOms = array();
    public $statusesOms = array();

    public function run() {

        // ������������ ���������, ������� ���� ������� ��� ������, ����� �� �� ������� ��������
        if (isset($_GET['newOmsNumber']))
            $this->model['policy'] = $_GET['newOmsNumber'];
        if (isset($_GET['newLastName']))
            $this->model['lastName'] = $_GET['newLastName'];
        if (isset($_GET['newFirstName']))
            $this->model['firstName'] = $_GET['newFirstName'];
        if (isset($_GET['newMiddleName']))
            $this->model['middleName'] = $_GET['newMiddleName'];
        if (isset($_GET['newBirthday']))
            $this->model['birthday'] = $_GET['newBirthday'];

        $this->typesOms = OmsType::getForSelect();
        $this->statusesOms = OmsStatus::getForSelect();

        // ��������� ��������� "��� ������ �� ���������"
        $omsTypeSetting = Setting::model()->find('name=\'defaultOmsType\'');

        if ($this->model['omsType']==NULL)
        {
            $this->model['omsType'] = $omsTypeSetting['value'];
        }

        $this->render('application.modules.reception.components.widgets.views.OmsFormWidget', array(
            'form' => $this->form,
            'model' => $this->model,
            'typesOms' => $this->typesOms,
            'statusesOms' => $this->statusesOms
        ));
    }
}

?>