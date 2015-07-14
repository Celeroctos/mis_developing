<?php
class UtilitesController extends Controller {
    public function actionMakeStandartDocserie() {
        $connection = Yii::app()->db;
        // Doctype for all wrong in db
        $connection->createCommand(
            "UPDATE mis.medcards
                SET doctype = 6
                WHERE doctype = 1 AND serie !~ '^[\d\s]{4,5}$'"
        )->execute();

        // Serie in standart format...
        $connection->createCommand(
            "UPDATE mis.medcards
                SET serie = CONCAT(SUBSTR(RTRIM(LTRIM(serie)), 1, 2), ' ', SUBSTR(RTRIM(LTRIM(serie)), 3, 2))
                WHERE doctype = 1 AND LENGTH(RTRIM(LTRIM(serie))) != 5"
        )->execute();

        echo "OK!";
    }
	
	public function actionAddUserToDoctor() {
		$users = User::model()->findAll();
		echo Yii::app()->db->connectionString;
		echo "<pre>";
		foreach($users as $user) {
			if(!$user['employee_id']) {
				continue;
			}
			$doc = Doctor::model()->findByPk($user['employee_id']);
			print_r($doc);
			if($doc) {
				$doc->user_id = $user['id'];
				$doc->save();
			}
		}
	}
}
?>