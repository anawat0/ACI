<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_SUB_PROJ".
 *
 * @property  $IM_SUB_PROJ_ID
 * @property  $IM_PROJ_ID
 * @property  $SUB_PROJ_NAME_TH
 * @property  $SUB_PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMPROJ $1
 */
class IM_SUB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_SUB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_PROJ_ID', 'SUB_PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_PROJ_ID', 'BUDGET'], 'integer'],
            [['SUB_PROJ_NAME_TH', 'SUB_PROJ_NAME_EN'], 'string', 'max' => 255],
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
            'IM_SUB_PROJ_ID' => 'เลขที่่อ้างอิง ตารางแผนงาน/โครงการ ย่อย',
            'IM_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'SUB_PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ ย่อย (ไทย)',
            'SUB_PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ ย่อย (eng)',
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
    public function getImProj()
    {
        return $this->hasOne(IM_PROJ::className(), ['IM_PROJ_ID' => 'IM_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select im_sub_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
