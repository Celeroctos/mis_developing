<?php
class MedcardFormWidget extends CWidget {
    public $model;
    public $form;
    public $privilegesList;
    public $showEditIcon = false;
    public $template = null;
    public $documentsTypes = array();

    public function run() {
        if($this->template != null) {

            // ������������ ���������, ������� ���� ������� ��� ������, ����� �� ����� �� �������
            if (isset($_GET['newSerie']))
                $this->model['serie'] = $_GET['newSerie'];
            if (isset($_GET['newDocnumber']))
                $this->model['docnumber'] = $_GET['newDocnumber'];
            if (isset($_GET['newSnils']))
                $this->model['snils'] = $_GET['newSnils'];

            // ����������� �������� ����� ����������

            $this->documentsTypes = Doctype::getForSelect();

            $this->render($this->template, array(
                'form' => $this->form,
                'model' => $this->model,
                'privilegesList' => $this->privilegesList,
                'showEditIcon' => $this->showEditIcon,
                'docTypesList' => $this->documentsTypes
            ));
        } else {

        }
    }
}

?>