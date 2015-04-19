<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SB_PROJ_LAUN_RES_TARGET".
 *
 * @property  $SB_PROJ_LAUN_RES_TARGET_ID
 * @property  $SB_PROJ_LAUN_RES_ID
 * @property  $SB_PROJ_LAUN_TARGET_ID
 * @property  $TARGET_AMOUNT
 * @property  $ACTUAL_AMOUNT
 *
 * @property SBPROJLAUNTARGET $1
 * @property SBPROJLAUNRES $10
 */
class SB_PROJ_LAUN_RES_TARGET extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SB_PROJ_LAUN_RES_TARGET';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SB_PROJ_LAUN_RES_ID', 'SB_PROJ_LAUN_TARGET_ID', 'TARGET_AMOUNT', 'ACTUAL_AMOUNT'], 'required'],
            [['SB_PROJ_LAUN_RES_ID', 'SB_PROJ_LAUN_TARGET_ID', 'TARGET_AMOUNT', 'ACTUAL_AMOUNT'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SB_PROJ_LAUN_RES_TARGET_ID' => 'Sb  Proj  Laun  Res  Target  ID',
            'SB_PROJ_LAUN_RES_ID' => 'Sb  Proj  Laun  Res  ID',
            'SB_PROJ_LAUN_TARGET_ID' => 'กลุ่มเป้าหมาย',
            'TARGET_AMOUNT' => 'เป้าหมาย',
            'ACTUAL_AMOUNT' => 'ทำได้',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunTarget()
    {
        return $this->hasOne(SB_PROJ_LAUN_TARGET::className(), ['SB_PROJ_LAUN_TARGET_ID' => 'SB_PROJ_LAUN_TARGET_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbProjLaunRes()
    {
        return $this->hasOne(SB_PROJ_LAUN_RES::className(), ['SB_PROJ_LAUN_RES_ID' => 'SB_PROJ_LAUN_RES_ID']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select sb_proj_laun_res_target_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }
}
