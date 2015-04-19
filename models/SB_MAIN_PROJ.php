<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_MAIN_PROJ".
 *
 * @property  $SB_MAIN_PROJ_ID
 * @property  $MAIN_PROJ_NAME_TH
 * @property  $MAIN_PROJ_NAME_EN
 * @property  $BUDGET_YEAR
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SBPROJ[] $sBPROJs
 */
class SB_MAIN_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_MAIN_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MAIN_PROJ_NAME_TH', 'BUDGET_YEAR', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['MAIN_PROJ_NAME_TH', 'MAIN_PROJ_NAME_EN'], 'string', 'max' => 255],
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
            'SB_MAIN_PROJ_ID' => 'Sb  Main  Proj  ID',
            'MAIN_PROJ_NAME_TH' => 'ชื่อแผนงาน/โครงการหลัก (ไทย)',
            'MAIN_PROJ_NAME_EN' => 'ชื่อแผนงาน/โครงการหลัก (Eng)',
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
    public function getSbProjs()
    {
        return $this->hasMany(SB_PROJ::className(), ['SB_MAIN_PROJ_ID' => 'SB_MAIN_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_main_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
