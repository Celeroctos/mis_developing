<?php

namespace laboratory\controllers;

use ActiveRecord;
use AnalysisType;
use CDbCriteria;
use CException;
use CHttpException;
use ControllerEx;
use CWidget;
use Exception;
use Laboratory_Address;
use Laboratory_CardNumberGenerator;
use Laboratory_Direction;
use Laboratory_DirectionParameter;
use Laboratory_Document;
use Laboratory_Form_Direction;
use Laboratory_Medcard;
use Laboratory_Patient;
use Laboratory_PatientCategory;
use Laboratory_Policy;
use Yii;

class DirectionController extends ControllerEx {

	/**
	 * Default view action
	 */
    public function actionView() {
        $this->render("view");
    }

	/**
	 * That action will load widget with direction
	 * register with just appended LIS medcard id
	 *
	 * @in (GET):
	 *  + medcard - Medcard identification number from
	 * laboratory module
	 *
	 * @out (JSON):
	 *  + [message] - Response message with error or success
	 *  + [component] - Widget component for
	 *  + status - Response status, true or false
	 *
	 * @throws Exception
	 */
	public function actionLoadWidget() {
		try {
			if (($id = Yii::app()->getRequest()->getQuery("medcard")) == null) {
				throw new CException("Can't resolve \"medcard\" identification number as query parameter");
			}
			if (Laboratory_Medcard::model()->findByPk($id) == null) {
				throw new CException("Unresolved laboratory's medcard identification number \"{$id}\"");
			}
			$widget = $this->getWidget("AutoForm", [
				"model" => new Laboratory_Form_Direction("laboratory.treatment.register", [
					"medcard_id" => $id
				])
			]);
			$this->leave([
				"component" => $widget,
				"status" => true
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Register some form's values in database, it will automatically
	 * fetch model from $_POST["model"], decode it, build it's FormModel
	 * object and save into database. But you must override
	 * ControllerEx::getModel and return instance of controller's model else
	 * it will throw an exception
	 *
	 * @in (POST):
	 *  + model - String with serialized client form via $("form").serialize(), if you're
	 * 		using Modal or Panel widgets that it will automatically find button with
	 * 		submit type and send ajax request
	 * @out (JSON):
	 *  + message - Message with status
	 *  + status - True if everything ok
	 *
	 * @see Controller2::getModel
	 * @see Modal
	 * @see Panel
	 */
	public function actionRegister() {
		$this->leave([
			'message' => 'Направление успешно зарегистрировано',
			'direction' => 1,
            'type' => 'success',
		]);		
		return;
		// Open new transaction for direction registering
		$transaction = Yii::app()->getDb()->beginTransaction();

		try {

			// require Laboratory_Form_DirectionEx model from post request with next attributes
			$form = $this->requireModel('Laboratory_Form_DirectionEx', [
				'analysis_type_id',
				'analysis_parameters',
				'pregnant',
				'smokes',
				'gestational_age',
				'menstruation_cycle',
				'race',
				'history',
				'comment',
				'medcard_id'
			]);

            $card = null;

			// If we have not medcard identification number, then we must
			// register information about patient in database before
			if (!$form->{'medcard_id'}) {
				$medcard->{'id'} = $this->registerMedcardForDirection($patient, $medcard, $card, false);
			} else {
				$medcard = Laboratory_Medcard::model()->findByPk($form->{'medcard_id'});
			}

			// Post validation errors
			if ($this->hasErrors()) {
				$this->postErrors();
			}

			// Load table model for patient category with next attributes
			$category = Laboratory_PatientCategory::load([
				'pregnant' => $form->{'pregnant'},
				'smokes' => $form->{'smokes'},
				'gestational_age' => $form->{'gestational_age'},
				'menstruation_cycle' => $form->{'menstruation_cycle'},
				'race' => $form->{'race'}
			]);

			// Try to save model in database
			if (!$category->save()) {
				$this->postErrors($category->errors);
			}

			// Get mis medcard identification number to provider
			// auto loading
			# TODO 'Add mis medcard autoload'
			if (isset($form->{'mis_medcard'})) {
				$mis_medcard = $form->{'mis_medcard'};
			} else {
				$mis_medcard = null;
			}

			// Get sender's identification number
			if (isset($form->{'sender_id'}) && !empty($form->{'sender_id'})) {
				$sender = $form->{'sender_id'};
			} else {
				$sender = Yii::app()->{'user'}->{'getState'}('doctorId');
			}

			// Load table model for direction
			$direction = Laboratory_Direction::load([
				'barcode' => null,
				'status' => Laboratory_Direction::STATUS_TREATMENT_ROOM,
				'comment' => $form->{'comment'},
				'analysis_type_id' => $form->{'analysis_type_id'},
				'medcard_id' => $medcard->{'id'},
				'sender_id' => $sender,
				'sending_date' => date('Y-m-d H:i:s.u'),
				'treatment_room_employee_id' => Yii::app()->{'user'}->{'getState'}('doctorId'),
				'laboratory_employee_id' => null,
				'history' => $form->{'history'},
				'ward_id' => null,
				'patient_category_id' => $category->{'id'}
			]);

			// Try to save direction in database
			if (!$direction->save(true)) {
				$this->postErrors($direction->errors);
			}

			// After saving we have to set it's barcode as primary key (maybe changed)
			Laboratory_Direction::model()->updateByPk($direction->{'id'}, [
				'barcode' => $direction->{'id'}
			]);

			// Register direction parameters in database
			foreach ($form->{'analysis_parameters'} as $id) {
				$dp = Laboratory_DirectionParameter::load([
					'direction_id' => $direction->{'id'},
					'analysis_type_parameter_id' => $id
				]);
				# Hot! Hot! Hot!
				if (!$dp->validate()) {
					$this->postErrors($dp->errors);
				} else {
					Yii::app()->getDb()->createCommand()->insert('lis.direction_parameter', [
						'direction_id' => $direction->{'id'},
						'analysis_type_parameter_id' => $id
					]);
				}
			}

			// Commit all changes and return response with new direction's id
			$transaction->commit();
			$this->leave([
				'message' => 'Направление успешно зарегистрировано',
				'direction' => $direction->{'id'},
                'type' => $card != null ? 'warning' : 'success',
			]);
		} catch (Exception $e) {
			$transaction->rollback();
			$this->exception($e);
		}
	}

	/**
	 * That function registers information about patient
     *
	 * @param Laboratory_Patient $patient - Instance with just registered
	 *  patient active record
     *
	 * @param Laboratory_Medcard $medcard - Instance with just registered
	 *  patient's medcard
     *
     * @param string $card - Number of regenerated medcard number if
     *  received in use
     *
	 * @param bool $withTransaction - Shall use transaction
     *
	 * @return int - Medcard identification number
	 * @throws Exception
	 */
	private function registerMedcardForDirection(&$patient, &$medcard, &$card = null, $withTransaction = true) {

		// Get form with properties, like checked passport or policy
		$propertyForm = Yii::app()->getRequest()->getPost('PropertyForm');

		// Require form for medcard and patient with next attributes
		$patientForm = $this->requireModel('Laboratory_Form_Patient', [
			'surname', 'name', 'patronymic', 'sex', 'birthday', 'contact', 'work_place'
		]);
		$medcardForm = $this->requireModel('Laboratory_Form_Medcard', [
			'mis_medcard', 'card_number', 'enterprise_id'
		]);

        // Check if we already has medcard with that number
        $card = Laboratory_Medcard::model()->findByAttributes([
            'card_number' => $medcardForm['card_number'],
        ]);
        if ($card != null) {
            $medcardForm['card_number'] = $card = Laboratory_CardNumberGenerator::getGenerator()->generate();
        }

		// Require passport and policy forms if have it's properties active
		if (isset($propertyForm['passport'])) {
			$passportForm = $this->requireModel('Laboratory_Form_Document', [
				'series', 'number', 'subdivision_name', 'subdivision_code', 'issue_date'
			]);
		} else {
			$passportForm = null;
		}
		if (isset($propertyForm['policy'])) {
			$policyForm = $this->requireModel('Laboratory_Form_Policy', [
				'number', 'issue_date', 'insurance_id'
			]);
		} else {
			$policyForm = null;
		}

		// Require forms with addresses, but remove last _1/_2 symbols, cuz
		// form's name is AddressForm
		$registerAddressForm = $this->requireModel('AddressForm_1', null, "/_\\d+$/");
		$addressForm = $this->requireModel('AddressForm_2', null, "/_\\d+$/");

		// If we have any validation errors, then post it
		if ($this->hasErrors()) {
			$this->postErrors();
		}

		// Begin transaction for different SQL actions
		if ($withTransaction) {
			$transaction = Yii::app()->getDb()->beginTransaction();
		} else {
			$transaction = null;
		}

		try {
			// Create model for address and address of registration
			// and insert it in database
			$address = Laboratory_Address::loadFromModel($addressForm, [
				'string' => $patientForm['address_id']
			]);
			if (!$address->save(true)) {
				$this->postErrors($address->errors);
			}
			$registerAddress = Laboratory_Address::loadFromModel($registerAddressForm, [
				'string' => $patientForm['register_address_id']
			]);
			if (!$registerAddress->save(true)) {
				$this->postErrors($registerAddress->errors);
			}

			// Load models for passport and policy and save it in database
			if ($passportForm != null) {
				$passport = Laboratory_Document::loadFromModel($passportForm, [
					'surname' => $patientForm['surname'],
					'name' => $patientForm['name'],
					'patronymic' => $patientForm['patronymic'],
					'birthday' => $patientForm['birthday']
				]);
				if (!$passport->save(true)) {
					$this->postErrors($passport->errors);
				}
			} else {
				$passport = null;
			}
			if ($policyForm != null) {
				$policy = Laboratory_Policy::loadFromModel($policyForm, [
					'surname' => $patientForm['surname'],
					'name' => $patientForm['name'],
					'patronymic' => $patientForm['patronymic'],
					'birthday' => $patientForm['birthday'],
					'document_type' => null
				]);
				if (!$policy->save(true)) {
					$this->postErrors($policy->errors);
				}
			} else {
				$policy = null;
			}

			// Load model for patient and register it in database
			$patient = Laboratory_Patient::loadFromModel($patientForm, [
				'address_id' => $address->{'id'},
				'register_address_id' => $registerAddress->{'id'},
				'passport_id' => $passport != null ? $passport->{'id'} : null,
				'policy_id' => $policy != null ? $policy->{'id'} : null
			]);
			if (!$patient->save(true)) {
				$this->postErrors($patient->errors);
			}

			// Load model for medcard and register it in database
			$medcard = Laboratory_Medcard::loadFromModel($medcardForm, [
				'sender_id' => Yii::app()->{'user'}->{'getState'}('doctorId'),
				'patient_id' => $patient->{'id'}
			]);
			if (!$medcard->save(true)) {
				$this->postErrors($medcard->errors);
			}

			// Commit all changes
			if ($withTransaction) {
				$transaction->commit();
			}

		} catch (Exception $e) {
			if ($withTransaction) {
				$transaction->rollback();
			}
			$this->exception($e);
		}

		return $medcard->{"id"};
	}

	public function actionGetTable() {
		try {
			if (!$class = Yii::app()->getRequest()->getQuery("class")) {
				throw new CException("Can't resolve [class] parameter");
			}
			if (!$attributes = json_decode(Yii::app()->getRequest()->getQuery("attributes"), true)) {
				throw new CException("Can't resolve [attributes] parameter");
			}
			$this->leave([
				"component" => $this->getWidget($class, [
						"date" => Yii::app()->getRequest()->getQuery("date")
					] + $attributes),
				"dates" => Laboratory_Direction::model()->getDates(Laboratory_Direction::STATUS_TREATMENT_ROOM)
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionParams() {
		try {
			if (!$id = Yii::app()->getRequest()->getQuery("id")) {
				throw new CException("Can't resolve analysis type identification number");
			} else {
				$this->leave([
					"component" => $this->getWidget("Laboratory_Widget_AnalysisParameterChecker", [
						"parameters" => AnalysisType::model()->findAnalysisParameters($id)
					])
				]);
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * That action sets direction status to 4, which means that
	 * analysis should be repeated
	 *
	 * @in (POST):
	 *  + id - direction's identification number
	 * @out (JSON):
	 *  + [message] - Response message
	 *  + status - Action result status
	 *
	 * @throws Exception
	 */
	public function actionRepeat() {
		try {
			$r = Laboratory_Direction::model()->updateByPk(Yii::app()->getRequest()->getPost("id"), [
				"status" => Laboratory_Direction::STATUS_TREATMENT_REPEAT
			]);
			if (!$r) {
				$this->error("Произошла ошибка при обновлении данных. Направление не было отправлено на повторный забор образца");
			} else {
				$this->leave([
					"message" => "Направление отправлено на повторный забор образца",
					"repeats" => Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_TREATMENT_REPEAT),
					"dates" => Laboratory_Direction::model()->getDates(Laboratory_Direction::STATUS_TREATMENT_ROOM)
				]);
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionRestore() {
		try {
			$r = Laboratory_Direction::model()->updateByPk(Yii::app()->getRequest()->getPost("id"), [
				"status" => Laboratory_Direction::STATUS_TREATMENT_ROOM
			]);
			if (!$r) {
				$this->error("Произошла ошибка при обновлении данных. Направление не было установлено как новое");
			} else {
				$this->leave([
					"message" => "Направление восстановлено как новое",
					"repeats" => Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_TREATMENT_REPEAT),
					"dates" => Laboratory_Direction::model()->getDates(Laboratory_Direction::STATUS_TREATMENT_ROOM)
				]);
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionLaboratory() {
		try {
			$form = $this->requireModel("Laboratory_Form_AboutDirection");
			if ($form->hasErrors()) {
				$this->postErrors($form->errors);
			}
			$direction = Laboratory_Direction::model()->findByPk($form->{"direction_id"});
			$parameters = [
				"sample_type_id" => $form->{"sample_type_id"},
				"sending_date" => $form->{"sending_date"}." ".$form->{"sending_time"}.".000000",
				"comment" => $form->{"comment"}
			];
			if ($direction->{"status"} == Laboratory_Direction::STATUS_TREATMENT_ROOM ||
				$direction->{"status"} == Laboratory_Direction::STATUS_TREATMENT_REPEAT
			) {
				$parameters += [
					"status" => Laboratory_Direction::STATUS_LABORATORY
				];
			}
			$r = Laboratory_Direction::model()->updateByPk($form->{"direction_id"}, $parameters);
			if (!$r) {
				throw new CException("Can't refresh direction");
			}
			$params = Laboratory_Direction::getIds(Laboratory_Direction::model()->getAnalysisParameters(
				$form->{"direction_id"}, false
			));
			foreach ($form->{"analysis_parameters"} as $id) {
				if ($this->arrayInDrop([ "id" => $id ], $params)) {
					continue;
				}
				$ar = Laboratory_DirectionParameter::load([
					"direction_id" => $form->{"direction_id"},
					"analysis_type_parameter_id" => $id
				]);
				if (!$ar->save()) {
					throw new CException("Can't save analysis parameter");
				}
			}
			foreach ($params as $id) {
				# Why Yii can't execute delete SQL query via [deleteAll] method ???
				Yii::app()->getDb()->createCommand()->delete("lis.direction_parameter", "direction_id = :d and analysis_type_parameter_id = :a", [
					":d" => $form->{"direction_id"}, ":a" => $id
				]);
			}
            $moved = [
                Laboratory_Direction::STATUS_TREATMENT_ROOM,
                Laboratory_Direction::STATUS_TREATMENT_REPEAT,
            ];
			if (in_array($direction->{"status"}, $moved)) {
				$msg = "Направление отправлено в лабораторию";
			} else {
				$msg = "Направление сохранено";
			}
			$this->leave([
				"message" => $msg,
				"dates" => Laboratory_Direction::model()->getDates(Laboratory_Direction::STATUS_TREATMENT_ROOM),
				"repeats" => Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_TREATMENT_REPEAT),
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionSearch() {
		try {
			if (!$form = Yii::app()->getRequest()->getPost('Laboratory_Form_DirectionSearch')) {
				throw new CException('Direction search requires [Laboratory_Form_DirectionSearch] form');
			}
			$criteria = new CDbCriteria();
			foreach ([ 'surname', 'name', 'patronymic' ] as $k) {
				if (isset($form[$k]) && !empty($form[$k])) {
					$criteria->addSearchCondition('lower(patient.'.$k.')', mb_strtolower((string) $form[$k], 'UTF-8'));
				}
			}
			if (isset($form['card_number']) && !empty($form['card_number'])) {
				$criteria->addSearchCondition('medcard.card_number', $form['card_number']);
			}
			if (isset($form['analysis_type_id']) && $form['analysis_type_id'] != -1) {
				$criteria->addColumnCondition([
					'analysis_type.analysis_type_id' => $form['analysis_type_id']
				]);
			}
			if (isset($form['config'])) {
                $config = json_decode($form['config'], true);
			} else {
				$config = [];
			}
            if ($config === false) {
                $config = [];
            }
            $config = \CMap::mergeArray($config, [
                'condition' => [
                    'condition' => $criteria->condition,
                    'params' => $criteria->params
                ]
            ]);
			$widget = $this->createWidget($form['widget'], [
				'provider' => new $form['provider']($config)
			]);
			if (!$widget instanceof CWidget) {
				throw new Exception('Loaded widget must be an instance of [app\\core\\Widget] class');
			}
			$widget->run();
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionTest() {
		try {
			if (!$id = Yii::app()->getRequest()->getQuery("id")) {
				throw new CException("Test action requires direction identification number");
			} else if (!$direction = Laboratory_Direction::model()->findByAttributes([ "id" => $id ])) {
				throw new CHttpException(404, "Направление с номером ($id) не зарегистрировано в системе");
			} else {
				$status = Yii::app()->getRequest()->getQuery("status", Laboratory_Direction::STATUS_LABORATORY);
			}
			if ($direction->{"status"} != $status) {
				$this->error("Направление с номером ($id) не отправлялось в лабораторию");
			} else {
				$this->leave();
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionCheck() {
		try {
			if (!$directions = Yii::app()->getRequest()->getPost("directions")) {
				$this->leave([
					"ready" => [],
					"total" => Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_READY)
				]); exit();
			} else if (!$status = Yii::app()->getRequest()->getPost("status")) {
				throw new Exception("Check action requires direction status");
			}
			$ready = [];
			foreach ($directions as $pk) {
				if (!$d = Laboratory_Direction::model()->findByPk($pk)) {
					continue;
				}
				if ($d->{"status"} == $status) {
					$ready[] = $pk;
				}
			}
			$this->leave([
				"ready" => $ready,
				"total" => Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_READY)
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
     * Override that method to return controller's model
     * @return ActiveRecord - Controller's model instance
     */
    public function getModel() {
        return new Laboratory_Direction();
    }
}