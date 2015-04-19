<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_ZONE".
 *
 * @property  $ZONE_CODE
 * @property  $ZONE_NAME_TH
 * @property  $ZONE_NAME_EN
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAPROVINCE[] $wAPROVINCEs
 */
class WA_ZONE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_ZONE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ZONE_NAME_TH', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['ZONE_NAME_TH', 'ZONE_NAME_EN'], 'string', 'max' => 100],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ZONE_CODE' => 'Zone  Code',
            'ZONE_NAME_TH' => 'Zone  Name  Th',
            'ZONE_NAME_EN' => 'Zone  Name  En',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAPROVINCEs()
    {
        return $this->hasMany(WAPROVINCE::className(), ['1' => 'ZONE_CODE']);
    }
}
