<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_NETWORK_YEAR".
 *
 * @property  $RB_NETWORK_YEAR_ID
 * @property  $YEAR
 * @property  $RB_NETWORK_ID
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBNETWORK $1
 */
class RB_NETWORK_YEAR extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_NETWORK_YEAR';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['YEAR', 'RB_NETWORK_ID', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['RB_NETWORK_ID'], 'integer'],
            [['YEAR'], 'string', 'max' => 4],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RB_NETWORK_YEAR_ID' => 'Rb  Network  Year  ID',
            'YEAR' => 'Year',
            'RB_NETWORK_ID' => 'Rb  Network  ID',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(RBNETWORK::className(), ['RB_NETWORK_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select RB_NETWORK_YEAR_ID_SEQ.nextval as ID from dual");
    	$result= $command->queryAll();
    	return $result[0]['ID'];
    }
}
