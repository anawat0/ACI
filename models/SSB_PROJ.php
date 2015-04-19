<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SSB_PROJ".
 *
 * @property  $SSB_PROJ_ID
 * @property  $SSB_MAIN_PROJ_ID
 * @property  $PROJ_NAME_TH
 * @property  $PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SSBPROJLAUN[] $sSBPROJLAUNs
 * @property SSBSUBPROJ[] $sSBSUBPROJs
 * @property SSBMAINPROJ $1
 */
class SSB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SSB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SSB_MAIN_PROJ_ID', 'PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SSB_MAIN_PROJ_ID', 'BUDGET'], 'integer'],
            [['PROJ_NAME_TH', 'PROJ_NAME_EN'], 'string', 'max' => 255],
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
            'SSB_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'SSB_MAIN_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการหลัก',
            'PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ (ไทย)',
            'PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ (eng)',
            'BUDGET' => 'งบประมาณ',
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
    public function getSSBPROJLAUNs()
    {
        return $this->hasMany(SSBPROJLAUN::className(), ['1' => 'SSB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsbSubProjs()
    {
        return $this->hasMany(SSB_SUB_PROJ::className(), ['SSB_PROJ_ID' => 'SSB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsbMainProj()
    {
        return $this->hasOne(SSB_MAIN_PROJ::className(), ['SSB_MAIN_PROJ_ID' => 'SSB_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select ssb_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
