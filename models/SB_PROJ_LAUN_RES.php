<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN_RES".
 *
 * @property  $SB_PROJ_LAUN_RES_ID
 * @property  $SB_PROJECT_LAUNCH_ID
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $MONTH
 * @property  $TARGET_FROM
 * @property  $BUDGET_YEAR
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
 * @property SBPROJLAUNRESTARGET[] $sBPROJLAUNRESTARGETs
 */
class SB_PROJ_LAUN_RES extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN_RES';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJECT_LAUNCH_ID', 'PROVINCE_CODE', 'TARGET_FROM', 'BUDGET_YEAR', 'TARGET_TO', 'ACTUAL_FROM', 'ACTUAL_TO', 'TARGET_PLACE', 'ACTUAL_PLACE', 'TARGET_BUDGET', 'ACTUAL_BUDGET', 'TARGET_SATISFACTION', 'ACTUAL_SATISFACTION', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PROJECT_LAUNCH_ID', 'MONTH', 'TARGET_SATISFACTION', 'ACTUAL_SATISFACTION', 'TARGET_BUDGET', 'ACTUAL_BUDGET'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_CODE', 'BUDGET_YEAR'], 'string', 'max' => 4],
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
            'SB_PROJ_LAUN_RES_ID' => 'Sb  Proj  Laun  Res  ID',
            'SB_PROJECT_LAUNCH_ID' => 'โครงการ ที่ดำเนินการ',
            'PROVINCE_CODE' => 'จังหวัด',
            'AMPHOE_CODE' => 'อำเภอ',
            'MONTH' => 'เดือน',
            'TARGET_FROM' => 'ระยะเวลาเริ่มดำเนินงาน เป้าหมาย',
            'BUDGET_YEAR' => 'ปีงบประมาณ',
            'TARGET_TO' => 'ระยะเวลาสิ้นสุดดำเนินงาน เป้าหมาย',
            'ACTUAL_FROM' => 'ระยะเวลาเริ่มดำเนินงานจริง',
            'ACTUAL_TO' => 'ระยะเวลาสิ้นสุดดำเนินงานจริง',
            'TARGET_PLACE' => 'สถานที่ดำเนินงานเป้าหมาย',
            'ACTUAL_PLACE' => 'สถานที่ดำเนินงานจริง',
            'TARGET_BUDGET' => 'งบประมาณเป้าหมาย',
            'ACTUAL_BUDGET' => 'งบประมาณจริง',
            'TARGET_SATISFACTION' => 'ระดับความพึงพอใจ เป้าหมาย %',
            'ACTUAL_SATISFACTION' => 'ระดับความพึงพอใจจริง %',
            'IMAGE_PATH' => 'รูปกิจกรรม',
            'SUM_RESULT' => 'สรุปผลการดำเนินงาน',
            'RECOMM' => 'ข้อเสนอแนะ',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
            'HEADER1' => 'รายละเอียดผลการดำเนินแผนงาน-โครงการ',
            'HEADER2' => 'รายละเอียดกลุ่มเป้าหมาย'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunResTargets()
    {
        return $this->hasMany(SB_PROJ_LAUN_RES_TARGET::className(), ['SB_PROJ_LAUN_RES_ID' => 'SB_PROJ_LAUN_RES_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaun()
    {
        return $this->hasOne(SB_PROJ_LAUN::className(), ['SB_PROJ_LAUN_ID' => 'SB_PROJECT_LAUNCH_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaProvince()
    {
        return $this->hasOne(WA_PROVINCE::className(), ['PROVINCE_CODE' => 'PROVINCE_CODE']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaAmphoe()
    {
        return $this->hasOne(WA_AMPHOE::className(), ['AMPHOE_CODE' => 'AMPHOE_CODE']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
