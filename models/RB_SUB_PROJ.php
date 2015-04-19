<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_SUB_PROJ".
 *
 * @property  $RB_SUB_PROJ_ID
 * @property  $RB_PROJ_ID
 * @property  $SUB_PROJ_NAME_TH
 * @property  $SUB_PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBPROJ $1
 * @property RBPROJLAUNRES[] $rBPROJLAUNRESs
 */
class RB_SUB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_SUB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RB_PROJ_ID', 'SUB_PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['RB_PROJ_ID'], 'integer'],
            [['SUB_PROJ_NAME_TH', 'SUB_PROJ_NAME_EN'], 'string', 'max' => 255],
            [['BUDGET'], 'string', 'max' => 10],
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
            'RB_SUB_PROJ_ID' => 'เลขที่่อ้างอิง ตารางแผนงาน/โครงการ ย่อย',
            'RB_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'SUB_PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ ย่อย (ไทย)',
            'SUB_PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ ย่อย (eng)',
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
    public function getRbProj()
    {
        return $this->hasOne(RB_PROJ::className(), ['RB_PROJ_ID' => 'RB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbProjLaunRess()
    {
        return $this->hasMany(RB_PROJ_LAUN_RES::className(), ['RB_SUB_PROJ_ID' => 'RB_SUB_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_sub_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
