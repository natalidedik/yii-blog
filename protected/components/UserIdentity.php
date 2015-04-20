<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    
    private $_id;
    
    public function authenticate() {
        $username = strtolower($this->username); // get the user name in lowercased letters
        $user = User::model()->find('LOWER(username)=?',array($username)); //find in database the user with that username
        if($user===null) { //if there is no such user
            $this->errorCode=self::ERROR_USERNAME_INVALID; // error - not such username
        } else if (!$user->validatePassword($this->password)){ //if the pass is invalid
            $this->errorCode=self::ERROR_PASSWORD_INVALID; // error - invalid pass
        } else {
            $this->_id=$user->id; // take user id
            $this->username=$user->username; // take user name
            //var_dump($this->_username);die;
            $this->errorCode=self::ERROR_NONE; // no errors
        }
        return $this->errorCode==self::ERROR_NONE; // return no errors
    }
    
    public function getId() { // возвращает значение id пользователя, найденного в таблице tbl_user
        return $this->_id;
    }
   
}

