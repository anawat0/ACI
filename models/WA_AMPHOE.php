<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_AMPHOE".
 *
 * @property  $AMPHOE_CODE
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_NAME_TH
 * @property  $AMPHOE_NAME_EN
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAPROVINCE $1
 */
class WA_AMPHOE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_AMPHOE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PROVINCE_CODE', 'AMPHOE_NAME_TH', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_NAME_TH', 'AMPHOE_NAME_EN'], 'string', 'max' => 100],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AMPHOE_CODE' => 'Amphoe  Code',
            'PROVINCE_CODE' => 'Province  Code',
            'AMPHOE_NAME_TH' => 'Amphoe  Name  Th',
            'AMPHOE_NAME_EN' => 'Amphoe  Name  En',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaProvince()
    {
        return $this->hasOne(WA_PROVINCE::className(), ['PROVINCE_CODE' => 'PROVINCE_CODE']);
    }
}
