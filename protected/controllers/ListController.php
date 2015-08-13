<?php
class ListController extends Controller {
	public function actionWards() {
    	$enterpriseId=$_GET['enterprise_id'];
        // Список отделений
        $ward = new Ward();
        if ($enterpriseId==-1){
        	$filters=false;
        }else{
			$filters=array(
	        	'groupOp' => 'AND',
	        	'rules' => array(
	        		array(
	        			'field'=>'enterprise_id',
	        			'op'=>'eq',
	        			'data'=>$enterpriseId
	        		)
	        	)
	        );    	
        }
        $wards = array(
       		//array('id'=>'-1', 'name' => 'Нет')
        );
        $wardsResult = $ward->getRows($filters, 'name', 'asc');
        $wards=array_merge($wards,$wardsResult);
    	
        echo CJSON::encode(array('success' => true, 'data'=>$wards));
	}
	
	public function actionDoctors(){
		$wardId=$_GET['ward_id'];
		$doctor = new Doctor();
		if ($wardId==-1||$wardId=='null'){
			$filters=false;
		}else{
			$filters=array(
	        	'groupOp' => 'AND',
	        	'rules' => array(
	        		array(
	        			'field'=>'ward_code',
	        			'op'=>'eq',
	        			'data'=>$wardId
	        		)
	        	)
	        ); 
		}
		$rows = $doctor->getRows($filters,'last_name, first_name, middle_name','asc');
		$list=array();
		foreach($rows as $row){
			$list[]=[
				'id'=>$row['id'],
				'name'=>sprintf('%s %s %s',$row['last_name'],$row['first_name'],$row['middle_name'])
			];
		}
		echo CJSON::encode(array('success' => true, 'data'=>$list));
	}
}
?>