<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_NETWORK".
 *
 * @property  $RB_NETWORK_ID
 * @property  $NETWORK_NAME
 * @property  $AMOUNT
 * @property  $SLOGAN
 * @property  $IMAGE_DESC
 * @property  $RB_ACT_1_FLAG
 * @property  $RB_ACT_2_FLAG
 * @property  $RB_ACT_3_FLAG
 * @property  $RB_ACT_4_FLAG
 * @property  $RB_ACT_5_FLAG
 * @property  $RB_ACT_6_FLAG
 * @property  $RB_ACT_7_FLAG
 * @property  $RB_ACT_8_FLAG
 * @property  $RB_ACT_9_FLAG
 * @property  $RB_ACT_OTHER_DESC
 * @property  $LOGO_IMAGE_PATH
 * @property  $PURPOSE
 * @property  $ACT_DESC
 * @property  $ACT_IMAGE_PATH
 * @property  $PRES_FIRST_NAME
 * @property  $PRES_LAST_NAME
 * @property  $PRES_TEL
 * @property  $PRES_FAX
 * @property  $PRES_EMAIL
 * @property  $CO_FIRST_NAME
 * @property  $CO_LAST_NAME
 * @property  $CO_TEL
 * @property  $CO_FAX
 * @property  $CO_EMAIL
 * @property  $ADDRESS_NO
 * @property  $ADDRESS_NAME
 * @property  $MOO
 * @property  $SOI
 * @property  $ROAD
 * @property  $TUMBOL
 * @property  $AMPHOE_CODE
 * @property  $PROVINCE_CODE
 * @property  $POST_CODE
 * @property  $NEAR_PLACE
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class RB_NETWORK extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_NETWORK';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NETWORK_NAME', 'AMOUNT', 'ADDRESS_NO', 'AMPHOE_CODE', 'PROVINCE_CODE', 'POST_CODE', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['AMOUNT', 'POST_CODE'], 'integer'],
            [['NETWORK_NAME', 'RB_ACT_OTHER_DESC', 'LOGO_IMAGE_PATH', 'ACT_IMAGE_PATH', 'PRES_FIRST_NAME', 'PRES_LAST_NAME', 'PRES_EMAIL', 'CO_FIRST_NAME', 'CO_LAST_NAME', 'CO_EMAIL', 'NEAR_PLACE'], 'string', 'max' => 100],
            [['SLOGAN', 'IMAGE_DESC', 'PURPOSE', 'ACT_DESC', 'ADDRESS_NO', 'SOI', 'ROAD', 'TUMBOL'], 'string', 'max' => 255],
            [['RB_ACT_1_FLAG', 'RB_ACT_2_FLAG', 'RB_ACT_3_FLAG', 'RB_ACT_4_FLAG', 'RB_ACT_5_FLAG', 'RB_ACT_6_FLAG', 'RB_ACT_7_FLAG', 'RB_ACT_8_FLAG', 'RB_ACT_9_FLAG'], 'string', 'max' => 1],
            [['PRES_TEL', 'PRES_FAX', 'CO_TEL', 'CO_FAX', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['ADDRESS_NAME'], 'string', 'max' => 20],
            [['MOO'], 'string', 'max' => 5],
            [['AMPHOE_CODE'], 'string', 'max' => 4],
            [['PROVINCE_CODE'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RB_NETWORK_ID' => 'เลขที่อ้างอิง ตารางเครือข่ายนันทนาการ',
            'NETWORK_NAME' => 'ชื่อเครือข่าย',
            'AMOUNT' => 'จำนวนสมาชิก',
            'SLOGAN' => 'สโลแกน',
            'IMAGE_DESC' => 'ภาพลักษณ์',
            'RB_ACT_1_FLAG' => 'ศิลปะ/หัตถกรรม',
            'RB_ACT_2_FLAG' => 'เกม การเล่น และกีฬา',
            'RB_ACT_3_FLAG' => 'เต้นรำ ฟ้อนรำ และรำพื้นเมือง',
            'RB_ACT_4_FLAG' => 'ดนตรีและการร้องเพลง',
            'RB_ACT_5_FLAG' => 'งานอดิเรก',
            'RB_ACT_6_FLAG' => 'ละครและการแสดง',
            'RB_ACT_7_FLAG' => 'กิจกรรมกลางแจ้ง/นอกเมือง',
            'RB_ACT_8_FLAG' => 'กิจกรรมทางสังคม/อาสาสมัคร',
            'RB_ACT_9_FLAG' => 'อื่นๆ',
            'RB_ACT_OTHER_DESC' => 'กิจกรรมอื่นๆ',
            'LOGO_IMAGE_PATH' => 'สัญลักษณ์เครือข่าย',
            'PURPOSE' => 'วัตถุประสงค์ของเครือข่าย',
            'ACT_DESC' => 'ข้อมูลกิจกรรมนันทนาการของเครือข่าย',
            'ACT_IMAGE_PATH' => 'รูปภาพกิจการรมของเครือข่าย',
            'PRES_FIRST_NAME' => 'ชื่อ',
            'PRES_LAST_NAME' => 'นามสกุล',
            'PRES_TEL' => 'หมายเลขโทรศัพท์',
            'PRES_FAX' => 'หมายเลขโทรสาร',
            'PRES_EMAIL' => 'อีเมล์',
            'CO_FIRST_NAME' => 'ชื่อ',
            'CO_LAST_NAME' => 'นามสกุล',
            'CO_TEL' => 'หมายเลขโทรศํพท์',
            'CO_FAX' => 'หมายเลขโทรสาร',
            'CO_EMAIL' => 'อีเมล์',
            'ADDRESS_NO' => 'เลขที่',
            'ADDRESS_NAME' => 'สถานที่ หน่วยงาน หมู่บ้าน',
            'MOO' => 'หมู่ที่',
            'SOI' => 'ซอย',
            'ROAD' => 'ถนน',
            'TUMBOL' => 'ตำบล',
            'AMPHOE_CODE' => 'รหัสอำเภอ',
            'PROVINCE_CODE' => 'รหัสจังหวัด',
            'POST_CODE' => 'รหัสไปรษณีย์',
            'NEAR_PLACE' => 'สถานที่ใกล้เคียง',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
        ];
    }
    
    public static function getNewID(){        
    	$command = Yii::$app->db->createCommand("select RB_NETWORK_ID_SEQ.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }    
}
