<?php

namespace app\models;

use Yii;

use yii\base\Model;
use app\models\WA_PROVINCE;

/**
 * This is the model class for table "CONST_PROJ".
 *
 * @property  $CONST_PROJ_ID
 * @property  $PROJ_NAME_TH
 * @property  $PROJ_NAME_EN
 * @property  $BUDGET
 * @property  $CONTRACT_NO
 * @property  $START_DATE
 * @property  $END_DATE
 * @property  $CONTRACTOR
 * @property  $CONTRACTOR_ADDR
 * @property  $GRANT_ORG
 * @property  $RESP1
 * @property  $RESP2
 * @property  $RESP3
 * @property  $PLACE
 * @property  $ADDR
 * @property  $MOO
 * @property  $SOI
 * @property  $ROAD
 * @property  $TUMBOL
 * @property  $AMPHOE_CODE
 * @property  $PROVINCE_CODE
 * @property  $POST_CODE
 * @property  $LATI
 * @property  $LONGI
 * @property  $TEL
 * @property  $FAX
 * @property  $MOBILE
 * @property  $EMAIL
 * @property  $IMAGE_PATH
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class CONST_PROJ extends \yii\db\ActiveRecord
{
    /*public $CONST_PROJ_ID ;
    public $PROJ_NAME_TH ;
    public $PROJ_NAME_EN ;
    public $BUDGET ;
    public $CONTRACT_NO ;
    public $START_DATE ;
    public $END_DATE ;
    public $CONTRACTOR ;
    public $CONTRACTOR_ADDR ;
    public $GRANT_ORG ;
    public $RESP1 ;
    public $RESP2 ;
    public $RESP3 ;
    public $PLACE ;
    public $ADDR ;
    public $MOO ;
    public $SOI ;
    public $ROAD ;
    public $TUMBOL ;
    public $AMPHOE_CODE ;
    public $PROVINCE_CODE ;
    public $POST_CODE ;
    public $LATI ;
    public $LONGI ;
    public $TEL ;
    public $FAX ;
    public $MOBILE ;
    public $EMAIL ;
    public $IMAGE_PATH ;
    public $STATUS ;
    public $CREATE_USER_ID ;
    public $CREATE_TIME ;
    public $LAST_UPD_USER_ID ;
    public $LAST_UPD_TIME ;*/
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CONST_PROJ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PROJ_NAME_TH', 'BUDGET', 'CONTRACT_NO', 'START_DATE', 'END_DATE', 'CONTRACTOR_ADDR', 'GRANT_ORG', 'RESP1', 'RESP2', 'RESP3', 'ADDR', 'AMPHOE_CODE', 'PROVINCE_CODE', 'POST_CODE', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['POST_CODE', 'BUDGET'], 'integer'],
            [['PROJ_NAME_TH', 'PROJ_NAME_EN', 'CONTRACTOR', 'CONTRACTOR_ADDR', 'GRANT_ORG', 'RESP1', 'RESP2', 'RESP3', 'PLACE', 'IMAGE_PATH'], 'string', 'max' => 255],
            [['CONTRACT_NO', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['ADDR', 'MOO'], 'string', 'max' => 15],
            [['SOI'], 'string', 'max' => 200],
            [['ROAD', 'TUMBOL', 'EMAIL'], 'string', 'max' => 100],
            [['AMPHOE_CODE'], 'string', 'max' => 4],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['LATI', 'LONGI', 'TEL', 'FAX', 'MOBILE'], 'string', 'max' => 30],
            [['STATUS'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CONST_PROJ_ID' => 'Const  Proj  ID',
            'PROJ_NAME_TH' => 'ชื่อโครงการก่อสร้างสนามกีฬา',
            'PROJ_NAME_EN' => 'Proj  Name  En',
            'BUDGET' => 'งบประมาณ (บาท)',
            'CONTRACT_NO' => 'เลขที่สัญญา',
            'START_DATE' => 'วันเริ่มสัญญา',
            'END_DATE' => 'วันสิ้นสุดสัญญา',
            'CONTRACTOR' => 'บริษั่ทผู้รับเหมา',
            'CONTRACTOR_ADDR' => 'ที่อยู่',
            'GRANT_ORG' => 'หน่วยงานรับผิดชอบ',
            'RESP1' => 'ผู้รับผิดชอบ 1',
            'RESP2' => 'ผู้รับผิดชอบ 2',
            'RESP3' => 'ผู้รับผิดชอบ 3',
            'PLACE' => 'ชื่อที่ตั้ง-สถานที่',
            'ADDR' => 'เลขที่',
            'MOO' => 'หมู่ที่',
            'SOI' => 'ซอย',
            'ROAD' => 'ถนน',
            'TUMBOL' => 'ตำบล',
            'AMPHOE_CODE' => 'อำเภอ',
            'PROVINCE_CODE' => 'จังหวัด',
            'POST_CODE' => 'รหัสไปรษณีย์',
            'LATI' => 'ละติจูด',
            'LONGI' => 'ลองติจูด',
            'TEL' => 'โทรศัพท์',
            'FAX' => 'โทรสาร',
            'MOBILE' => 'โทรศัพท์มือถือ',
            'EMAIL' => 'อีเมล์',
            'IMAGE_PATH' => 'Image  Path',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'Create  User  ID',
            'CREATE_TIME' => 'Create  Time',
            'LAST_UPD_USER_ID' => 'Last  Upd  User  ID',
            'LAST_UPD_TIME' => 'Last  Upd  Time',
            'HEADER1' => 'รายละเอียดโครงการ' ,
            'HEADER2' => 'ที่ตั้งและรายละเอียดสถานที่ก่อสร้าง' ,
            'HEADER3' => 'บริษัทผู้รับเหมา' ,
            'HEADER4' => 'พิกัด' ,
            
        ];
    }
    
    public static function getNewID(){
    	$command = Yii::$app->db->createCommand("select const_proj_id_seq.nextval as ID from dual");
		$result= $command->queryAll();
		return $result[0]['ID'];
    }
}
