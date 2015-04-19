<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
// use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;

class UserLDAP implements IdentityInterface

{	
	public $username;
	public $password;
	public $email;
	public $LDAP_ORG_CODE;
	public $IS_LDAP_AUTHEN = true;
	public $USER_NAME_EN;
	public $EMAIL;
	public $PROVINCE_CODE;
	public $AMPHOE_CODE;
	
// 	$user->IS_LDAP_AUTHEN = true;
// 	$user->USER_NAME_EN = (isset($ldapinfo['email'])?$ldapinfo['email']:'');
// 	$user->EMAIL = (isset($ldapinfo['email'])?$ldapinfo['email']:'');
// 	$user->LDAP_ORG_CODE = (isset($ldapinfo['user_idx'])?$ldapinfo['user_idx']:'');
// 	$user->PROVINCE_CODE = (isset($ldapinfo['waProvince'][0]['PROVINCE_CODE'])?$ldapinfo['waProvince'][0]['PROVINCE_CODE']:'');
// 	$user->AMPHOE_CODE
	
	public static function findIdentity($id)
	{
		return self;
	}
	/* modified */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return self;
	}
	
	public function getId()
	{
		return 1;
	}
	
	public function getAuthKey()
	{
		return $this->EMAIL;
	}
	
	public function validateAuthKey($authKey)
	{
		//echo '<script type="text/javascript">alert("==validateAuthKey==");</script>'; exit();
		return true;
	}
}
