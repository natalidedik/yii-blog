<?php

class WebUser extends CWebUser {

    private $_model = null;

    public function getRole() {
        if ($user = $this->getModel()) {
            // в таблице User есть поле role
            // error_log($user->role);
            return $user->role;
        }
    }

    private function getModel() {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }

    public function checkAccess($operation, $params = array()) {
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        $role = $this->getRole();

        error_log($role);
        if ($role === 'admin') {
            return true; // admin role has access to everything
        }
        // allow access if the operation request is the current user's role
        if (is_array($operation)) {
            return in_array($role, $operation);
        }
        return ($operation === $role);
    }

}
