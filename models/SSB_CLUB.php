<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SSB_CLUB".
 *
 * @property  $SSB_CLUB_ID
 * @property  $CLUB_NAME_TH
 * @property  $CLUB_NAME_EN
 * @property  $LEADER
 * @property  $ESTABLISH_DATE
 * @property  $TEL
 * @property  $FAX
 * @property  $MOBILE
 * @property  $ADDRESS_NAME
 * @property  $ADDRESS_NO
 * @property  $SOI
 * @property  $ROAD
 * @property  $TUMBOL
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $POST_CODE
 * @property  $BUDGET
 * @property  $AMOUNT
 * @property  $EMAIL
 * @property  $PWD
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property SSBCLUBLAUN[] $sSBCLUBLAUNs
 */
class SSB_CLUB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SSB_CLUB';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CLUB_NAME_TH', 'LEADER', 'ESTABLISH_DATE', 'TEL', 'ADDRESS_NO', 'PROVINCE_CODE', 'AMPHOE_CODE', 'POST_CODE', 'BUDGET', 'EMAIL', 'PWD', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['POST_CODE', 'BUDGET', 'AMOUNT'], 'integer'],
            [['CLUB_NAME_TH', 'CLUB_NAME_EN', 'LEADER', 'ADDRESS_NO', 'SOI', 'ROAD', 'TUMBOL'], 'string', 'max' => 255],
            [['TEL'], 'string', 'max' => 30],
            [['FAX', 'MOBILE', 'ADDRESS_NAME'], 'string', 'max' => 20],
            [['AMPHOE_CODE'], 'string', 'max' => 4],
            [['EMAIL', 'PWD', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['STATUS'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SSB_CLUB_ID' => 'เลขที่อ้างอิง ตารางชมรม',
            'CLUB_NAME_TH' => 'ชื่อชมรม (ไทย)',
            'CLUB_NAME_EN' => 'ชื่อชมรม (en)',
            'LEADER' => 'ประธานชมรม',
            'ESTABLISH_DATE' => 'วันที่ก่อตั้ง',
            'TEL' => 'หมายเลขโทรศัพท์',
            'FAX' => 'หมายเลขโทรสาร',
            'MOBILE' => 'หมายเลขโทรศัพท์เคลื่อนที่',
            'ADDRESS_NAME' => 'สถานที่ หน่วยงาน หมู่บ้าน',
            'ADDRESS_NO' => 'เลขที่',
            'SOI' => 'ซอย',
            'ROAD' => 'ถนน',
            'TUMBOL' => 'ตำบล',
            'PROVINCE_CODE' => 'จังหวัด',
            'AMPHOE_CODE' => 'อำเภอ',
            'POST_CODE' => 'รหัสไปรษณีย์',
            'BUDGET' => 'งบประมาณ',
            'AMOUNT' => 'จำนวนสมาชิก',
            'EMAIL' => 'อีเมล์',
            'PWD' => 'รหัสผ่าน',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
            'HEADER1' => 'ข้อมูลชมรม' ,
            'HEADER2' => 'สถานที่ตั้ง' ,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSSBCLUBLAUNs()
    {
        return $this->hasMany(SSBCLUBLAUN::className(), ['1' => 'SSB_CLUB_ID']);
    }
    
    public static function getNewID()
    {
    	$command = Yii::$app->db->createCommand("select ssb_club_id_seq.nextval as ID from dual");
    	$result= $command->queryAll();
    	return $result[0]['ID'];
    }
    
}
