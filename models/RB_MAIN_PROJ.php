<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_MAIN_PROJ".
 *
 * @property  $RB_MAIN_PROJ_ID
 * @property  $MAIN_PROJ_NAME_TH
 * @property  $MAIN_PROJ_NAME_EN
 * @property  $BUDGET_YEAR
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBPROJ[] $rBPROJs
 */
class RB_MAIN_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_MAIN_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MAIN_PROJ_NAME_TH', 'BUDGET_YEAR', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['MAIN_PROJ_NAME_TH', 'MAIN_PROJ_NAME_EN'], 'string', 'max' => 255],
            [['BUDGET_YEAR'], 'string', 'max' => 4],
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
            'RB_MAIN_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการหลัก',
            'MAIN_PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการหลัก (ไทย)',
            'MAIN_PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการหลัก (eng)',
            'BUDGET_YEAR' => 'ประจำปี',
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
    public function getRbProjs()
    {
        return $this->hasMany(RB_PROJ::className(), ['RB_MAIN_PROJ_ID' => 'RB_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select rb_main_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
