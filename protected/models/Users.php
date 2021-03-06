<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property integer $status
 * @property integer $date_add
 * @property integer $date_edit
 */
class Users extends CActiveRecord
{
        const AVATAR_PATH = '/images/users/avatar';
    
        public static $roles = array(1 => 'user', 2 => 'moderator', 3 => 'administrator');
        public static $statuses = array(1 => 'active', 2 => 'noactive', 3 => 'ban');
        
        public $repassword;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, email, status, date_add, date_edit, role', 'required', 'except' => array('regByApi', 'simpleRegistration')),
                        array('login, email', 'required', 'on' => array('regByApi')),
                        array('email', 'required', 'on' => array('simpleRegistration')),
                        array('password', 'required', 'on' => array('general_update')),
                        array('email', 'email'),
			array('status, date_add, date_edit', 'numerical', 'integerOnly'=>true),
			array('login, email', 'length', 'max'=>255),
			array('password', 'length', 'max'=>32),
                        array('repassword', 'compare', 'compareAttribute'=>'password', 'on'=>'general_update', 'message' => Yii::t('Site', 'Write right pass')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, password, email, status, date_add, date_edit, role', 'safe', 'on'=>'search'),
                        array('login, email', 'safe', 'on'=>'regByApi'),
                        array('password', 'safe', 'on'=>'general_update'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
                    'providers' => array(self::HAS_MANY, 'UserProviders', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => Yii::t('Site', 'Login'),
			'password' => Yii::t('Site', 'Password'),
			'email' => Yii::t('Site', 'Email'),
			'status' => Yii::t('Site', 'Status'),
			'date_add' => Yii::t('Site', 'Date Add'),
			'date_edit' => Yii::t('Site', 'Date Edit'),
                        'repassword' => Yii::t('Site', 'Repassword'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_add',$this->date_add);
		$criteria->compare('date_edit',$this->date_edit);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function beforeSave() {
            parent::beforeSave();
            if($this->isNewRecord){
                $this->role = array_search('user', self::$roles);
                $this->date_add = time();
                $this->date_edit = $this->date_add;
                $name = explode('@', $this->email);
                $pass = substr(md5($name[0] . time() . 'intwall was heare'), 8); // send to email
                $this->password = md5($pass);
                if($this->scenario == 'regByApi'){
                    $this->status = array_search('active', self::$statuses);
                } elseif($this->scenario == 'simpleRegistration'){
                    $this->status = array_search('noactive', self::$statuses);
                    $this->login = $name[0];
                }
            } else {
                if($this->password){
                    $this->password = md5($this->password);
                }
            }
            $this->email = strtolower($this->email);
            return true;
        }
}