<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_ACTIVITY_FORM2_AMPHOE".
 *
 * @property  $NP_ACTIVITY_FORM2_AMPHOE_ID
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $NP_WORK_GROUP_ACTIVITY_ID
 * @property  $YEAR
 * @property  $MONTH
 * @property  $SUBJECT
 * @property  $SUBJECT_LOCATION
 * @property  $IMAGE_PATH
 * @property  $PARTICIPANTS_NUM
 * @property  $START_DATE
 * @property  $END_DATE
 * @property  $SEQ
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class NP_ACTIVITY_FORM2_AMPHOE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_ACTIVITY_FORM2_AMPHOE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PROVINCE_CODE', 'AMPHOE_CODE', 'NP_WORK_GROUP_ACTIVITY_ID', 'YEAR', 'MONTH', 'SUBJECT', 'SUBJECT_LOCATION', 'IMAGE_PATH', 'START_DATE', 'END_DATE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['NP_WORK_GROUP_ACTIVITY_ID', 'MONTH', 'PARTICIPANTS_NUM', 'SEQ'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_CODE', 'YEAR'], 'string', 'max' => 4],
            [['SUBJECT', 'SUBJECT_LOCATION', 'IMAGE_PATH', 'IMAGE_PATH_1', 'IMAGE_PATH_2', 'IMAGE_PATH_3', 'IMAGE_PATH_4'], 'string', 'max' => 500],
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
            'NP_ACTIVITY_FORM2_AMPHOE_ID' => 'Np  Activity  Form2  Amphor  ID',
            'PROVINCE_CODE' => 'Province  Code',
            'AMPHOE_CODE' => 'Amphoe  Code',
            'NP_WORK_GROUP_ACTIVITY_ID' => 'รายงานผลการปฏิบัติงาน',
            'YEAR' => 'ประจำปี',
            'MONTH' => 'เดือน',
            'SUBJECT' => 'การบูรณาการ',
            'SUBJECT_LOCATION' => 'สถานที่ดำเนินการ',
            'IMAGE_PATH' => 'ภาพกิจกรรม 1',
            'IMAGE_PATH_1' => 'ภาพกิจกรรม 2',
            'IMAGE_PATH_2' => 'ภาพกิจกรรม 3',
            'IMAGE_PATH_3' => 'ภาพกิจกรรม 4',
            'IMAGE_PATH_4' => 'ภาพกิจกรรม 5',
            'PARTICIPANTS_NUM' => 'Participants  Num',
            'START_DATE' => 'วันที่เริ่มดำเนินการ',
            'END_DATE' => 'วันที่สิ้นสุดดำเนินการ',
            'SEQ' => 'Seq',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
            'npactivity' => 'บันทึก การรายงานผลการปฏิบัติงานประจำเดือน (2)',
        ];
    }
    
    public static function getNewID()
    {
    	$command = Yii::$app->db->createCommand("select np_activity_form2_amphoe_id.nextval as ID from dual");
    	$result= $command->queryAll();
    	return $result[0]['ID'];
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
