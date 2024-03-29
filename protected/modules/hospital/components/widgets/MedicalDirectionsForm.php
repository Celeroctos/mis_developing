<?php
class MedicalDirectionsForm extends CWidget {
    protected $data = array();
    public $currentOmsId = null;
    public $currentDoctorId = null;
    public $currentMedcard = null;
    
    // Список учреждений
    private function getEnterprisesList(){
        $enterprisesList = Enterprise::model()->findAll();
        foreach($enterprisesList as $enterprise) {
            $list[$enterprise['id']] = $enterprise['shortname'];
        }   
        return $list;
    } 
    public function run() {
        $this->data['model'] = Yii::createComponent('application.modules.hospital.models.forms.FormDirectionForPatientAdd');
        $this->data['wardsList'] = array();
        $this->data['currentOmsId'] = $this->currentOmsId;
        $this->data['currentDoctorId'] = $this->currentDoctorId;
        $this->data['currentMedcard'] = $this->currentMedcard;
		
        $wards = Ward::model()->getAll();
        foreach($wards as $key => $ward) {
            $this->data['wardsList'][(string)$ward['id']] = $ward['name'].', '.$ward['enterprise_name'];
        }
		$this->data['enterprisesList']=$this->getEnterprisesList();
        $this->render('application.modules.hospital.components.widgets.views.MedicalDirectionsForm', $this->data);
    }
}

?>