<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_STAFF".
 *
 * @property  $STAFF_ID
 * @property  $YEAR
 * @property  $FIRST_NAME
 * @property  $LAST_NAME
 * @property  $ORG_TYPE
 * @property  $ORG_PROVINCE_CODE
 * @property  $ORG_AMPHOE_CODE
 * @property  $PRESENT_HOUSE_NO
 * @property  $PRESENT_VILLAGE_NO
 * @property  $PRESENT_VILLAGE_NAME
 * @property  $PRESENT_SOI
 * @property  $PRESENT_ROAD
 * @property  $PRESENT_PROVINCE_CODE
 * @property  $PRESENT_AMPHOE_CODE
 * @property  $PRESENT_TAMBOL_CODE
 * @property  $PRESENT_POST_CODE
 * @property  $PERM_HOUSE_NO
 * @property  $PERM_VILLAGE_NO
 * @property  $PERM_VILLAGE_NAME
 * @property  $PERM_SOI
 * @property  $PERM_ROAD
 * @property  $PERM_PROVINCE_CODE
 * @property  $PERM_AMPHOE_CODE
 * @property  $PERM_TAMBOL_CODE
 * @property  $PERM_POST_CODE
 * @property  $TEL_NO
 * @property  $MOBILE_NO
 * @property  $BIRTH_DATE
 * @property  $ID_NO
 * @property  $ID_NO_PLACE
 * @property  $ID_NO_EXPIRE_DATE
 * @property  $NATIONLITY
 * @property  $RACE
 * @property  $RELIGIOUS
 * @property  $WEIGHT
 * @property  $HEIGHT
 * @property  $MARITAL_STATUS
 * @property  $BECH_INSTITUTE_NAME
 * @property  $BECH_MAJOR
 * @property  $BECH_YEAR_BEGIN
 * @property  $BECH_YEAR_FINISH
 * @property  $BECH_GRADE
 * @property  $MAST_INSTITUTE_NAME
 * @property  $MAST_MAJOR
 * @property  $MAST_YEAR_BEGIN
 * @property  $MAST_YEAR_FINISH
 * @property  $MAST_GRADE
 * @property  $CONT_FIRST_NAME
 * @property  $CONT_LAST_NAME
 * @property  $CONT_PHONE_NO
 * @property  $CONT_RELATE
 * @property  $CONT_HOUSE_NO
 * @property  $CONT_VILLAGE_NO
 * @property  $CONT_VILLAGE_NAME
 * @property  $CONT_ROAD
 * @property  $CONT_PROVINCE_CODE
 * @property  $CONT_AMPHOE_CODE
 * @property  $CONT_TAMBOL_CODE
 * @property  $CONT_POST_CODE
 * @property  $IMAGE_PATH
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class NP_STAFF extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_STAFF';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['YEAR', 'ORG_TYPE', 'FIRST_NAME', 'LAST_NAME', 'PRESENT_HOUSE_NO', 'PRESENT_PROVINCE_CODE', 'PRESENT_AMPHOE_CODE', 'PRESENT_TAMBOL_CODE', 'PERM_PROVINCE_CODE', 'PERM_AMPHOE_CODE', 'PERM_TAMBOL_CODE', 'PERM_POST_CODE', 'MOBILE_NO', 'BIRTH_DATE', 'ID_NO', 'ID_NO_PLACE', 'ID_NO_EXPIRE_DATE', 'NATIONLITY', 'RACE', 'RELIGIOUS', 'WEIGHT', 'HEIGHT', 'MARITAL_STATUS', 'CONT_FIRST_NAME', 'CONT_LAST_NAME', 'CONT_PHONE_NO', 'CONT_RELATE', 'CONT_HOUSE_NO', 'CONT_PROVINCE_CODE', 'CONT_AMPHOE_CODE', 'CONT_TAMBOL_CODE', 'CONT_POST_CODE', 'IMAGE_PATH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['WEIGHT', 'HEIGHT', 'ORG_TEL_NO', 'ORG_FAX_NO', 'PRESENT_POST_CODE', 'PERM_POST_CODE', 'CONT_POST_CODE'], 'integer'],
            [['YEAR', 'ORG_AMPHOE_CODE', 'PRESENT_AMPHOE_CODE', 'PERM_AMPHOE_CODE', 'BECH_YEAR_BEGIN', 'BECH_YEAR_FINISH', 'MAST_YEAR_BEGIN', 'MAST_YEAR_FINISH', 'CONT_AMPHOE_CODE'], 'string', 'max' => 4],
            [['FIRST_NAME', 'LAST_NAME', 'PRESENT_VILLAGE_NAME', 'PRESENT_TAMBOL_CODE', 'PERM_VILLAGE_NAME', 'PERM_TAMBOL_CODE', 'ID_NO_PLACE', 'CONT_TAMBOL_CODE'], 'string', 'max' => 100],
            [['ORG_TYPE', 'STATUS'], 'string', 'max' => 1],
            [['ORG_PROVINCE_CODE', 'PRESENT_PROVINCE_CODE', 'PERM_PROVINCE_CODE', 'MARITAL_STATUS', 'CONT_PROVINCE_CODE'], 'string', 'max' => 2],
            [['PRESENT_HOUSE_NO', 'PRESENT_VILLAGE_NO', 'PERM_HOUSE_NO', 'BECH_GRADE', 'MAST_GRADE', 'CONT_HOUSE_NO', 'CONT_VILLAGE_NO', 'ORG_TEL_NO_EXT', 'MOBILE_NO'], 'string', 'max' => 10],
            [['PRESENT_SOI', 'PRESENT_ROAD', 'PERM_SOI', 'PERM_ROAD', 'NATIONLITY', 'RACE', 'RELIGIOUS', 'BECH_INSTITUTE_NAME', 'BECH_MAJOR', 'MAST_INSTITUTE_NAME', 'MAST_MAJOR', 'CONT_FIRST_NAME', 'CONT_LAST_NAME', 'CONT_RELATE', 'CONT_VILLAGE_NAME', 'CONT_ROAD', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['PERM_VILLAGE_NO', 'PRESENT_POST_CODE', 'PERM_POST_CODE', 'CONT_POST_CODE'], 'string', 'max' => 5],
            [['TEL_NO', 'CONT_PHONE_NO'], 'string', 'max' => 20],
            [['ID_NO'], 'string', 'max' => 13],
            [['ORG_TEL_NO', 'ORG_FAX_NO'], 'string', 'max' => 9],
            [['BLOOD_GROUP'], 'string', 'max' => 2],
            [['IMAGE_PATH'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [ 
			'YEAR' => 'ประจำปี',
			'ORG_TYPE' => 'ที่ทำงาน',
			'FIRST_NAME' => 'ชื่อ',
			'LAST_NAME' => 'นามสกุล',
			'ORG_PROVINCE_CODE' => 'จังหวัด',
			'ORG_AMPHOE_CODE' => 'อำเภอ',
			'PRESENT_HOUSE_NO' => 'เลขที่',
			'PRESENT_VILLAGE_NO' => 'หมู่ที่',
			'PRESENT_VILLAGE_NAME' => 'หมู่บ้าน/อาคาร',
			'PRESENT_SOI' => '',
			'PRESENT_ROAD' => 'ถนน',
			'PRESENT_PROVINCE_CODE' => 'จังหวัด',
			'PRESENT_AMPHOE_CODE' => 'อำเภอ',
			'PRESENT_TAMBOL_CODE' => 'ตำบล',
			'PRESENT_POST_CODE' => 'รหัสไปรษณีย์',
			'PERM_HOUSE_NO' => 'เลขที่',
			'PERM_VILLAGE_NO' => 'หมู่ที่',
			'PERM_VILLAGE_NAME' => 'หมู่บ้าน/อาคาร',
			'PERM_SOI' => '',
			'PERM_ROAD' => 'ถนน',
			'PERM_PROVINCE_CODE' => 'จังหวัด',
			'PERM_AMPHOE_CODE' => 'อำเภอ',
			'PERM_TAMBOL_CODE' => 'ตำบล',
			'PERM_POST_CODE' => 'รหัสไปรษณีย์',
			'CONT_HOUSE_NO' => 'เลขที่',
			'CONT_VILLAGE_NO' => 'หมู่ที่',
			'CONT_VILLAGE_NAME' => 'หมู่บ้าน/อาคาร',
			'CONT_SOI' => '',
			'CONT_ROAD' => 'ถนน',
			'CONT_PROVINCE_CODE' => 'จังหวัด',
			'CONT_AMPHOE_CODE' => 'อำเภอ',
			'CONT_TAMBOL_CODE' => 'ตำบล',
			'CONT_POST_CODE' => 'รหัสไปรษณีย์',
			'TEL_NO' => 'โทรศัพท์บ้าน',
			'MOBILE_NO' => 'โทรศัพท์เคลื่อนที่',
			'BIRTH_DATE' => 'วัน/เดือน/ปีเกิด',
			'ID_NO' => 'เลขที่บัตรประชาชน',
			'ID_NO_PLACE' => 'ออกที่',
			'ID_NO_EXPIRE_DATE' => 'วัน/เดือน/ปี ที่หมดอายุ',
			'NATIONLITY' => 'สัญชาติ',
			'RACE' => 'เชื้อชาติ',
			'RELIGIOUS' => 'ศาสนา',
			'WEIGHT' => 'น้ำหนัก',
			'HEIGHT' => 'ส่วนสูง',
			'RELIGIOUS' => 'ศาสนา',
			'MARITAL_STATUS' => 'สถานภาพ',
			'BECH_INSTITUTE_NAME' => 'ชื่อสถานศึกษา',
			'BECH_MAJOR' => 'คุณวุฒิ',
			'BECH_YEAR_BEGIN' => 'ปีที่เข้าศึกษา',
			'BECH_YEAR_FINISH' => 'ถึง',
			'BECH_GRADE' => 'เกรดเฉลี่ย',
			'MAST_INSTITUTE_NAME' => 'ชื่อสถานศึกษา',
			'MAST_MAJOR' => 'คุณวุฒิ',
			'MAST_YEAR_BEGIN' => 'ปีที่เข้าศึกษา',
			'MAST_YEAR_FINISH' => 'ถึง',
			'MAST_GRADE' => 'เกรดเฉลี่ย',
			'CONT_FIRST_NAME' => 'ชื่อ',
			'CONT_LAST_NAME' => 'นามสกุล',
			'CONT_PHONE_NO' => 'โทรศัพท์',
			'CONT_RELATE' => 'เกี่ยวข้องเป็น',
			'CONT_HOUSE_NO' => 'เลขที่',
			'CONT_VILLAGE_NO' => 'หมู่ที่',
			'CONT_VILLAGE_NAME' => 'หมู่บ้าน/อาคาร',
			'CONT_ROAD' => 'ถนน',
			'CONT_PROVINCE_CODE' => 'จังหวัด',
			'CONT_AMPHOE_CODE' => 'อำเภอ',
			'CONT_TAMBOL_CODE' => 'ตำบล',
			'CONT_POST_CODE' => 'รหัสไปรษณีย์',
			'IMAGE_PATH' => 'ไฟล์ภาพ',
			'HEADER1' => 'ประวัติส่วนตัว',
			'HEADER2' => 'ประวัติการศึกษา',
			'HEADER3' => 'บุคคลที่ติดต่อได้ในกรณีเร่งด่วน',
			'HEADER4' => 'รูปภาพ',
        	'AGE' => 'อายุ',
        	'ORG_TEL_NO' => 'หมายเลขโทรศัพท์ ที่ทำงาน', 
			'ORG_TEL_NO_EXT' => 'เบอร์ต่อ',
			'ORG_FAX_NO' => 'หมายเลขโทรสาร ที่ทำงาน',
			'BLOOD_GROUP' => 'กรุ๊ปเลือด',
		];
    }
    
    public static function getNewID()
    {
    	$command = Yii::$app->db->createCommand("select np_staff_id_seq.nextval as ID from dual");
    	$result= $command->queryAll();
    	return $result[0]['ID'];
    }
}
