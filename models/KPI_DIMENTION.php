<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "KPI_DIMENTION".
 *
 * @property  $KPI_DIMENTION_ID
 * @property  $BUDGET_YEAR
 * @property  $DIMENTION_NAME_TH
 * @property  $DIMENTION_NAME_EN
 * @property  $SEQ
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property KPIREC[] $kPIRECs
 * @property KPIFST[] $kPIFSTs
 */
class KPI_DIMENTION extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'KPI_DIMENTION';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DIMENTION_NAME_TH', 'BUDGET_YEAR', 'SEQ', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SEQ'], 'integer'],
            [['BUDGET_YEAR'], 'string', 'max' => 4],
            [['DIMENTION_NAME_TH', 'DIMENTION_NAME_EN'], 'string', 'max' => 255],
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
            'KPI_DIMENTION_ID' => 'เลขที่อ้างอิง ตารางมิติตัวชี้วัดผลการปฏิบัติราชการ',
            'BUDGET_YEAR' => 'ประจำปี',
            'DIMENTION_NAME_TH' => 'ชื่อมิติตัวชี้วัดผลการปฏิบัติราชการ (ไทย)',
            'DIMENTION_NAME_EN' => 'ชื่อมิติตัวชี้วัดผลการปฏิบัติราชการ (eng)',
            'SEQ' => 'ลำดับการแสดง',
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
        return $this->hasMany(KPIREC::className(), ['1' => 'KPI_DIMENTION_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKPIFSTs()
    {
        return $this->hasMany(KPIFST::className(), ['1' => 'KPI_DIMENTION_ID']);
    }
    
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select KPI_DIMENTION_ID_SEQ.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
