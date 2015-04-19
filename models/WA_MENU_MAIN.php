<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_MENU_MAIN".
 *
 * @property  $WA_MENU_MAIN_ID
 * @property  $MENU_MAIN_NAME_TH
 * @property  $MENU_MAIN_NAME_EN
 * @property  $DESCRIPTION
 * @property  $STATUS
 * @property  $SEQ
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAMENUSUB[] $wAMENUSUBs
 */
class WA_MENU_MAIN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_MENU_MAIN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MENU_MAIN_NAME_TH', 'STATUS', 'SEQ', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SEQ'], 'integer'],
            [['MENU_MAIN_NAME_TH', 'MENU_MAIN_NAME_EN', 'DESCRIPTION'], 'string', 'max' => 255],
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
            'WA_MENU_MAIN_ID' => 'Wa  Menu  Main  ID',
            'MENU_MAIN_NAME_TH' => 'ชื่อเมนู',
            'MENU_MAIN_NAME_EN' => 'Menu  Main  Name  En',
            'DESCRIPTION' => 'รายละเอียด',
            'STATUS' => 'สถานะ',
            'SEQ' => 'ลำดับ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAMENUSUBs()
    {
        return $this->hasMany(WAMENUSUB::className(), ['1' => 'WA_MENU_MAIN_ID']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select menu_main_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
