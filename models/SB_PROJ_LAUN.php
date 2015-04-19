<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN".
 *
 * @property  $SB_PROJ_LAUN_ID
 * @property  $SB_PROJ_ID
 * @property  $SB_SUB_PROJ_ID
 * @property  $WA_SECTION_ID
 * @property  $PROJECT_RESPONSIBLE
 * @property  $PLACE
 * @property  $FROM_DATE
 * @property  $TO_DATE
 * @property  $BUDGET
 * @property  $BUDGET_COUNTRY
 * @property  $BUDGET_ZONE
 * @property  $BUDGET_PROV
 * @property  $BUDGET_AMPHOE
 * @property  $SATISFACTION_LEVEL
 * @property  $BUDGET_YEAR
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SBPROJLAUNTARGET[] $sBPROJLAUNTARGETs
 * @property SBPROJ $1
 * @property WASECTION $10
 * @property SBSUBPROJ $11
 */
class SB_PROJ_LAUN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJ_ID', 'SB_SUB_PROJ_ID', 'WA_SECTION_ID', 'PROJECT_RESPONSIBLE', 'FROM_DATE', 'TO_DATE', 'BUDGET', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PROJ_ID', 'SB_SUB_PROJ_ID', 'WA_SECTION_ID', 'BUDGET', 'BUDGET_COUNTRY', 'BUDGET_ZONE', 'BUDGET_PROV', 'BUDGET_AMPHOE'], 'integer'],
            [['PROJECT_RESPONSIBLE', 'PLACE'], 'string', 'max' => 255],
            //[['BUDGET', 'BUDGET_COUNTRY', 'BUDGET_ZONE', 'BUDGET_PROV', 'BUDGET_AMPHOE'], 'string', 'max' => 8],
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
            'SB_PROJ_LAUN_ID' => 'Sb  Proj  Laun  ID',
            'SB_PROJ_ID' => 'แผนงาน/โครงการหลัก',
            'SB_SUB_PROJ_ID' => 'แผนงาน/โครงการย่อย',
            'WA_SECTION_ID' => 'Wa  Section  ID',
            'PROJECT_RESPONSIBLE' => 'ผู้รับผิดชอบ',
            'PLACE' => 'Place',
            'FROM_DATE' => 'From  Date',
            'TO_DATE' => 'To  Date',
            'BUDGET' => 'งบประมาณ',
            'BUDGET_COUNTRY' => 'Budget  Country',
            'BUDGET_ZONE' => 'Budget  Zone',
            'BUDGET_PROV' => 'Budget  Prov',
            'BUDGET_AMPHOE' => 'Budget  Amphoe',
            'SATISFACTION_LEVEL' => 'Satisfaction  Level',
            'BUDGET_YEAR' => 'ประจำปี',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunTargets()
    {
        return $this->hasMany(SB_PROJ_LAUN_TARGET::className(), ['1' => 'SB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunRess()
    {
        return $this->hasMany(SB_PROJ_LAUN_RES::className(), ['SB_PROJECT_LAUNCH_ID' => 'SB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProj()
    {
        return $this->hasOne(SB_PROJ::className(), ['SB_PROJ_ID' => 'SB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get10()
    {
        return $this->hasOne(WASECTION::className(), ['WA_SECTION_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbSubProj()
    {
        return $this->hasOne(SB_SUB_PROJ::className(), ['SB_SUB_PROJ_ID' => 'SB_SUB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunAct()
    {
        return $this->hasOne(SB_PROJ_LAUN_ACT::className(), ['SB_PROJ_LAUN_ACT_ID' => 'SB_PROJ_LAUN_ACT_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
