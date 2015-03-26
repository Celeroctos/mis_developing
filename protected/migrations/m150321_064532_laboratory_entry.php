<?php

class m150321_064532_laboratory_entry extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL

		CREATE TABLE "lis"."passport" (
		  "id" SERIAL PRIMARY KEY, -- Первичный ключ
		  "surname" VARCHAR(100) NOT NULL, -- Фамилия
		  "name" VARCHAR(100) NOT NULL, -- Имя
		  "patronymic" VARCHAR(100) NOT NULL, -- Отчество
		  "birthday" DATE NOT NULL, -- Дата рождения
		  "series" VARCHAR(10) NOT NULL UNIQUE, -- Серия
		  "number" VARCHAR(10) NOT NULL UNIQUE, -- Номер
		  "subdivision_name" VARCHAR(200) NOT NULL, -- Наименование подразделения
		  "subdivision_code" VARCHAR(10) NOT NULL, -- Код подразделения
		  "issue_date" DATE NOT NULL -- Дата выдачи
		);

		CREATE TABLE "lis"."policy" (
		  "id" SERIAL PRIMARY KEY, -- Первичный ключ
		  "surname" VARCHAR(100) NOT NULL, -- Фамилия
		  "name" VARCHAR(100) NOT NULL, -- Имя
		  "patronymic" VARCHAR(100) NOT NULL, -- Отчество
		  "birthday" DATE NOT NULL, -- Дата рождения
		  "number" VARCHAR(50) NOT NULL, -- Номер полиса
		  "issue_date" DATE NOT NULL, -- Дата выдачи
		  "insurance_id" INT REFERENCES "mis"."insurances"("id"), -- Страховая компания
		  "document_type" INT REFERENCES "mis"."doctypes"("id")
		);

		CREATE TABLE "lis"."address" (
		  "id" SERIAL PRIMARY KEY, -- Первичный ключ
		  "street_name" VARCHAR(100) DEFAULT NULL, -- Название улицы
		  "house_number" VARCHAR(10) DEFAULT NULl, -- Номер дома
		  "flat_number" VARCHAR(10) DEFAULT NULL, -- Номер квартиры
		  "post_index" INT DEFAULT NULL, -- Почтовый индекс,
		  "region_name" VARCHAR(100) DEFAULT NULL, -- Регион
		  "district_name" VARCHAR(100) DEFAULT NULL -- Район
		);

		CREATE TABLE "lis"."patient" (
		  "id" SERIAL PRIMARY KEY, -- Первичный ключ
		  "surname" VARCHAR(100) NOT NULL, -- Фамилия пациента
		  "name"  VARCHAR(50) NOT NULL, -- Имя пациента
		  "patronymic" VARCHAR(100) DEFAULT NULL, -- Отчество пациента
		  "sex" INT NOT NULL, -- Пол пациента
		  "birthday" DATE NOT NULL, -- Дата рождения
		  "passport_id" INT REFERENCES "lis"."passport"("id") DEFAULT NULL, -- Паспорт
		  "register_address_id" INT REFERENCES "lis"."address"("id") DEFAULT NULL, -- Адрес регистрации
		  "address_id" INT REFERENCES "lis"."address"("id") DEFAULT NULL -- Адрес фактического проживания
		);

		CREATE TABLE "lis"."medcard" (
		  "id" SERIAL PRIMARY KEY, -- Первичный ключ
		  "mis_medcard" VARCHAR(50) REFERENCES "mis"."medcards"("card_number") DEFAULT NULL, -- Медкартка в МИС
		  "sender_id" INT REFERENCES "mis"."doctors"("id") DEFAULT NULL, -- Направитель
		  "patient_id" INT REFERENCES "lis"."patient"("id") DEFAULT NULL, -- Пациент
		  "card_number" VARCHAR(50) NOT NULL,
		  "enterprise_id" INT REFERENCES "mis"."enterprise_params"("id")
		);

		CREATE TABLE "lis"."direction" (
		  "id" SERIAL PRIMARY KEY, -- Первичый ключ
		  "barcode" INT, -- Баркод
		  "status" INT, -- Статус [DirectionStatusField]
		  "comment" TEXT NOT NULL DEFAULT '', -- Комментарий
		  "analysis_type_id" INT REFERENCES "lis"."analysis_type"("id"), -- Тип анализа
		  "medcard_id" INT REFERENCES "lis"."medcard", -- Медкарта
		  "sender_id" INT REFERENCES "mis"."doctors"("id"), -- Направитель
		  "sending_date" TIMESTAMP DEFAULT now(), -- Дата создания направления
		  "treatment_room_employee_id" INT REFERENCES "mis"."doctors"("id"), -- Сотрудник процедурного кабинета
		  "laboratory_employee_id" INT REFERENCES "mis"."doctors"("id"), -- Сотрудник лаборатории
		  "history" TEXT DEFAULT '', -- История направления
		  "ward_id" INT REFERENCES "mis"."wards"("id"), -- Подразделение
		  "enterprise_id" INT REFERENCES "mis"."enterprise_params"("id"), -- Медицинское учреждение
		  "is_repeated" INT DEFAULT 0 -- Является ли повторным
		)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL

		DROP TABLE "lis"."direction";
		DROP TABLE "lis"."medcard";
		DROP TABLE "lis"."patient";
		DROP TABLE "lis"."address";
		DROP TABLE "lis"."policy";
		DROP TABLE "lis"."passport";
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}