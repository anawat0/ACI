<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_PROJ".
 *
 * @property  $IM_PROJ_ID
 * @property  $IM_MAIN_PROJ_ID
 * @property  $PROJ_NAME_TH
 * @property  $PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMSUBPROJ[] $iMSUBPROJs
 * @property IMPROJLAUN[] $iMPROJLAUNs
 * @property IMMAINPROJ $1
 */
class IM_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_MAIN_PROJ_ID', 'PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_MAIN_PROJ_ID', 'BUDGET'], 'integer'],
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
            'IM_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'IM_MAIN_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการหลัก',
            'PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ (ไทย)',
            'PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ (eng)',
            'BUDGET' => 'งบประมาณ',
            'STATUS' => 'สถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImSubProjs()
    {
        return $this->hasMany(IM_SUB_PROJ::className(), ['IM_PROJ_ID' => 'IM_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLauns()
    {
        return $this->hasMany(IM_PROJ_LAUN::className(), ['IM_PROJ_ID' => 'IM_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImMainProj()
    {
        return $this->hasOne(IM_MAIN_PROJ::className(), ['IM_MAIN_PROJ_ID' => 'IM_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select im_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
