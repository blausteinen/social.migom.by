<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('email, password', 'required', 'message' => Yii::t('Site', 'Cannot be blank')),
                        array('email', 'email', 'message' => Yii::t('Site', 'write right')),
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
                        'email' => Yii::t('Site', 'E-mail'),
                        'password' => Yii::t('Site', 'Password'),
			'rememberMe'=>Yii::t('Site', 'Stay signed in'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->email,md5($this->password));
			if(!$this->_identity->authenticate()){
                            if($this->_identity->errorCode == UserIdentity::ERROR_USER_BLOCKED){
                                $this->addError('password', Yii::t('Site', 'User with this email was banned.'));
                            } else {
                                $this->addError('password', Yii::t('Site', 'Incorrect email or password.'));
                            }
                        }
				
		}
	}

	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
