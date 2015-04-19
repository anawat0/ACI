<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "KPI_FRTH".
 *
 * @property  $KPI_FRTH_ID
 * @property  $KPI_THRD_ID
 * @property  $FRTH_NAME_TH
 * @property  $FRTH_NAME_EN
 * @property  $TARGET_SCORE
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property KPIREC[] $kPIRECs
 * @property KPITHRD $1
 */
class KPI_FRTH extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'KPI_FRTH';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KPI_THRD_ID', 'FRTH_NAME_TH', 'TARGET_SCORE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['KPI_THRD_ID', 'TARGET_SCORE'], 'integer'],
            [['FRTH_NAME_TH', 'FRTH_NAME_EN'], 'string', 'max' => 255],
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
            'KPI_FRTH_ID' => 'เลขที่อ้างอิง ตารางตัวชี้วัดแยกย่อยของผลการปฏิบัติราชการ (ระดับที่ 4)',
            'KPI_THRD_ID' => 'เลขที่อ้างอิง ตารางตัวชี้วัดย่อยของผลการปฏิบัติราชการ (ระดับที่ 3)',
            'FRTH_NAME_TH' => 'ชื่อตัวชี้วัดแยกย่อยของผลการปฏิบัติราชการ (ระดับที่ 4) (ไทย)',
            'FRTH_NAME_EN' => 'ชื่อตัวชี้วัดแยกย่อยของผลการปฏิบัติราชการ (ระดับที่ 4) (eng)',
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
        return $this->hasMany(KPIREC::className(), ['1' => 'KPI_FRTH_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(KPITHRD::className(), ['KPI_THRD_ID' => '1']);
    }
    
    public static function getNewID(){        
    	$command = Yii::$app->db->createCommand("select KPI_FRTH_ID_SEQ.nextval as ID from dual");
	$result= $command->queryAll();
	return $result[0]['ID'];
    }    
}
