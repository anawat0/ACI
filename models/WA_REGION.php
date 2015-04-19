<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_REGION".
 *
 * @property  $REGION_CODE
 * @property  $REGION_NAME_TH
 * @property  $REGION_NAME_EN
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAPROVINCE[] $wAPROVINCEs
 */
class WA_REGION extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_REGION';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['REGION_NAME_TH', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['REGION_NAME_TH', 'REGION_NAME_EN'], 'string', 'max' => 100],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'REGION_CODE' => 'รหัสภาค',
            'REGION_NAME_TH' => 'ชื่อภาค (ไทย)',
            'REGION_NAME_EN' => 'ชื่อภาค (eng)',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWAPROVINCEs()
    {
        return $this->hasMany(WAPROVINCE::className(), ['1' => 'REGION_CODE']);
    }
}
