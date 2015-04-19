<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_ACTIVITY_FORM1_PROVINCE".
 *
 * @property  $NP_ACTIVITY_FORM1_PROVINCE_ID
 * @property  $PROVINCE_CODE
 * @property  $NP_WORK_GROUP_ACTIVITY_ID
 * @property  $YEAR
 * @property  $MONTH
 * @property  $SUBJECT
 * @property  $SUBJECT_DETAIL
 * @property  $SUBJECT_LOCATION
 * @property  $SUBJECT_RESULT
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
class NP_ACTIVITY_FORM1_PROVINCE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_ACTIVITY_FORM1_PROVINCE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PROVINCE_CODE', 'NP_WORK_GROUP_ACTIVITY_ID', 'YEAR', 'MONTH', 'SUBJECT', 'SUBJECT_DETAIL', 'SUBJECT_LOCATION', 'SUBJECT_RESULT', 'IMAGE_PATH', 'PARTICIPANTS_NUM', 'START_DATE', 'END_DATE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['NP_WORK_GROUP_ACTIVITY_ID', 'MONTH', 'PARTICIPANTS_NUM', 'SEQ'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['YEAR'], 'string', 'max' => 4],
            [['SUBJECT', 'SUBJECT_DETAIL', 'SUBJECT_LOCATION', 'SUBJECT_RESULT', 'IMAGE_PATH', 'IMAGE_PATH_1', 'IMAGE_PATH_2', 'IMAGE_PATH_3', 'IMAGE_PATH_4'], 'string', 'max' => 500],
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
            'NP_ACTIVITY_FORM1_PROVINCE_ID' => 'Np  Activity  Form1  Province  ID',
            'PROVINCE_CODE' => 'จังหวัด',
            'NP_WORK_GROUP_ACTIVITY_ID' => 'เวิคกรุ๊ป',
            'YEAR' => 'ปี',
            'MONTH' => 'เดือน',
            'SUBJECT' => 'หัวข้อ',
            'SUBJECT_DETAIL' => 'รายการ',
            'SUBJECT_LOCATION' => 'สถานที่ดำเนินการ',
            'SUBJECT_RESULT' => 'ผลการดำเนินการ',
            'IMAGE_PATH' => 'ภาพกิจกรรม 1',
            'IMAGE_PATH_1' => 'ภาพกิจกรรม 2',
            'IMAGE_PATH_2' => 'ภาพกิจกรรม 3',
            'IMAGE_PATH_3' => 'ภาพกิจกรรม 4',
            'IMAGE_PATH_4' => 'ภาพกิจกรรม 5',
            'PARTICIPANTS_NUM' => 'จำนวนบุคลากร',
            'START_DATE' => 'วันที่เริ่มดำเนินการ',
            'END_DATE' => 'วันที่สิ้นสุด',
            'SEQ' => 'Seq',
            'STATUS' => 'Status',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
        ];
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select NP_ACT_FRM1_PROVINCE_ID_SEQ.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNpSpOpenProvince()
    {
        return $this->hasOne(NP_SP_OPEN_PROVINCE::className(), ['YEAR' => 'YEAR',
                                                                'MONTH' => 'MONTH',
                                                                'PROVINCE_CODE' => 'PROVINCE_CODE']);
    }
}
