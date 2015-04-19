<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_SUB_PROJ".
 *
 * @property  $SB_SUB_PROJ_ID
 * @property  $SB_PROJ_ID
 * @property  $SUB_PROJ_NAME_TH
 * @property  $SUB_PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SBPROJ $1
 * @property SBPROJLAUN[] $sBPROJLAUNs
 */
class SB_SUB_PROJ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_SUB_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJ_ID', 'SUB_PROJ_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PROJ_ID', 'BUDGET'], 'integer'],
            [['SUB_PROJ_NAME_TH', 'SUB_PROJ_NAME_EN'], 'string', 'max' => 255],
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
            'SB_SUB_PROJ_ID' => 'Sb  Sub  Proj  ID',
            'SB_PROJ_ID' => 'Sb  Proj  ID',
            'SUB_PROJ_NAME_TH' => 'โครงการย่อย-กิจกรรม (ไทย)',
            'SUB_PROJ_NAME_EN' => 'โครงการย่อย-กิจกรรม (ENG)',
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
    public function getSbProj()
    {
        return $this->hasOne(SB_PROJ::className(), ['SB_PROJ_ID' => 'SB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLauns()
    {
        return $this->hasMany(SB_PROJ_LAUN::className(), ['SB_SUB_PROJ_ID' => 'SB_SUB_PROJ_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_sub_proj_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
