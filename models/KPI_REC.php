<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "KPI_REC".
 *
 * @property  $KPI_REC_ID
 * @property  $QUATER
 * @property  $KPI_DIMENTION_ID
 * @property  $KPI_FST_ID
 * @property  $KPI_SECD_ID
 * @property  $KPI_THRD_ID
 * @property  $KPI_FRTH_ID
 * @property  $POINT
 * @property  $WT
 * @property  $TARGET_SCORE
 * @property  $ACTUAL_SCORE
 * @property  $TARGET_QUATER_CODE
 * @property  $TARGET_QUATER_REMARK
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property KPIDIMENTION $1
 * @property KPIFRTH $10
 * @property KPIFST $11
 * @property KPISECD $12
 * @property KPITHRD $13
 */
class KPI_REC extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'KPI_REC';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['QUATER', 'KPI_DIMENTION_ID', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['QUATER', 'TARGET_QUATER_CODE'], 'string', 'max' => 1],
            [['POINT', 'WT'], 'string', 'max' => 3],
            [['TARGET_SCORE', 'ACTUAL_SCORE'], 'string', 'max' => 8],
            [['TARGET_QUATER_REMARK'], 'string', 'max' => 255],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'KPI_REC_ID' => 'Kpi  Rec  ID',
            'QUATER' => 'Quater',
            'KPI_DIMENTION_ID' => 'Kpi  Dimention  ID',
            'KPI_FST_ID' => 'Kpi  Fst  ID',
            'KPI_SECD_ID' => 'Kpi  Secd  ID',
            'KPI_THRD_ID' => 'Kpi  Thrd  ID',
            'KPI_FRTH_ID' => 'Kpi  Frth  ID',
            'POINT' => 'Point',
            'WT' => 'Wt',
            'TARGET_SCORE' => 'Target  Score',
            'ACTUAL_SCORE' => 'Actual  Score',
            'TARGET_QUATER_CODE' => 'Target  Quater  Code',
            'TARGET_QUATER_REMARK' => 'Target  Quater  Remark',
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
        return $this->hasOne(KPIDIMENTION::className(), ['KPI_DIMENTION_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get10()
    {
        return $this->hasOne(KPIFRTH::className(), ['KPI_FRTH_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get11()
    {
        return $this->hasOne(KPIFST::className(), ['KPI_FST_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get12()
    {
        return $this->hasOne(KPISECD::className(), ['KPI_SECD_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get13()
    {
        return $this->hasOne(KPITHRD::className(), ['KPI_THRD_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select kpi_rec_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
