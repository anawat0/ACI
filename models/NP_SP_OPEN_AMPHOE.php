<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_SP_OPEN_AMPHOE".
 *
 * @property  $YEAR
 * @property  $MONTH
 * @property  $AMPHOE_CODE
 * @property  $PROVINCE_CODE
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class NP_SP_OPEN_AMPHOE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_SP_OPEN_AMPHOE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MONTH'], 'string'],
            [['YEAR', 'AMPHOE_CODE'], 'string', 'max' => 16],
            [['PROVINCE_CODE', 'STATUS'], 'string', 'max' => 8],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['CREATE_TIME', 'LAST_UPD_TIME'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'YEAR' => 'Year',
            'MONTH' => 'Month',
            'AMPHOE_CODE' => 'Amphoe  Code',
            'PROVINCE_CODE' => 'Province  Code',
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
    public function getVNpSummaryAmphoes()
    {
        return $this->hasMany(V_NP_SUMMARY_AMPHOE::className(), ['YEAR' => 'YEAR',
                                                                'MONTH' => 'MONTH',
                                                                'PROVINCE_CODE' => 'PROVINCE_CODE',
                                                                'AMPHOE_CODE' => 'AMPHOE_CODE']);
    }
}
