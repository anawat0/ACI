<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_GROUP_USER".
 *
 * @property  $WA_GROUP_USER_ID
 * @property  $WA_GROUP_ID
 * @property  $WA_USER_ID
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAGROUP $1
 * @property WAUSER $10
 */
class WA_GROUP_USER extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_GROUP_USER';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WA_GROUP_ID', 'WA_USER_ID', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['WA_GROUP_ID', 'WA_USER_ID'], 'integer'],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WA_GROUP_USER_ID' => 'Wa  Group  User  ID',
            'WA_GROUP_ID' => 'Wa  Group  ID',
            'WA_USER_ID' => 'Wa  User  ID',
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
        return $this->hasOne(WAGROUP::className(), ['WA_GROUP_ID' => '1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get10()
    {
        return $this->hasOne(WAUSER::className(), ['WA_USER_ID' => '1']);
    }
    
    public function getWauser()
    {
        // Customer has_many Order via Order.customer_id -> id
        return $this->hasMany(WAUSER::className(), ['WA_USER_ID' => 'WA_USER_ID']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select wa_group_user_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
