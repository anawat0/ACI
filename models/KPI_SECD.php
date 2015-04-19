<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "KPI_SECD".
 *
 * @property  $KPI_SECD_ID
 * @property  $KPI_FST_ID
 * @property  $SECD_NAME_TH
 * @property  $SECD_NAME_EN
 * @property  $TARGET_SCORE
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property KPIREC[] $kPIRECs
 * @property KPITHRD[] $kPITHRDs
 * @property KPIFST $1
 */
class KPI_SECD extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'KPI_SECD';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KPI_FST_ID', 'SECD_NAME_TH', 'TARGET_SCORE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['KPI_FST_ID', 'TARGET_SCORE'], 'integer'],
            [['SECD_NAME_TH', 'SECD_NAME_EN'], 'string', 'max' => 255],
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
            'KPI_SECD_ID' => 'เลขที่อ้างอิง ตารางตัวชี้วัดรองของผลการปฏิบัติราชการ (ระดับที่ 2)',
            'KPI_FST_ID' => 'เลขที่อ้างอิง ตารางตัวชี้วัดผลการปฏิบัติราชการ (ระดับที่1)',
            'SECD_NAME_TH' => 'ชื่อตัวชี้วัดรองของผลการปฏิบัติราชการ (ระดับที่ 2) (ไทย)',
            'SECD_NAME_EN' => 'ชื่อตัวชี้วัดรองของผลการปฏิบัติราชการ (ระดับที่ 2) (eng)',
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
        return $this->hasMany(KPIREC::className(), ['1' => 'KPI_SECD_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKPITHRDs()
    {
        return $this->hasMany(KPITHRD::className(), ['1' => 'KPI_SECD_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(KPIFST::className(), ['KPI_FST_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select KPI_SECD_ID_SEQ.nextval as ID from dual");
	$result= $command->queryAll();
	return $result[0]['ID'];
    }    
    
}
