<?php

class AnalysisType extends GActiveRecord {

	/**
	 * Override that method to return form model instance, it will
	 * help search method to implement automatic search, you can
	 * use [createFilter] method or override [getAttributes] to filter
	 * displayable attributes
	 *
	 * @return FormModel - Form model instance for current table model
	 *
	 * @see FormModel::createFilter - For backward method, it will ignore hidden fields
	 * @see FormModel::backward - Same as [CFormModel::rules], but with 'hide' property
	 */
	public function getForm() {
		return new AnalysisTypeForm();
	}

	public function tableName() {
		return "lis.analysis_types";
	}
}