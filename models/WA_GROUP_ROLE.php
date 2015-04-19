<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WA_GROUP_ROLE".
 *
 * @property  $WA_GROUP_ROLE_ID
 * @property  $WA_GROUP_ID
 * @property  $WA_MENU_SUB_ID
 * @property  $ACCESS_FLAG
 * @property  $ADD_FLAG
 * @property  $EDIT_FLAG
 * @property  $DELETE_FLAG
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property WAGROUP $1
 * @property WAMENUSUB $10
 */
class WA_GROUP_ROLE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'WA_GROUP_ROLE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WA_GROUP_ID', 'WA_MENU_SUB_ID', 'ACCESS_FLAG', 'ADD_FLAG', 'EDIT_FLAG', 'DELETE_FLAG', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['WA_GROUP_ID', 'WA_MENU_SUB_ID'], 'integer'],
            [['ACCESS_FLAG', 'ADD_FLAG', 'EDIT_FLAG', 'DELETE_FLAG'], 'string', 'max' => 1],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WA_GROUP_ROLE_ID' => 'Wa  Group  Role  ID',
            'WA_GROUP_ID' => 'Wa  Group  ID',
            'WA_MENU_SUB_ID' => 'Wa  Menu  Sub  ID',
            'ACCESS_FLAG' => 'Access  Flag',
            'ADD_FLAG' => 'Add  Flag',
            'EDIT_FLAG' => 'Edit  Flag',
            'DELETE_FLAG' => 'Delete  Flag',
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
        return $this->hasOne(WAMENUSUB::className(), ['WA_MENU_SUB_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select wa_group_role_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
