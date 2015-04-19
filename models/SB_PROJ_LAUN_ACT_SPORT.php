<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN_ACT_SPORT".
 *
 * @property  $SB_PROJ_LAUN_ACT_SPORT_ID
 * @property  $SB_PROJ_LAUN_ACT_ID
 * @property  $WA_SPORT_ID
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WASPORT $1
 */
class SB_PROJ_LAUN_ACT_SPORT extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN_ACT_SPORT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJ_LAUN_ACT_ID', 'WA_SPORT_ID', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SB_PROJ_LAUN_ACT_ID', 'WA_SPORT_ID'], 'integer'],
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
            'SB_PROJ_LAUN_ACT_SPORT_ID' => 'Sb  Proj  Laun  Act  Sport  ID',
            'SB_PROJ_LAUN_ACT_ID' => 'Sb  Proj  Laun  Act  ID',
            'WA_SPORT_ID' => 'ชนิกกีฬา',
            'STATUS' => 'Status',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(WASPORT::className(), ['WA_SPORT_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunAct()
    {
        return $this->hasOne(WASPORT::className(), ['SB_PROJ_LAUN_ACT_ID' => 'SB_PROJ_LAUN_ACT_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_act_sport_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
