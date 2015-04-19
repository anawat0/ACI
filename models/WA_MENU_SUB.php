<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_MENU_SUB".
 *
 * @property  $WA_MENU_SUB_ID
 * @property  $WA_MENU_MAIN
 * @property  $MENU_SUB_NAME_TH
 * @property  $MENU_SUB_NAME_EN
 * @property  $DESCRIPTION
 * @property  $MENU_LINK
 * @property  $SEQ
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAGROUPROLE[] $wAGROUPROLEs
 * @property WAMENUMAIN $1
 */
class WA_MENU_SUB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_MENU_SUB';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WA_MENU_MAIN', 'MENU_SUB_NAME_TH', 'SEQ', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['WA_MENU_MAIN', 'SEQ'], 'integer'],
            [['MENU_SUB_NAME_TH', 'MENU_SUB_NAME_EN', 'DESCRIPTION', 'MENU_LINK'], 'string', 'max' => 255],
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
            'WA_MENU_SUB_ID' => 'Wa  Menu  Sub  ID',
            'WA_MENU_MAIN' => 'Wa  Menu  Main',
            'MENU_SUB_NAME_TH' => 'ชื่อเมนูย่อย',
            'MENU_SUB_NAME_EN' => 'Menu  Sub  Name  En',
            'DESCRIPTION' => 'Description',
            'MENU_LINK' => 'Path',
            'SEQ' => 'ลำดับแสดง',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAGROUPROLEs()
    {
        return $this->hasMany(WAGROUPROLE::className(), ['1' => 'WA_MENU_SUB_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(WAMENUMAIN::className(), ['WA_MENU_MAIN_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select menu_sub_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
