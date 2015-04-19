<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ".
 *
 * @property  $SB_PROJ_ID
 * @property  $SB_MAIN_PROJ_ID
 * @property  $PROJ_NAME_TH
 * @property  $PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SBSUBPROJ[] $sBSUBPROJs
 * @property SBPROJLAUN[] $sBPROJLAUNs
 * @property SBMAINPROJ $1
 */
class SB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_MAIN_PROJ_ID', 'PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_MAIN_PROJ_ID', 'BUDGET'], 'integer'],
            [['PROJ_NAME_TH', 'PROJ_NAME_EN'], 'string', 'max' => 255],
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
            'SB_PROJ_ID' => 'Sb  Proj  ID',
            'SB_MAIN_PROJ_ID' => 'รหัสแผนงาน/โครงการหลัก',
            'PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการ (ไทย)',
            'PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการ (Eng)',
            'BUDGET' => 'งบประมาณ',
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
    public function getSbSubProjs()
    {
        return $this->hasMany(SB_SUB_PROJ::className(), ['SB_PROJ_ID' => 'SB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLauns()
    {
        return $this->hasMany(SB_PROJ_LAUN::className(), ['SB_PROJ_ID' => 'SB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbMainProj()
    {
        return $this->hasOne(SB_MAIN_PROJ::className(), ['SB_MAIN_PROJ_ID' => 'SB_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
