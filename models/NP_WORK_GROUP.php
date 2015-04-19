<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_WORK_GROUP".
 *
 * @property  $NP_WORK_GROUP_ID
 * @property  $GROUP_TYPE
 * @property  $GROUP_SUBJECT
 * @property  $SEQ
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property NPWORKGROUPACTIVITY[] $nPWORKGROUPACTIVITies
 */
class NP_WORK_GROUP extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_WORK_GROUP';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['GROUP_TYPE', 'GROUP_SUBJECT', 'SEQ', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SEQ'], 'integer'],
            [['GROUP_TYPE', 'STATUS'], 'string', 'max' => 1],
            [['GROUP_SUBJECT'], 'string', 'max' => 250],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NP_WORK_GROUP_ID' => 'Np  Work  Group  ID',
            'GROUP_TYPE' => 'Group  Type',
            'GROUP_SUBJECT' => 'Group  Subject',
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
    public function getNPWORKGROUPACTIVITies()
    {
        return $this->hasMany(NPWORKGROUPACTIVITY::className(), ['1' => 'NP_WORK_GROUP_ID']);
    }
}
