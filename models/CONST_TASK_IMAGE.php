<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CONST_TASK_IMAGE".
 *
 * @property  $CONST_TASK_IMAGE_ID
 * @property  $CONST_TASK_ID
 * @property  $IMAGE_PATH
 * @property  $IMAGE_DESCRIPTION
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property CONSTTASK $1
 */
class CONST_TASK_IMAGE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CONST_TASK_IMAGE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CONST_TASK_ID'], 'integer'],
            [['IMAGE_PATH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['IMAGE_PATH', 'IMAGE_DESCRIPTION'], 'string', 'max' => 255],
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
            'CONST_TASK_IMAGE_ID' => 'Const  Task  Image  ID',
            'CONST_TASK_ID' => 'Const  Task  ID',
            'IMAGE_PATH' => 'Image  Path',
            'IMAGE_DESCRIPTION' => 'Image  Description',
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
    public function get1()
    {
        return $this->hasOne(CONST_TASK::className(), ['CONST_TASK_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select const_task_image_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
