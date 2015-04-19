<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_PROJ_LAUN_RES".
 *
 * @property  $IM_PROJ_LAUN_RES_ID
 * @property  $IM_PROJ_LAUN_ID
 * @property  $MONTH
 * @property  $BUDGET_YEAR
 * @property  $TARGET_FROM
 * @property  $TARGET_TO
 * @property  $ACTUAL_FROM
 * @property  $ACTUAL_TO
 * @property  $TARGET_PLACE
 * @property  $ACTUAL_PLACE
 * @property  $TARGET_BUDGET
 * @property  $ACTUAL_BUDGET
 * @property  $TARGET_SATISFACTION
 * @property  $ACTUAL_SATISFACTION
 * @property  $IMAGE_PATH
 * @property  $SUM_RESULT
 * @property  $RECOMM
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMPROJLAUNRESTARGET[] $iMPROJLAUNRESTARGETs
 * @property IMPROJLAUN $1
 */
class IM_PROJ_LAUN_RES extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_PROJ_LAUN_RES';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_PROJ_LAUN_ID', 'MONTH', 'BUDGET_YEAR', 'TARGET_FROM', 'TARGET_TO', 'ACTUAL_FROM', 'ACTUAL_TO', 'TARGET_PLACE', 'ACTUAL_PLACE', 'TARGET_BUDGET', 'ACTUAL_BUDGET', 'TARGET_SATISFACTION', 'ACTUAL_SATISFACTION', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_PROJ_LAUN_ID', 'MONTH', 'TARGET_BUDGET', 'ACTUAL_BUDGET', 'TARGET_SATISFACTION', 'ACTUAL_SATISFACTION'], 'integer'],
            [['BUDGET_YEAR'], 'string', 'max' => 4],
            [['TARGET_PLACE', 'ACTUAL_PLACE', 'IMAGE_PATH', 'SUM_RESULT', 'RECOMM'], 'string', 'max' => 255],
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
            'IM_PROJ_LAUN_RES_ID' => 'เลขที่อ้างอิง ตารางผลการดำเนินแผนงาน-โครงการ ',
            'IM_PROJ_LAUN_ID' => 'แผนงาน-โครงการ',
            'MONTH' => 'เดือน',
            'BUDGET_YEAR' => 'ปีงบประมาณ',
            'TARGET_FROM' => 'ระยะเวลาเริ่มดำเนินงาน เป้าหมาย',
            'TARGET_TO' => 'ระยะเวลาสิ้นสุดดำเนินงาน เป้าหมาย',
            'ACTUAL_FROM' => 'ระยะเวลาเริ่มดำเนินงานจริง',
            'ACTUAL_TO' => 'ระยะเวลาสิ้นสุดดำเนินงานจริง',
            'TARGET_PLACE' => 'สถานที่ดำเนินงานเป้าหมาย',
            'ACTUAL_PLACE' => 'สถานที่ดำเนินงานจริง',
            'TARGET_BUDGET' => 'งบประมาณเป้าหมาย',
            'ACTUAL_BUDGET' => 'งบประมาณจริง',
            'TARGET_SATISFACTION' => 'ระดับความพึงพอใจ เป้าหมาย',
            'ACTUAL_SATISFACTION' => 'ระดับความพึงพอใจจริง',
            'IMAGE_PATH' => 'รูปกิจกรรม',
            'SUM_RESULT' => 'สรุปผลการดำเนินงาน',
            'RECOMM' => 'ข้อเสนอแนะ',
            'STATUS' => 'สถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
            'HEADER1' => 'รายละเอียดผลการดำเนินแผนงาน-โครงการ',
            'HEADER2' => 'รายละเอียดกลุ่มเป้าหมาย',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLaunResTargets()
    {
        return $this->hasMany(IM_PROJ_LAUN_RES_TARGET::className(), ['IM_PROJ_LAUN_RES_ID' => 'IM_PROJ_LAUN_RES_ID']);
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
        $command = Yii::$app->db->createCommand("select im_proj_laun_res_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
