<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_PROJ_LAUN_RES_TARGET".
 *
 * @property  $IM_PROJ_LAUH_RES_TARGET_ID
 * @property  $IM_PROJ_LAUN_RES_ID
 * @property  $IM_PROJ_LAUN_TARGET_ID
 * @property  $TARGET_QT
 * @property  $ACTUAL_QT
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMPROJLAUNTARGET $1
 * @property IMPROJLAUNRES $10
 */
class IM_PROJ_LAUN_RES_TARGET extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_PROJ_LAUN_RES_TARGET';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_PROJ_LAUN_RES_ID', 'IM_PROJ_LAUN_TARGET_ID', 'TARGET_QT', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_PROJ_LAUN_RES_ID', 'IM_PROJ_LAUN_TARGET_ID'], 'integer'],
            [['TARGET_QT', 'ACTUAL_QT'], 'string', 'max' => 10],
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
            'IM_PROJ_LAUH_RES_TARGET_ID' => 'เลขที่อ้างอิง ตารางผลการดำเนินงานกลุ่มเป้าหมาย ของแผนงาน-โครงการ ',
            'IM_PROJ_LAUN_RES_ID' => 'เลขที่อ้างอิง ตารางผลการดำเนินแผนงาน-โครงการ ',
            'IM_PROJ_LAUN_TARGET_ID' => 'เลขที่อ้างอิง ตารางกลุ่มเป้าหมายของ แผนงาน-โครงการ ที่ดำเนินการ ',
            'TARGET_QT' => 'เป้าหมาย',
            'ACTUAL_QT' => 'ดำเนินการจริง',
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
    public function getImProjLaunTarget()
    {
        return $this->hasOne(IM_PROJ_LAUN_TARGET::className(), ['IM_PROJ_LAUN_TARGET_ID' => 'IM_PROJ_LAUN_TARGET_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLaunRes()
    {
        return $this->hasOne(IM_PROJ_LAUN_RES::className(), ['IM_PROJ_LAUN_RES_ID' => 'IM_PROJ_LAUN_RES_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select im_proj_laun_res_target_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
