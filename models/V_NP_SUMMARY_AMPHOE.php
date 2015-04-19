<?php

namespace app\models;

use Yii;
use yii\db\mssql\PDO;

/**
 * This is the model class for table "V_NP_SUMMARY_AMPHOE".
 *
 * @property  $YEAR
 * @property  $MONTH
 * @property  $AMPHOE_CODE
 * @property  $AMPHOE_NAME_TH
 * @property  $PROVINCE_CODE
 * @property  $PROVINCE_NAME_TH
 * @property  $COUNT_FORM1
 * @property  $COUNT_FORM2_1
 * @property  $COUNT_FORM2_2
 * @property  $COUNT_SAT
 * @property  $STATUS
 */
class V_NP_SUMMARY_AMPHOE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'V_NP_SUMMARY_AMPHOE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MONTH'], 'integer'],
            [['COUNT_FORM1', 'COUNT_FORM2_1', 'COUNT_FORM2_2', 'COUNT_SAT'], 'string'],
            [['YEAR', 'AMPHOE_CODE'], 'string', 'max' => 4],
            [['AMPHOE_NAME_TH'], 'string', 'max' => 150],
            [['PROVINCE_CODE', 'STATUS'], 'string', 'max' => 2],
            [['PROVINCE_NAME_TH'], 'string', 'max' => 100]
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
            'AMPHOE_NAME_TH' => 'Amphoe  Name  Th',
            'PROVINCE_CODE' => 'Province  Code',
            'PROVINCE_NAME_TH' => 'Province  Name  Th',
            'COUNT_FORM1' => 'Count  Form1',
            'COUNT_FORM2_1' => 'Count  Form2 1',
            'COUNT_FORM2_2' => 'Count  Form2 2',
            'COUNT_SAT' => 'Count  Sat',
            'STATUS' => 'Status',
        ];
    }

    static public function callSpOpenRecAmphoe($amphoeCode, $provinceCode, $month, $year)
    {
        $oResult = 0;
        $oerrMsg = '';

        $command = Yii::$app->db->createCommand("CALL NP_PKG.SP_OPEN_REC_AMPHOE_NEW(?,?,?,?,?,?)");
        $command->bindParam(1, $amphoeCode, PDO::PARAM_STR, 4);
        $command->bindParam(2, $provinceCode, PDO::PARAM_STR, 2);
        $command->bindParam(3, $month, PDO::PARAM_INT, 1);
        $command->bindParam(4, $year, PDO::PARAM_STR, 4);
        $command->bindParam(5, $oResult, PDO::PARAM_INT, 1);
        $command->bindParam(6, $oerrMsg, PDO::PARAM_STR, 5000);
        $command->execute();

        // Yii::trace($oResult, 'Debug');
        // Yii::trace($oerrMsg, 'Debug');

        return ['result' => $oResult, 'err_msg' => $oerrMsg];
    }

    static public function callSpCloseRecAmphoe($amphoeCode, $provinceCode, $month, $year)
    {
        $oResult = 0;
        $oerrMsg = '';

        $command = Yii::$app->db->createCommand("CALL NP_PKG.SP_CLOSE_REC_AMPHOE_NEW(?,?,?,?,?,?)");
        $command->bindParam(1, $amphoeCode, PDO::PARAM_STR, 4);
        $command->bindParam(2, $provinceCode, PDO::PARAM_STR, 2);
        $command->bindParam(3, $month, PDO::PARAM_INT, 1);
        $command->bindParam(4, $year, PDO::PARAM_STR, 4);
        $command->bindParam(5, $oResult, PDO::PARAM_INT, 1);
        $command->bindParam(6, $oerrMsg, PDO::PARAM_STR, 5000);
        $command->execute();

        // Yii::trace($oResult, 'Debug');
        // Yii::trace($oerrMsg, 'Debug');

        return ['result' => $oResult, 'err_msg' => $oerrMsg];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNpSpOpenAmphoe()
    {
        return $this->hasOne(NP_SP_OPEN_AMPHOE::className(), ['YEAR' => 'YEAR',
                                                                'MONTH' => 'MONTH',
                                                                'PROVINCE_CODE' => 'PROVINCE_CODE',
                                                                'AMPHOE_CODE' => 'AMPHOE_CODE']);
    }
}