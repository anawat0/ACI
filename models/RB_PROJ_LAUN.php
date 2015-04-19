<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_PROJ_LAUN".
 *
 * @property  $RB_PROJ_LAUN_ID
 * @property  $RB_PROJ_ID
 * @property  $WA_SECTION_ID
 * @property  $PROJ_RESPONSIBLE
 * @property  $FROM_DATE
 * @property  $TO_DATE
 * @property  $BUDGET
 * @property  $SATISFACTION_LEVEL
 * @property  $BUDGET_YEAR
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBPROJLAUNRES[] $rBPROJLAUNRESs
 * @property RBPROJ $1
 * @property WASECTION $10
 */
class RB_PROJ_LAUN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_PROJ_LAUN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RB_PROJ_ID', 'WA_SECTION_ID', 'PROJ_RESPONSIBLE', 'FROM_DATE', 'TO_DATE', 'BUDGET', 'SATISFACTION_LEVEL', 'BUDGET_YEAR', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['RB_PROJ_ID', 'WA_SECTION_ID', 'BUDGET'], 'integer'],
            [['PROJ_RESPONSIBLE'], 'string', 'max' => 255],
            [['SATISFACTION_LEVEL'], 'string', 'max' => 2],
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
            'RB_PROJ_LAUN_ID' => 'เลขที่อ้างอิง ตารางแผนงาน-โครงการ ที่ดำเนินการ ',
            'RB_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'WA_SECTION_ID' => 'เลขที่อ้างอิง ตารางส่วนงาน รับผิดชอบ',
            'PROJ_RESPONSIBLE' => 'ผู้รับผิดชอบโครงการ ::',
            'FROM_DATE' => 'วันเริ่มดำเนินการ ',
            'TO_DATE' => 'วันสิ้นสุดดำเนินการ ',
            'BUDGET' => 'งบประมาณทั้งโครงการ',
            'SATISFACTION_LEVEL' => 'ระดับความพึงพอใจของผู้รับบริการ  (%)',
            'BUDGET_YEAR' => 'ปีงบประมาณ',
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
    public function getRbProjLaunRess()
    {
        return $this->hasMany(RB_PROJ_LAUN_RES::className(), ['RB_PROJ_LAUN_ID' => 'RB_PROJ_LAUN_ID']);
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
    public function getWaSection()
    {
        return $this->hasOne(WA_SECTION::className(), ['WA_SECTION_ID' => 'WA_SECTION_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select rb_proj_laun_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
