<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_GROUP".
 *
 * @property  $WA_GROUP_ID
 * @property  $GROUP_NAME_TH
 * @property  $GROUP_NAME_EN
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 * @property  $LDAP_ORG_CODE
 *
 * @property WAGROUPROLE[] $wAGROUPROLEs
 * @property WAGROUPUSER[] $wAGROUPUSERs
 */
class WA_GROUP extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_GROUP';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['GROUP_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['GROUP_NAME_TH', 'GROUP_NAME_EN'], 'string', 'max' => 255],
            [['STATUS'], 'string', 'max' => 1],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WA_GROUP_ID' => 'Wa  Group  ID',
            'GROUP_NAME_TH' => 'ชื่อกลุ่ม',
            'GROUP_NAME_EN' => 'Group  Name  En',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
            'LDAP_ORG_CODE' => 'LDAP Mapping',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAGROUPROLEs()
    {
        return $this->hasMany(WAGROUPROLE::className(), ['1' => 'WA_GROUP_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAGROUPUSERs()
    {
        return $this->hasMany(WAGROUPUSER::className(), ['1' => 'WA_GROUP_ID']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select wa_group_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
