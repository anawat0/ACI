<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_DEPARTMENT".
 *
 * @property  $WA_DEPARTMENT_ID
 * @property  $DEPARTMENT_NAME_TH
 * @property  $DEPARTMENT_NAME_EN
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WASECTION[] $wASECTIONs
 */
class WA_DEPARTMENT extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_DEPARTMENT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DEPARTMENT_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['DEPARTMENT_NAME_TH', 'DEPARTMENT_NAME_EN'], 'string', 'max' => 100],
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
            'WA_DEPARTMENT_ID' => 'Wa  Department  ID',
            'DEPARTMENT_NAME_TH' => 'Department  Name  Th',
            'DEPARTMENT_NAME_EN' => 'Department  Name  En',
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
    public function getWASECTIONs()
    {
        return $this->hasMany(WASECTION::className(), ['1' => 'WA_DEPARTMENT_ID']);
    }
}
