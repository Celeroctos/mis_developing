<?php
class MedcardHistoryDiagnosisWidget extends Widget {
	public $greetingId;
	protected $data = array();
	public function run() {
		$greeting = SheduleByDay::model()->findByPk($this->greetingId);

		//Основной диагноз по МКБ-10
		$primary=$this->getMkb10Descriptions(PatientDiagnosis::model()->findDiagnosis($this->greetingId,0));

		//Сопутствующий диагноз по МКБ-10
		$secondary=$this->getMkb10Descriptions(PatientDiagnosis::model()->findDiagnosis($this->greetingId,1));
		//var_dump($secondary);

		//клинический основной
		$clinPrimary=$this->getClinicalDescriptions(ClinicalPatientDiagnosis::model()->findDiagnosis($this->greetingId,0));
		//var_dump($clinPrimary);
		//клинический сопутствующий
		$clinSecondary=$this->getClinicalDescriptions(ClinicalPatientDiagnosis::model()->findDiagnosis($this->greetingId,1));
		///var_dump($clinSecondary);

		//осложнения основного
		$complicatingDiag=$this->getMkb10Descriptions(PatientDiagnosis::model()->findDiagnosis($this->greetingId,2));
		//var_dump($complicatingDiag);
		$this->data=[
			'primary'=>$primary,
			'secondary'=>$secondary,
			'clinPrimary'=>$clinPrimary,
			'clinSecondary'=>$clinSecondary,
			'complicatingDiag'=>$complicatingDiag,
			'greeting'=>$greeting
		];
		$this->render('application.modules.medcard.widgets.views.MedcardHistoryDiagnosisWidget', $this->data);
		/*

		print $greeting->note;
		print '<div>diagnosis</div>';
		print $this->greetingId;
		*/
	}
	private function getMkb10Descriptions($entities){
		$list=[];
		foreach($entities as $item){
			$list[]=$this->getFullMkbDescription($item['mkb10_id']);
		}
		return $list;
	}
	private function getClinicalDescriptions($entities){
		$list=[];
		foreach($entities as $item){
			$diagnosis=ClinicalDiagnosis::model()->findByPk($item['diagnosis_id']);
			$list[]=$diagnosis['description'];
		}
		return $list;
	}
	private function getFullMkbDescription($mkbId){
		$mkb10=Mkb10::model()->findByPk($mkbId);
		if ($mkb10['parent_id']!=null){
			$parent=Mkb10::model()->findByPk($mkb10['parent_id']);
			return $parent['description'].' / '.$mkb10['description'];
			//return $this->getFullMkbDescription($mkb10['parent_id']).' / '.$mkb10['description'];
		}
		return $mkb10['description'];
	}

}