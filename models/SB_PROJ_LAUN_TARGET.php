<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN_TARGET".
 *
 * @property  $SB_PROJ_LAUN_TARGET_ID
 * @property  $SB_PRJECT_LAUN_ID
 * @property  $TARGET_NAME_TH
 * @property  $TARGET_NAME_EN
 * @property  $TARGET_AMOUNT
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SBPROJLAUN $1
 * @property SBPROJLAUNRESTARGET[] $sBPROJLAUNRESTARGETs
 */
class SB_PROJ_LAUN_TARGET extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN_TARGET';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PRJECT_LAUN_ID', 'TARGET_NAME_TH', 'TARGET_AMOUNT', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PRJECT_LAUN_ID', 'TARGET_AMOUNT'], 'integer'],
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
            'SB_PROJ_LAUN_TARGET_ID' => 'Sb  Proj  Laun  Target  ID',
            'SB_PRJECT_LAUN_ID' => 'แผนงาน-โครงการ ที่กำลังดำเนินการ',
            'TARGET_NAME_TH' => 'ชื่อกลุ่มเป้าหมาย (ไทย)',
            'TARGET_NAME_EN' => 'ชื่อกลุ่มเป้าหมาย (Eng)',
            'TARGET_AMOUNT' => 'เป้าหมาย (คน)',
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
    public function getSbProjLaun()
    {
        return $this->hasOne(SB_PROJ_LAUN::className(), ['SB_PROJ_LAUN_ID' => 'SB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunResTargets()
    {
        return $this->hasMany(SB_PROJ_LAUN_RES_TARGET::className(), ['SB_PROJ_LAUN_TARGET_ID' => 'SB_PROJ_LAUN_TARGET_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_target_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
