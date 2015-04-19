<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_SPORT".
 *
 * @property  $WA_SPORT_ID
 * @property  $SPORT_NAME_TH
 * @property  $SPORT_NAME_EN
 * @property  $DESCRIPTION
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class WA_SPORT extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_SPORT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SPORT_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SPORT_NAME_TH', 'SPORT_NAME_EN'], 'string', 'max' => 100],
            [['DESCRIPTION'], 'string', 'max' => 250],
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
            'WA_SPORT_ID' => 'Wa  Sport  ID',
            'SPORT_NAME_TH' => 'Sport  Name  Th',
            'SPORT_NAME_EN' => 'Sport  Name  En',
            'DESCRIPTION' => 'Description',
            'STATUS' => 'Status',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }
}
