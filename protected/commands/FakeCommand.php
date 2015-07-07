<?php

require __DIR__.'/../vendor/faker/autoload.php';

class FakeCommand extends CConsoleCommand {

	public function actionPolicy() {
		$faker = \Faker\Factory::create('ru_RU');
		$pagination = new CPagination(Oms::model()->count());
		$total = $pagination->getItemCount();
		$finished = 0;
		$pagination->setPageSize(10000);
		for ($i = 0; $i < $pagination->getPageCount(); $i++) {
			$pagination->setCurrentPage($i);
			$rows = Yii::app()->getDb()->createCommand()
				->select('id')
				->from('mis.oms')
				->limit($pagination->getLimit(), $pagination->getOffset())
				->queryAll();
			foreach ($rows as $row) {
				Yii::app()->getDb()->createCommand()
					->update('mis.oms', [
						'first_name' => mb_strtoupper($faker->firstName, 'UTF-8'),
						'last_name' => mb_strtoupper($faker->lastName, 'UTF-8'),
						'middle_name' => mb_strtoupper($faker->{'middleName'}, 'UTF-8')
					], 'id = :id', [
						':id' => $row['id']
					]);
				++$finished;
			}
			print_r((((float) $finished / $total) * 100) . '%, ');
		}
	}

	public function actionPatient() {
		$faker = \Faker\Factory::create('ru_RU');
		$pagination = new CPagination(Oms::model()->count());
		$total = $pagination->getItemCount();
		$finished = 0;
		$pagination->setPageSize(10000);
		for ($i = 0; $i < $pagination->getPageCount(); $i++) {
			$pagination->setCurrentPage($i);
			$rows = Yii::app()->getDb()->createCommand()
				->select('id')
				->from('lis.patient')
				->limit($pagination->getLimit(), $pagination->getOffset())
				->queryAll();
			foreach ($rows as $row) {
				Yii::app()->getDb()->createCommand()
					->update('lis.patient', [
						'surname' => mb_strtoupper($faker->lastName, 'UTF-8'),
						'name' => mb_strtoupper($faker->firstName, 'UTF-8'),
						'patronymic' => mb_strtoupper($faker->{'middleName'}, 'UTF-8')
					], 'id = :id', [
						':id' => $row['id']
					]);
				++$finished;
			}
			print_r((((float) $finished / $total) * 100) . '%, ');
		}
	}
}