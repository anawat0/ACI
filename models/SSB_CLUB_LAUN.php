<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SSB_CLUB_LAUN".
 *
 * @property  $SSB_CLUB_LAUN_ID
 * @property  $SSB_CLUB_ID
 * @property  $MONTH
 * @property  $YEAR
 * @property  $PROVINCE_CODE
 * @property  $ACT_NAME_TH
 * @property  $ACT_NAME_EN
 * @property  $AGE_MALE_0
 * @property  $AGE_MALE_1
 * @property  $AGE_MALE_2
 * @property  $AGE_MALE_3
 * @property  $AGE_FEMALE_0
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
 *
 * @property SSBCLUB $1
 */
class SSB_CLUB_LAUN extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SSB_CLUB_LAUN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SSB_CLUB_ID', 'MONTH', 'PROVINCE_CODE', 'ACT_NAME_TH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['SSB_CLUB_ID', 'MONTH', 'AGE_MALE_0', 'AGE_MALE_1', 'AGE_MALE_2', 'AGE_MALE_3', 'AGE_FEMALE_0', 'AGE_FEMALE_1', 'AGE_FEMALE_2', 'AGE_FEMALE_3', 'APPROVED_BY'], 'integer'],
            [['YEAR'], 'string', 'max' => 4],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['ACT_NAME_TH', 'ACT_NAME_EN'], 'string', 'max' => 255],
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
            'SSB_CLUB_LAUN_ID' => 'เลขที่อ้างอิง ตารางบันทึกผลดำเนินงานชมรม',
            'SSB_CLUB_ID' => 'เลขที่อ้างอิง ตารางชมรม',
            'MONTH' => 'เดือน',
            'YEAR' => 'ปี',
            'PROVINCE_CODE' => 'รหัสจังหวัด',
            'ACT_NAME_TH' => 'ชื่อกิจกรรม (ไทย)',
            'ACT_NAME_EN' => 'ชื่อกิจกรรม (eng)',
            'AGE_MALE_0' => 'จำนวนผู้ร่วมกิจกรรมชาย อายุ 3-5 ปี',
            'AGE_MALE_1' => 'จำนวนผู้ร่วมกิจกรรมชาย อายุ 6-24 ปี',
            'AGE_MALE_2' => 'จำนวนผู้ร่วมกิจกรรมชาย อายุ 25-60 ปี',
            'AGE_MALE_3' => 'จำนวนผู้ร่วมกิจกรรมชาย อายุ 61 ปีขึ้นไป',
            'AGE_FEMALE_0' => 'จำนวนผู้ร่วมกิจกรรมหญิง อายุ 3-5 ปี',
            'AGE_FEMALE_1' => 'จำนวนผู้ร่วมกิจกรรมหญิง อายุ 6-24 ปี',
            'AGE_FEMALE_2' => 'จำนวนผู้ร่วมกิจกรรมหญิง อายุ 25-60 ปี',
            'AGE_FEMALE_3' => 'จำนวนผู้ร่วมกิจกรรมหญิง อายุ 60 ปีขึ้นไป',
            'IMAGE_PATH' => 'ภาพกิจกรรม',
            'APPROVED_BY' => 'อนุมัติโดย',
            'APPROVED_DATE' => 'วันที่อนุมัติ',
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
    public function getSsbClub()
    {
        return $this->hasOne(SSB_CLUB::className(), ['SSB_CLUB_ID' => 'SSB_CLUB_ID']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getWaProvince()
    {
        return $this->hasOne(WA_PROVINCE::className(), ['PROVINCE_CODE' => 'PROVINCE_CODE']);
    }

    public static function getNewID()
    {
    	$command = Yii::$app->db->createCommand("select ssb_club_laun_id_seq.nextval as ID from dual");
    	$result= $command->queryAll();
    	return $result[0]['ID'];
    }
}
