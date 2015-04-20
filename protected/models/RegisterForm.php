<?php

/**
 * RegisterForm class.
 */
class RegisterForm extends CFormModel {

    public $username;
    public $password;
    public $email;
    public $profile;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password, email', 'required'),
            array('email, username', 'unique', 'message'=>'Email already exists!', 'className' => 'User'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username'=>'Your Username',
            'email'=>'Your Email Address',
            'password'=>'Your Password',
            'profile'=>'Few Words About You (not necessary) ',
        );        
    }
   
}
