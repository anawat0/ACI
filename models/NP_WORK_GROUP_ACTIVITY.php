<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_WORK_GROUP_ACTIVITY".
 *
 * @property  $NP_WORK_GROUP_ACTIVITY_ID
 * @property  $NP_WORK_GROUP_ID
 * @property  $ACTIVITY_SUBJECT
 * @property  $SEQ
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property NPWORKGROUP $1
 * @property NPACTIVITYFORM2PROVINCE[] $nPACTIVITYFORM2PROVINCEs
 */
class NP_WORK_GROUP_ACTIVITY extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_WORK_GROUP_ACTIVITY';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NP_WORK_GROUP_ID', 'ACTIVITY_SUBJECT', 'SEQ', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['NP_WORK_GROUP_ID', 'SEQ'], 'integer'],
            [['ACTIVITY_SUBJECT'], 'string', 'max' => 250],
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
            'NP_WORK_GROUP_ACTIVITY_ID' => 'Np  Work  Group  Activity  ID',
            'NP_WORK_GROUP_ID' => 'Np  Work  Group  ID',
            'ACTIVITY_SUBJECT' => 'Activity  Subject',
            'SEQ' => 'Seq',
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
        return $this->hasOne(NPWORKGROUP::className(), ['NP_WORK_GROUP_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNPACTIVITYFORM2PROVINCEs()
    {
        return $this->hasMany(NPACTIVITYFORM2PROVINCE::className(), ['1' => 'NP_WORK_GROUP_ACTIVITY_ID']);
    }
}
