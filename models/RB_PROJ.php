<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_PROJ".
 *
 * @property  $RB_PROJ_ID
 * @property  $RB_MAIN_PROJ_ID
 * @property  $PROJ_NAME_TH
 * @property  $PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $S_PROJ_STATUS
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBSUBPROJ[] $rBSUBPROJs
 * @property RBPROJLAUN[] $rBPROJLAUNs
 * @property RBMAINPROJ $1
 */
class RB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RB_MAIN_PROJ_ID', 'PROJ_NAME_TH', 'S_PROJ_STATUS', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['RB_MAIN_PROJ_ID'], 'integer'],
            [['PROJ_NAME_TH', 'PROJ_NAME_EN'], 'string', 'max' => 255],
            [['BUDGET'], 'string', 'max' => 10],
            [['S_PROJ_STATUS', 'STATUS'], 'string', 'max' => 1],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RB_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'RB_MAIN_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการหลัก',
            'PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ (ไทย)',
            'PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ (eng)',
            'BUDGET' => 'งบประมาณ',
            'S_PROJ_STATUS' => 'S  Proj  Status',
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
    public function getRbSubProjs()
    {
        return $this->hasMany(RB_SUB_PROJ::className(), ['RB_PROJ_ID' => 'RB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbProjLauns()
    {
        return $this->hasMany(RB_PROJ_LAUN::className(), ['RB_PROJ_ID' => 'RB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbMainProj()
    {
        return $this->hasOne(RB_MAIN_PROJ::className(), ['RB_MAIN_PROJ_ID' => 'RB_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
