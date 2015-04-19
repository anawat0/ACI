<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_PROJ_LAUN_TARGET".
 *
 * @property  $IM_PROJ_LAUN_TARGET_ID
 * @property  $IM_PROJ_LAUN_ID
 * @property  $TARGET_NAME_TH
 * @property  $TARGET_NAME_EN
 * @property  $TARGET_AMOUNT
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMPROJLAUNRESTARGET[] $iMPROJLAUNRESTARGETs
 * @property IMPROJLAUN $1
 */
class IM_PROJ_LAUN_TARGET extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_PROJ_LAUN_TARGET';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_PROJ_LAUN_ID', 'TARGET_NAME_TH', 'TARGET_AMOUNT', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_PROJ_LAUN_ID', 'TARGET_AMOUNT'], 'integer'],
            [['TARGET_NAME_TH', 'TARGET_NAME_EN'], 'string', 'max' => 255],
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
            'IM_PROJ_LAUN_TARGET_ID' => 'เลขที่อ้างอิง ตารางกลุ่มเป้าหมายของ แผนงาน-โครงการ ที่ดำเนินการ ',
            'IM_PROJ_LAUN_ID' => 'เลขที่อ้างอิง ตารางแผนงาน-โครงการ ที่ดำเนินการ ',
            'TARGET_NAME_TH' => 'ชื่อเป้าหมาย (ไทย)',
            'TARGET_NAME_EN' => 'ชื่อเป้าหมาย (eng)',
            'TARGET_AMOUNT' => 'เป้าหมายทั้งโครงการ',
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
    public function getImProjLaunResTargets()
    {
        return $this->hasMany(IM_PROJ_LAUN_RES_TARGET::className(), ['IM_PROJ_LAUN_TARGET_ID' => 'IM_PROJ_LAUN_TARGET_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLaun()
    {
        return $this->hasOne(IM_PROJ_LAUN::className(), ['IM_PROJ_LAUN_ID' => 'IM_PROJ_LAUN_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select im_proj_laun_target_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
