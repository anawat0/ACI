<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "WA_USER".
 *
 * @property  $WA_USER_ID
 * @property  $USER_NAME_TH
 * @property  $USER_NAME_EN
 * @property  $IMAGE_PATH
 * @property  $EMAIL
 * @property  $PASSWORD
 * @property  $ADDR
 * @property  $SOI
 * @property  $ROAD
 * @property  $TUMBOL
 * @property  $AMPHOE_CODE
 * @property  $PROVINCE_CODE
 * @property  $ZIPCODE
 * @property  $TEL_NO
 * @property  $FAX_NO
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAGROUPUSER[] $wAGROUPUSERs
 */
class WA_USER extends \yii\db\ActiveRecord

{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_USER';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['USER_NAME_TH', 'EMAIL', 'PASSWORD', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['USER_NAME_TH', 'USER_NAME_EN', 'TUMBOL', 'TEL_NO', 'FAX_NO', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['IMAGE_PATH'], 'string', 'max' => 150],
            [['EMAIL', 'ADDR'], 'string', 'max' => 100],
            [['PASSWORD'], 'string', 'max' => 35],
            [['SOI', 'ROAD'], 'string', 'max' => 30],
            [['AMPHOE_CODE'], 'string', 'max' => 4],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['ZIPCODE'], 'string', 'max' => 10],
            [['STATUS'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WA_USER_ID' => 'Wa  User  ID',
            'USER_NAME_TH' => 'ชื่อผู้ใช้',
            'USER_NAME_EN' => 'User  Name  En',
            'IMAGE_PATH' => 'Image  Path',
            'EMAIL' => 'อีเมล์',
            'PASSWORD' => 'รหัสผู้ใช้',
            'ADDR' => 'Addr',
            'SOI' => 'Soi',
            'ROAD' => 'Road',
            'TUMBOL' => 'Tumbol',
            'AMPHOE_CODE' => 'Amphoe  Code',
            'PROVINCE_CODE' => 'Province  Code',
            'ZIPCODE' => 'Zipcode',
            'TEL_NO' => 'Tel  No',
            'FAX_NO' => 'Fax  No',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }
    
    public function getWaGroupUsers()
    {
        return $this->hasMany(WA_GROUP_USER::className(), ['WA_USER_ID' => 'WA_USER_ID']);
    }
    
    
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select wa_user_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
