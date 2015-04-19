<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SSB_12AUG".
 *
 * @property  $SSB_12AUG_ID
 * @property  $ACT_NAME_TH
 * @property  $ACT_NAME_EN
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $YEAR
 * @property  $AGE_MALE_1
 * @property  $AGE_MALE_2
 * @property  $AGE_MALE_3
 * @property  $AGE_FEMALE_1
 * @property  $AGE_FEMALE_2
 * @property  $AGE_FEMALE_3
 * @property  $IMAGE_PATH
 * @property  $APPROVED_BY
 * @property  $APPROVED_DATE
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class SSB_12AUG extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SSB_12AUG';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ACT_NAME_TH', 'PROVINCE_CODE', 'YEAR', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['AGE_MALE_1', 'AGE_MALE_2', 'AGE_MALE_3', 'AGE_FEMALE_1', 'AGE_FEMALE_2', 'AGE_FEMALE_3', 'APPROVED_BY'], 'integer'],
            [['ACT_NAME_TH', 'ACT_NAME_EN'], 'string', 'max' => 255],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_CODE', 'YEAR'], 'string', 'max' => 4],
            [['IMAGE_PATH'], 'string', 'max' => 100],
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
            'SSB_12AUG_ID' => 'เลขที่อ้างอิง ตาราง กิจกรรมรวมพลคนรักแอโรบิก 12 สิงหามหาราชินี',
            'ACT_NAME_TH' => 'Act  Name  Th',
            'ACT_NAME_EN' => 'ชื่อกิจกรรม (eng)',
            'PROVINCE_CODE' => 'รหัสจังหวัด',
            'AMPHOE_CODE' => 'รหัสอำเภอ',
            'YEAR' => 'ปี',
            'AGE_MALE_1' => 'ผู้ร่วมกิจกรรมชาย อายุ 5-24 ปี',
            'AGE_MALE_2' => 'ผู้ร่วมกิจกรรมชาย อายุ 25-60 ปี',
            'AGE_MALE_3' => 'ผู้ร่วมกิจกรรมชาย อายุ  60 ปีขึ้นไป',
            'AGE_FEMALE_1' => 'ผู้ร่วมกิจกรรมหญิง อายุ 5-24 ปี',
            'AGE_FEMALE_2' => 'ผู้ร่วมกิจกรรมหญิง อายุ 25-60 ปี',
            'AGE_FEMALE_3' => 'ผู้ร่วมกิจกรรมหญิง อายุ 60 ปีขึ้นไป',
            'IMAGE_PATH' => 'ภาพกิจกรรม',
            'APPROVED_BY' => 'อนุมัติโดย',
            'APPROVED_DATE' => 'วันทีอนุมัติ',
            'STATUS' => 'สถานะสถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select SSB_12AUG_ID_SEQ.nextval as ID from dual");
	$result= $command->queryAll();
	return $result[0]['ID'];
    }    
}
