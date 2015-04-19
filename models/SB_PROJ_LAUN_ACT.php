<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN_ACT".
 *
 * @property  $SB_PROJ_LAUN_ACT_ID
 * @property  $SB_PROJ_LAUN_ID
 * @property  $ACT_NAME_TH
 * @property  $ACT_NAME_EN
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class SB_PROJ_LAUN_ACT extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN_ACT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJ_LAUN_ID', 'ACT_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PROJ_LAUN_ID'], 'integer'],
            [['ACT_NAME_TH', 'ACT_NAME_EN'], 'string', 'max' => 255],
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
            'SB_PROJ_LAUN_ACT_ID' => 'Sb  Proj  Laun  Act  ID',
            'SB_PROJ_LAUN_ID' => 'แผนงาน-โครงการ ที่กำลังดำเนินการ',
            'ACT_NAME_TH' => 'ชื่อรูปแบบกิจกรรม (ไทย)',
            'ACT_NAME_EN' => 'ชื่อรูปแบบกิจกรรม (Eng)',
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
    public function getSbProjLauns()
    {
        return $this->hasMany(SB_PROJ_LAUN::className(), ['SB_PROJ_LAUN_ID' => 'SB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunActSports()
    {
        return $this->hasMany(SB_PROJ_LAUN_ACT_SPORT::className(), ['SB_PROJ_LAUN_ID' => 'SB_PROJ_LAUN_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_act_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
