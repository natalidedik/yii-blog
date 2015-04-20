<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $profile
 */
class User extends CActiveRecord {

    const ROLE_ADMIN = 'admin';
    const ROLE_MODER = 'moderator';
    const ROLE_USER = 'user';

    public $prevRole = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password, email', 'required'),
            array('username, password, email', 'length', 'max' => 128),
            array('profile, role', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, email, profile, role', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'profile' => 'Profile',
            'role' => 'Role',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('profile', $this->profile, true);
        $criteria->compare('role', $this->role, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * validatePassword function to check the pass, and the next is for hashing the pass
     */
    public function validatePassword($password) {
        return CPasswordHelper::verifyPassword($password, $this->password);
    }

    public function hashPassword($password) {
        return CPasswordHelper::hashPassword($password);
    }

//    public function beforeSave() {
//        parent::beforeSave();
//        $this->password = hashPassword($this->password);
//        /*
//         * Если пользователь не имеет права изменять роль, то мы должны
//         * установить роль по-умолчанию (user)
//         */
//        if (!Yii::app()->user->checkAccess('admin')) {
//            if ($this->isNewRecord) {
//                //ставим роль по-умолчанию user
//                $this->role = Users::ROLE_USER;
//            }
//        }
//        return true;
//    }
//
//    public function afterSave() {
//        parent::afterSave();
//        //связываем нового пользователя с ролью
//        $auth = Yii::app()->authManager;
//        //предварительно удаляем старую связь
//        $auth->revoke($this->prevRole, $this->id);
//        $auth->assign($this->role, $this->id);
//        $auth->save();
//        return true;
//    }
//
//    public function beforeDelete() {
//        parent::beforeDelete();
//        //убираем связь удаленного пользователя с ролью
//        $auth = Yii::app()->authManager;
//        $auth->revoke($this->role, $this->id);
//        $auth->save();
//        return true;
//    }
    
//        protected function beforeSave(){
//            $this->password = hashPassword($this->password);
//            return parent::beforeSave();
//        }

}
