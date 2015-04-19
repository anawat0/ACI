<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "CONST_TASK".
 *
 * @property  $CONST_TASK_ID
 * @property  $CONST_PROJ_ID
 * @property  $TASK_NAME_TH
 * @property  $TASK_NAME_EN
 * @property  $BUDGET
 * @property  $START_DATE
 * @property  $END_DATE
 * @property  $PROGRESS
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property CONSTPROJ $1
 */
class CONST_TASK extends \yii\db\ActiveRecord 
{    
    /*public $CONST_TASK_ID ;
    public $CONST_PROJ_ID ;
    public $TASK_NAME_TH ;
    public $TASK_NAME_EN ;
    public $BUDGET ;
    public $START_DATE ;
    public $END_DATE ;
    public $PROGRESS ;
    public $STATUS ;
    public $CREATE_USER_ID ;
    public $CREATE_TIME ;
    public $LAST_UPD_USER_ID ;
    public $LAST_UPD_TIME ;    */
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CONST_TASK';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CONST_PROJ_ID', 'TASK_NAME_TH', 'START_DATE', 'END_DATE', 'PROGRESS', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['CONST_PROJ_ID', 'PROGRESS', 'BUDGET'], 'integer'],
            [['TASK_NAME_TH', 'TASK_NAME_EN'], 'string', 'max' => 100],
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
            'CONST_TASK_ID' => 'เลขที่อ้างอิง ตารางงวดงาน ของโครงการสร้างสนามกีฬา',
            'CONST_PROJ_ID' => 'Const  Proj  ID',
            'TASK_NAME_TH' => 'ชื่อรายละเอียดงวดงาน',
            'TASK_NAME_EN' => 'Task  Name  En',
            'BUDGET' => 'งบประมาณ',
            'START_DATE' => 'วันที่เริ่มงวดงาน',
            'END_DATE' => 'วันที่สิ้นสุดงวดงาน',
            'PROGRESS' => 'ความก้าวหน้า',
            'STATUS' => 'สถานะสถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get1()
    {
        return $this->hasOne(CONSTPROJ::className(), ['CONST_PROJ_ID' => '1']);
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select const_task_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
