<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_SECTION".
 *
 * @property  $WA_SECTION_ID
 * @property  $WA_DEPARTMENT_ID
 * @property  $SECTION_NAME_TH
 * @property  $SECTION_NAME_EN
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 * @property  $DEPT_IDX
 * @property  $CC_IDX
 *
 * @property WADEPARTMENT $1
 * @property SBPROJLAUN[] $sBPROJLAUNs
 * @property RBPROJLAUN[] $rBPROJLAUNs
 * @property SSBPROJLAUN[] $sSBPROJLAUNs
 * @property IMPROJLAUN[] $iMPROJLAUNs
 */
class WA_SECTION extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_SECTION';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WA_DEPARTMENT_ID', 'SECTION_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['WA_DEPARTMENT_ID'], 'integer'],
            [['SECTION_NAME_TH'], 'string', 'max' => 150],
            [['SECTION_NAME_EN'], 'string', 'max' => 100],
            [['STATUS'], 'string', 'max' => 1],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['DEPT_IDX', 'CC_IDX'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WA_SECTION_ID' => 'เลขที่อ้างอิง ตารางส่วนงาน',
            'WA_DEPARTMENT_ID' => 'เลขที่อ้างอิง ตารางแผนกงาน',
            'SECTION_NAME_TH' => 'ชื่อส่วนงาน (ไทย)',
            'SECTION_NAME_EN' => 'ชื่อส่วนงาน (en)',
            'STATUS' => 'สถานะสถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
            'DEPT_IDX' => 'รหัสแผนกงาน ระบบเดิม',
            'CC_IDX' => 'เลขที่อ้างอิง ตารางส่วนงาน ระบบเดิม',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(WADEPARTMENT::className(), ['WA_DEPARTMENT_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSBPROJLAUNs()
    {
        return $this->hasMany(SBPROJLAUN::className(), ['1' => 'WA_SECTION_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRBPROJLAUNs()
    {
        return $this->hasMany(RBPROJLAUN::className(), ['1' => 'WA_SECTION_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSSBPROJLAUNs()
    {
        return $this->hasMany(SSBPROJLAUN::className(), ['1' => 'WA_SECTION_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIMPROJLAUNs()
    {
        return $this->hasMany(IMPROJLAUN::className(), ['1' => 'WA_SECTION_ID']);
    }
}
