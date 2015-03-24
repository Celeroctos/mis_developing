<?php

class m150324_100502_hospital_base extends CDbMigration
{
	public function up() {
        $this->getDbConnection()->createCommand(
            "CREATE SCHEMA hospital"
        )->execute();

        $this->getDbConnection()->createCommand(
            "CREATE TABLE hospital.hospitalization_queue -- Очереди на госпитализацию
            (
              id serial NOT NULL,
              type integer, -- Тип очереди
              num_pre integer, -- Кол-во по предв. записи
              num_queue integer, -- Кол-во по живой очереди
              comission_date date -- Дата комисии
            )
            WITH (
              OIDS=FALSE
            );"
        )->execute();

        $this->getDbConnection()->createCommand(
            "ALTER TABLE hospital.hospitalization_queue
              OWNER TO postgres;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "CREATE TABLE hospital.medical_directions -- Направления от врачей
            (
              id serial NOT NULL,
              patient_id integer, -- ID пациента (таблица hospital.patients)
              doctor_id integer, -- ID доктора, кто выдал направление (hospital.doctor_id)
              is_pregnant integer, -- Беременная пациентка на момент выписки направления или нет
              ward_id integer, -- В какое отделение выдали направление (mis.wards)
              type integer, -- Тип госпитализации (обычная (0) / срочная (1) )
              create_date date DEFAULT now(), -- Дата создания
              pregnant_term numeric(4,2),
              hospitalization_date date, -- Дата госпитализации
              card_number character varying(20), -- Номер карты, на которое выписано направление
              is_refused integer, -- Отказ от госпитализации (в дальнейшем возможно использование для описания причины отказа)
              is_showed integer, -- Осмотрена врачом или нет
              write_type integer -- Тип комиссии по госпитализации ...
            )
            WITH (
              OIDS=FALSE
            );"
        )->execute();

        $this->getDbConnection()->createCommand(
            "ALTER TABLE hospital.medical_directions
              OWNER TO postgres;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "CREATE TABLE hospital.patient -- Сущность пациента
            (
              id serial NOT NULL,
              first_name character varying(50), -- Имя пациента
              last_name character varying(50), -- Фамилия
              middle_name character varying(50), -- Отчество
              birthday date, -- Дата рождения
              oms_id integer -- ID ОМСа
            )
            WITH (
              OIDS=FALSE
            );"
        )->execute();

        $this->getDbConnection()->createCommand(
           "ALTER TABLE hospital.patient
              OWNER TO postgres;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "CREATE OR REPLACE VIEW hospital.comission_grid AS
             SELECT concat(t1.first_name, ' ', t1.last_name, ' ', t1.middle_name) AS fio,
                t1.id AS patient_id,
                t1.birthday,
                t2.name AS ward_name,
                t2.id AS ward_id,
                t3.doctor_id,
                t3.is_pregnant,
                t3.type,
                t3.create_date,
                t3.id AS direction_id,
                t3.pregnant_term,
                    CASE
                        WHEN t3.type = 0 THEN 'Обычная'::text
                        WHEN t3.type = 1 THEN 'По записи'::text
                        ELSE 'Неизвестно'::text
                    END AS comission_type_desc,
                t3.card_number,
                t3.hospitalization_date,
                t3.is_refused,
                t3.write_type
               FROM hospital.medical_directions t3
               LEFT JOIN mis.wards t2 ON t2.id = t3.ward_id
               JOIN hospital.patient t1 ON t1.id = t3.patient_id
              WHERE t3.type = 0;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "ALTER TABLE hospital.comission_grid
              OWNER TO postgres;
              COMMENT ON VIEW hospital.comission_grid
              IS 'Таблица комиссии на госпитализацию'"
        )->execute();

        $this->getDbConnection()->createCommand(
            "CREATE OR REPLACE VIEW hospital.hospitalization_grid AS
             SELECT concat(t1.first_name, ' ', t1.last_name, ' ', t1.middle_name) AS fio,
                t1.id AS patient_id,
                t2.name AS ward_name,
                t2.id AS ward_id,
                t3.is_pregnant,
                t3.type,
                t3.id AS direction_id,
                t3.pregnant_term,
                    CASE
                        WHEN t3.type = 0 THEN 'Обычная'::text
                        WHEN t3.type = 1 THEN 'По записи'::text
                        ELSE 'Неизвестно'::text
                    END AS comission_type_desc,
                t3.card_number,
                t3.is_showed,
                t3.is_refused,
                t3.write_type
               FROM hospital.medical_directions t3
               LEFT JOIN mis.wards t2 ON t2.id = t3.ward_id
               JOIN hospital.patient t1 ON t1.id = t3.patient_id
              WHERE t3.hospitalization_date IS NULL AND t3.type = 1 OR t3.hospitalization_date IS NOT NULL OR t3.is_refused = 1;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "ALTER TABLE hospital.hospitalization_grid
              OWNER TO postgres;
             COMMENT ON VIEW hospital.hospitalization_grid
              IS 'Таблица госпитализации'"
        );

        $this->getDbConnection()->createCommand(
            "CREATE OR REPLACE VIEW hospital.queue_grid AS
             SELECT concat(t1.first_name, ' ', t1.last_name, ' ', t1.middle_name) AS fio,
                t1.id AS patient_id,
                t1.birthday,
                t2.name AS ward_name,
                t2.id AS ward_id,
                t3.doctor_id,
                t3.is_pregnant,
                t3.type,
                t3.create_date,
                t3.id AS direction_id,
                t3.pregnant_term,
                t3.card_number,
                t3.hospitalization_date,
                t3.is_refused,
                    CASE
                        WHEN t3.type = 0 THEN 'Обычная'::text
                        WHEN t3.type = 1 THEN 'По записи'::text
                        ELSE 'Неизвестно'::text
                    END AS comission_type_desc
               FROM hospital.medical_directions t3
               LEFT JOIN mis.wards t2 ON t2.id = t3.ward_id
               JOIN hospital.patient t1 ON t1.id = t3.patient_id
              WHERE t3.type = 0 AND t3.hospitalization_date IS NULL;"
        )->execute();

        $this->getDbConnection()->createCommand(
            "ALTER TABLE hospital.queue_grid
              OWNER TO postgres;
            COMMENT ON VIEW hospital.queue_grid
              IS 'Таблица очереди на госпитализацию'"
        )->execute();
	}

	public function down() {
        $this->getDbConnection()->createCommand(
            "DROP VIEW hospital.comission_grid"
        )->execute();
        $this->getDbConnection()->createCommand(
            "DROP VIEW hospital.hospitalization_grid"
        )->execute();
        $this->getDbConnection()->createCommand(
            "DROP VIEW hospital.queue_grid"
        )->execute();
        $this->getDbConnection()->createCommand(
            "DROP TABLE hospital.medical_directions"
        )->execute();
        $this->getDbConnection()->createCommand(
            "DROP TABLE hospital.hospitalization_queue"
        )->execute();
        $this->getDbConnection()->createCommand(
            "DROP TABLE hospital.patient"
        )->execute();

        $this->getDbConnection()->createCommand(
            "DROP SCHEMA hospital CASCADE"
        )->execute();
	}
}