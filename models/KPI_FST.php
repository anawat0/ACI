<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "KPI_FST".
 *
 * @property  $KPI_FST_ID
 * @property  $KPI_DIMENTION_ID
 * @property  $FST_NAME_TH
 * @property  $FST_NAME_EN
 * @property  $TARGET_SCORE
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property KPIREC[] $kPIRECs
 * @property KPISECD[] $kPISECDs
 * @property KPIDIMENTION $1
 */
class KPI_FST extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'KPI_FST';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KPI_DIMENTION_ID', 'FST_NAME_TH', 'TARGET_SCORE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['KPI_DIMENTION_ID', 'TARGET_SCORE'], 'integer'],
            [['FST_NAME_TH', 'FST_NAME_EN'], 'string', 'max' => 255],
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
            'KPI_FST_ID' => 'เลขที่อ้างอิง ตารางตัวชี้วัดผลการปฏิบัติราชการ (ระดับที่1)',
            'KPI_DIMENTION_ID' => 'เลขที่อ้างอิง ตารางมิติตัวชี้วัดผลการปฏิบัติราชการ',
            'FST_NAME_TH' => 'ชื่อตัวชี้วัดผลการปฏิบัติราชการ (ระดับที่1) (ไทย)',
            'FST_NAME_EN' => 'ชื่อตัวชี้วัดผลการปฏิบัติราชการ (ระดับที่1) (eng)',
            'TARGET_SCORE' => 'น้ำหนัก (ร้อยละ)',
            'STATUS' => 'สถานะสถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKPIRECs()
    {
        return $this->hasMany(KPIREC::className(), ['1' => 'KPI_FST_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKPISECDs()
    {
        return $this->hasMany(KPISECD::className(), ['1' => 'KPI_FST_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(KPIDIMENTION::className(), ['KPI_DIMENTION_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select KPI_FST_ID_SEQ.nextval as ID from dual");
	$result= $command->queryAll();
	return $result[0]['ID'];
    }    
    
    
}
