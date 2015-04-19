<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "IM_PROJ_LAUN".
 *
 * @property  $IM_PROJ_LAUN_ID
 * @property  $IM_PROJ_ID
 * @property  $WA_SECTION_ID
 * @property  $PROJ_RESP
 * @property  $FROM_DATE
 * @property  $TO_DATE
 * @property  $BUDGET
 * @property  $BUDGET_TTPROV
 * @property  $BUDGET_INST_SPORT
 * @property  $BUDGET_MT_CENTER
 * @property  $BUDGET_SCHL_SPORT
 * @property  $SATISFACTION_LEVEL
 * @property  $PLACE
 * @property  $BUDGET_YEAR
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property IMPROJLAUNRES[] $iMPROJLAUNRESs
 * @property IMPROJLAUNTARGET[] $iMPROJLAUNTARGETs
 * @property IMPROJ $1
 * @property WASECTION $10
 */
class IM_PROJ_LAUN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IM_PROJ_LAUN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IM_PROJ_ID', 'PROJ_RESP', 'FROM_DATE', 'TO_DATE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IM_PROJ_ID', 'BUDGET', 'BUDGET_TTPROV', 'BUDGET_INST_SPORT', 'BUDGET_MT_CENTER', 'BUDGET_SCHL_SPORT'], 'integer'],
            [['PROJ_RESP', 'PLACE'], 'string', 'max' => 255],
            [['SATISFACTION_LEVEL'], 'string', 'max' => 20],
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
            'IM_PROJ_LAUN_ID' => 'เลขที่อ้างอิง ตารางแผนงาน-โครงการ ที่ดำเนินการ ',
            'IM_PROJ_ID' => 'ตารางแผนงาน/โครงการ',
            'PROJ_RESP' => 'ผู้รับผิดชอบโครงการ',
            'FROM_DATE' => 'วันที่ดำเนินการจาก-ถึง',
            'TO_DATE' => '-',
            'BUDGET' => 'งบประมาณรวม',
            'BUDGET_TTPROV' => 'ท่องเที่ยวและกีฬาจังหวัด',
            'BUDGET_INST_SPORT' => 'สถาบันการพลศึกษา',
            'BUDGET_MT_CENTER' => 'ศูนย์อนุรักษ์มวยไทยจังหวัด',
            'BUDGET_SCHL_SPORT' => 'โรงเรียนกีฬา',
            'SATISFACTION_LEVEL' => 'ระดับความพึงพอใจ',
            'PLACE' => 'สถานที่',
            'BUDGET_YEAR' => 'ปีงบประมาณ',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
            'HEADER_SECTION_1' => 'บันทึกแผนงาน-โครงกำร ที่กำลังดำเนินการ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLaunRess()
    {
        return $this->hasMany(IM_PROJ_LAUN_RES::className(), ['IM_PROJ_LAUN_ID' => 'IM_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProjLaunTargets()
    {
        return $this->hasMany(IM_PROJ_LAUN_TARGET::className(), ['IM_PROJ_LAUN_ID' => 'IM_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImProj()
    {
        return $this->hasOne(IM_PROJ::className(), ['IM_PROJ_ID' => 'IM_PROJ_ID']);
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
        $command = Yii::$app->db->createCommand("select im_proj_laun_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
