<?php

class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticateApi() {
        $record = User::model()->findByAttributes(array('login' => $this->username));
        if ($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else
        if ($record->password !== md5(md5($this->password))) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
        }
        Yii::app()->user->setState('id', $record->id);
        Yii::app()->user->setState('roleId', $record->id);
        Yii::app()->user->setState('login', $record->login);
        Yii::app()->user->setState('password', $this->password);
        Yii::app()->user->setState('startpageUrl', '');
        return $this->errorCode == self::ERROR_NONE;
    }

    public function authenticateStep1($skipSecondStep = false) {
        $record = User::model()->findByAttributes(array('login' => $this->username));
        if ($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else
        if ($record->password !== md5(md5($this->password))) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
        }

        $roles = RoleToUser::model()->findAllRolesByUser($record->id);
        $currentPriority = -1;
        $url = '';
        foreach ($roles as $role) {
            if ($currentPriority < $role['priority']) {
                $currentPriority = $role['priority'];
                $url = $role['url'];
            }
        }
        Yii::app()->user->setState('id', $record->id);
        Yii::app()->user->setState('roleId', $roles);
        Yii::app()->user->setState('login', $record->login);
        Yii::app()->user->setState('password', $this->password);
        Yii::app()->user->setState('startpageUrl', $url);
        if (isset($_SESSION['fontSize'])) {
            Yii::app()->user->setState('fontSize', $_SESSION['fontSize']);
        } else {
            Yii::app()->user->setState('fontSize', 12);
        }
        // Проверяем, сколько сотрудников прикреплено к пользователю. Если больше одного - выводить окно для выбора сотрудника в методе на уровень выше	
        $numEmployeesToUser = count(Doctor::model()->findAll('user_id = :user_id', array(':user_id' => $record->id)));
        if ($numEmployeesToUser == 1 && $skipSecondStep == false) { // Если всего один, то сразу вынимать все данные
            $this->authenticateStep2($record, Doctor::model()->find('user_id = :user_id', array(':user_id' => $record->id)));
        }

        return !$this->errorCode;
    }

    public function authenticateStep2($record = false, $employeeForm = false) {
        if ($record === false) {
            $record = User::model()->findByAttributes(array('login' => $this->username));
        }
        $this->_id = $record->id;
        $employee = Doctor::model()->findByPk($employeeForm->id);
        if ($employee != null) {
            $ward = Ward::model()->findByPk($employee->ward_code);
        } else {
            $ward = null;
        }
        $post=Post::model()->findByPk($employee->post_id);
        $enterprise=$ward?Enterprise::model()->findByPk($ward->enterprise_id):null;
        	
        
        // Данные юзера
        Yii::app()->user->setState('username', $record->username);
        Yii::app()->user->setState('doctorId', $employee->id);
        Yii::app()->user->setState('medworkerId', $employee->post_id);
        Yii::app()->user->setState('enterprise', $enterprise);
        Yii::app()->user->setState('post', $post);
        Yii::app()->user->setState('enterpriseId', $ward != null ? $ward->enterprise_id : null);
        Yii::app()->user->setState('fio', $employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name);
        Yii::app()->user->setState('medcardGenRuleId', $ward != null ? $ward->rule_id : null);
        Yii::app()->user->setState('authStep', -1);
        return true;
    }

    public function authenticateInOneStep() {

        $record = User::model()->findByAttributes(array(
            'password' => md5(md5($this->password)),
            'login' => $this->username
        ));

        if ($record === null) {

            $this->errorCode = self::ERROR_USERNAME_INVALID;
            $this->errorCode = self::ERROR_PASSWORD_INVALID;

            return false;
        }

        $this->_id = $record->id;

        $employee = Doctor::model()->findByPk($record->employee_id);
        $roles = RoleToUser::model()->findAllRolesByUser($record->id);

        if ($employee != null) {
            $ward = Ward::model()->findByPk($employee->ward_code);
        } else {
            $ward = null;
        }

        $currentPriority = -1;
        $url = '';

        foreach ($roles as $role) {
            if ($currentPriority < $role['priority']) {
                $currentPriority = $role['priority'];
                $url = $role['url'];
            }
        }

        // Данные юзера
        $this->setState('login', $record->login);
        $this->setState('id', $record->id);
        $this->setState('username', $record->username);
        $this->setState('roleId', $roles);
        $this->setState('doctorId', $employee->id);
        $this->setState('medworkerId', $employee->post_id);
        $this->setState('enterpriseId', $ward != null ? $ward->enterprise_id : null);
        $this->setState('fio', $employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name);
        $this->setState('startpageUrl', $url);
        $this->setState('medcardGenRuleId', $ward != null ? $ward->rule_id : null);

        if (isset($_SESSION['fontSize'])) {
            $this->setState('fontSize', $_SESSION['fontSize']);
        } else {
            $this->setState('fontSize', 12);
        }

        $this->errorCode = self::ERROR_NONE;

        return !$this->errorCode;
    }

	public function authenticate() {
        $record = User::model()->findByAttributes(array('login' => $this->username));

        if ($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($record->password !== md5(md5($this->password))) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $record->id;
            $employee = Doctor::model()->findByPk($record->employee_id);
            $roles = RoleToUser::model()->findAllRolesByUser($record->id);
            if ($employee != null) {
                $ward = Ward::model()->findByPk($employee->ward_code);
            } else {
                $ward = null;
            }
            $currentPriority = -1;
            $url = '';
            foreach ($roles as $role) {
                if ($currentPriority < $role['priority']) {
                    $currentPriority = $role['priority'];
                    $url = $role['url'];
                }
            }

            // Данные юзера
            $this->setState('login', $record->login);
            $this->setState('id', $record->id);
            $this->setState('username', $record->username);
            $this->setState('roleId', $roles);
            $this->setState('doctorId', $employee->id);
            $this->setState('medworkerId', $employee->post_id);
            $this->setState('enterpriseId', $ward != null ? $ward->enterprise_id : null);
            $this->setState('fio', $employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name);
            $this->setState('startpageUrl', $url);
            $this->setState('medcardGenRuleId', $ward != null ? $ward->rule_id : null);
            if (isset($_SESSION['fontSize'])) {
                $this->setState('fontSize', $_SESSION['fontSize']);
            } else {
                $this->setState('fontSize', 12);
            }

            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function wrongLogin()  {
        return ($this->errorCode == self::ERROR_USERNAME_INVALID);
    }

    public function wrongPassword() {
        return ($this->errorCode == self::ERROR_PASSWORD_INVALID);
    }

}

?>