<?php

abstract class GController extends LController {

	/**
	 * @var string - Guide default layout
	 */
	public $layout = 'application.modules.guides.views.layouts.index';

	/**
	 * Override that method to return name of received form, if returned
	 * value is null, then it receives name from GActiveRecord instance
	 * via getModel method
	 * @see GController::getModel
	 * @return string|null - Name of form or null
	 */
	public function getModelName() {
		return null;
	}

	/**
	 * Default view action, it displays table
	 * with guide rows and all allowed actions
	 */
	public function actionIndex() {
		$this->render("index");
	}

	/**
	 * Override that method to realize your own implementation
	 * of that algorithm with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionCreate() {
		try {
			if (!Yii::app()->getRequest()->getIsPostRequest()) {
				throw new CHttpException(405, "Request method not allowed, requires POST");
			}
			$model = $this->getModel();
			if (!$model instanceof GActiveRecord) {
				throw new CException("Model must be an instance of GActiveRecord class");
			}
			if (($name = $this->getModelName()) == null) {
				$name = get_class($model);
			}
			$name = $name."Form";
			if (!isset($_POST[$name])) {
				throw new CException("Can't receive form's model \"$name\" via POST request method");
			}
			$form = $model->getForm();
			$post = Yii::app()->getRequest()->getPost($name);
			unset($post["id"]);
			foreach ($post as $key => $value) {
				$model->$key = $value;
				$form->$key = $value;
			}
			$model->attributes = $post;
			$form->attributes = $post;
			$this->validateForm($form);
			$model->setAttributes($post);
			if (!$model->save()) {
				$this->leave([
					"message" => "Произошла ошибка при сохранении данных, данные не были сохранены",
					"status" => false
				]);
			}
			$this->leave([
				"message" => "Данные были успешно сохранены"
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Override that method to realize your own implementation
	 * of update method for guides with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionUpdate() {
		if (!Yii::app()->getRequest()->getIsPostRequest()) {
			throw new CHttpException(405, "Request method not allowed, requires POST");
		}
		if (!isset($_POST["id"])) {
			throw new CException("Can't resolve component's identification number \"id\" in POST request");
		}
		$model = $this->getModel();
		if (!$model instanceof GActiveRecord) {
			throw new CException("Model must be an instance of GActiveRecord class");
		}
		if (($name = $this->getModelName()) == null) {
			$name = get_class($model);
		}
		if (!isset($_POST[$name])) {
			throw new CException("Can't receive form's model \"$name\" via POST request method");
		}
		$model = $model->findByPk(
			$id = Yii::app()->getRequest()->getPost("id")
		);
		if (!$model) {
			throw new CException("Can't resolve row in \"" .$this->getModelName(). "\" with \"$id\" identification number");
		}
		$model->attributes = Yii::app()->getRequest()->getPost($name);
		if (!$model->save()) {
			$this->leave([
				"message" => "Произошла ошибка при обновлении данных, данные не были обновлены",
				"status" => false
			]);
		}
		$this->leave([
			"message" => "Данные были успешно сохранены"
		]);
	}

	/**
	 * Override that method to realize your own implementation
	 * of guide row deletion with your features
	 * @throws CHttpException
	 * @throws CException
	 */
	public function actionDelete() {
		if (!Yii::app()->getRequest()->getIsPostRequest()) {
			throw new CHttpException(405, "Request method not allowed, requires POST");
		}
		if (!$_POST["id"]) {
			throw new CException("Can't resolve component's identification number \"id\" in POST request");
		}
		if (!$this->getModel()->deleteByPk(Yii::app()->getRequest()->getPost("id"))) {
			$this->leave([
				"message" => "Произошла ошибка при удалении данных, данные не были удалены",
				"status" => false
			]);
		}
		$this->leave([
			"message" => "Данные были успешно удалены"
		]);
	}

	/**
	 * Default view action, it displays table
	 * with guide rows and all allowed actions
	 */
	public function actionView() {
		$this->actionIndex();
	}

	/**
	 * Terminate script execution with some json response
	 * @param array $result - Json response
	 */
	public function leave(array $result = []) {
		print json_encode($result + [
				"status" => true
			]);
		Yii::app()->end();
	}
}