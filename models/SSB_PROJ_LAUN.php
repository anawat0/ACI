<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SSB_PROJ_LAUN".
 *
 * @property  $SSB_PROJ_LAUN_ID
 * @property  $SSB_PROJ_ID
 * @property  $WA_SECTION_ID
 * @property  $PROJECT_RESPONSIBLE
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
 * @property WASECTION $1
 * @property SSBPROJ $10
 */
class SSB_PROJ_LAUN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SSB_PROJ_LAUN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SSB_PROJ_ID', 'WA_SECTION_ID', 'PROJECT_RESPONSIBLE', 'FROM_DATE', 'TO_DATE', 'BUDGET', 'SATISFACTION_LEVEL', 'BUDGET_YEAR', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SSB_PROJ_ID', 'WA_SECTION_ID'], 'integer'],
            [['PROJECT_RESPONSIBLE'], 'string', 'max' => 255],
            [['BUDGET'], 'string', 'max' => 10],
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
            'SSB_PROJ_LAUN_ID' => 'เลขที่อ้างอิง ตารางแผนงาน-โครงการ ที่ดำเนินการ ',
            'SSB_PROJ_ID' => 'เลขที่อ้างอิง ตารางแผนงาน/โครงการ',
            'WA_SECTION_ID' => 'เลขที่อ้างอิง ตารางส่วนงาน รับผิดชอบ',
            'PROJECT_RESPONSIBLE' => 'ผู้รับผิดชอบโครงการ ::',
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
    public function getSsbProjLaunRess()
    {
        return $this->hasMany(SSB_PROJ_LAUN_RES::className(), ['SSB_PROJ_LAUN_ID' => 'SSB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSsbProj()
    {
        return $this->hasOne(SSB_PROJ::className(), ['SSB_PROJ_ID' => 'SSB_PROJ_ID']);
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
        $command = Yii::$app->db->createCommand("select ssb_proj_laun_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
