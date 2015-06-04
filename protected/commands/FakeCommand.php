<?php

require __DIR__.'/../vendor/faker/autoload.php';

class FakeCommand extends CConsoleCommand {

	public function actionPolicy() {
		$faker = \Faker\Factory::create('ru_RU');
		$pagination = new CPagination(Oms::model()->count());
		$total = $pagination->getItemCount();
		$finished = 0;
		$pagination->setPageSize(1000);
		for ($i = 0; $i < $pagination->getPageCount(); $i++) {
			$pagination->setCurrentPage($i);
			$rows = Yii::app()->getDb()->createCommand()
				->select('id, first_name')
				->from('mis.oms')
				->limit($pagination->getLimit(), $pagination->getOffset())
				->queryAll();
			foreach ($rows as $row) {
				if (!is_numeric($row['first_name'])) {
					continue;
				}
				Yii::app()->getDb()->createCommand()
					->update('mis.oms', [
						'first_name' => ($faker->firstName),
						'last_name' => ($faker->lastName),
						'middle_name' => ($faker->{'middleName'})
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
				->select('id, first_name')
				->from('lis.patient')
				->limit($pagination->getLimit(), $pagination->getOffset())
				->queryAll();
			foreach ($rows as $row) {
				if (!is_numeric($row['surname'])) {
					continue;
				}
				Yii::app()->getDb()->createCommand()
					->update('lis.patient', [
						'surname' => ($faker->lastName),
						'name' => ($faker->firstName),
						'patronymic' => ($faker->{'middleName'})
					], 'id = :id', [
						':id' => $row['id']
					]);
				++$finished;
			}
			print_r((((float) $finished / $total) * 100) . '%, ');
		}
	}
}