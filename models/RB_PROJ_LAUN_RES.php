<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RB_PROJ_LAUN_RES".
 *
 * @property  $RB_PROJ_LAUN_RES_ID
 * @property  $RB_PROJ_LAUN_ID
 * @property  $RB_SUB_PROJ_ID
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $BUDGET_YEAR
 * @property  $MONTH
 * @property  $ACT_NAME_TH
 * @property  $ACT_NAME_EN
 * @property  $FROM_DATE
 * @property  $TO_DATE
 * @property  $PLACE
 * @property  $ORG_RESPON_NAME
 * @property  $BUDGET
 * @property  $PEOPLE_MALE
 * @property  $PEOPLE_FEMALE
 * @property  $YOUTH_MALE
 * @property  $YOUTH_FEMALE
 * @property  $OP_ITSELF_FLAG
 * @property  $OP_TOGETHER_FLAG
 * @property  $OP_PATH_FLAG
 * @property  $IMAGE_PATH
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 *
 * @property RBPROJLAUN $1
 * @property RBSUBPROJ $10
 */
class RB_PROJ_LAUN_RES extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'RB_PROJ_LAUN_RES';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RB_PROJ_LAUN_ID', 'RB_SUB_PROJ_ID', 'PROVINCE_CODE', 'BUDGET_YEAR', 'MONTH', 'ACT_NAME_TH', 'FROM_DATE', 'TO_DATE', 'ORG_RESPON_NAME', 'OP_FLAG', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['RB_PROJ_LAUN_ID', 'RB_SUB_PROJ_ID', 'MONTH', 'PEOPLE_MALE', 'PEOPLE_FEMALE', 'YOUTH_MALE', 'YOUTH_FEMALE', 'BUDGET'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_CODE', 'BUDGET_YEAR'], 'string', 'max' => 4],
            [['ACT_NAME_TH', 'ACT_NAME_EN'], 'string', 'max' => 100],
            [['PLACE', 'ORG_RESPON_NAME'], 'string', 'max' => 255],
            [['OP_FLAG', 'STATUS'], 'string', 'max' => 1],
            [['IMAGE_PATH', 'IMAGE_PATH_1', 'IMAGE_PATH_2', 'IMAGE_PATH_3', 'IMAGE_PATH_4',], 'string', 'max' => 150],
            [['CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RB_PROJ_LAUN_RES_ID' => 'เลขที่อ้างอิง ตารางผลการดำเนินแผนงาน-โครงการ ',
            'RB_PROJ_LAUN_ID' => 'เลขที่อ้างอิง ตารางแผนงาน-โครงการ ที่ดำเนินการ ',
            'RB_SUB_PROJ_ID' => 'เลขที่่อ้างอิง ตารางแผนงาน/โครงการ ย่อย',
            'PROVINCE_CODE' => 'รหัสจังหวัด',
            'AMPHOE_CODE' => 'รหัสอำเภอ',
            'BUDGET_YEAR' => 'ปีงบประมาณ',
            'MONTH' => 'เดือน',
            'ACT_NAME_TH' => 'ชื่อกิจกรรม (ไทย)',
            'ACT_NAME_EN' => 'ชื่อกิจกรรม (eng)',
            'FROM_DATE' => 'วันที่เริ่มดำเนินการ',
            'TO_DATE' => 'วันที่สิ้นสุดดำเนินการ',
            'PLACE' => 'สถานที่จัดงาน',
            'ORG_RESPON_NAME' => 'หน่วยงานที่จัด',
            'BUDGET' => 'งบประมาณ',
            'PEOPLE_MALE' => 'จำนวนประชาชนชาย ที่ร่วมงาน',
            'PEOPLE_FEMALE' => 'จำนวนประชาชนหญิง ที่ร่วมงาน',
            'YOUTH_MALE' => 'จำนวนเยาวชนชาย ที่ร่วมงาน',
            'YOUTH_FEMALE' => 'จำนวนเยาวชนหญิง ที่ร่วมงาน',
            'OP_FLAG' => 'ลักษณะงาน',
            'IMAGE_PATH' => 'รูปกิจกรรม 1',
            'IMAGE_PATH_1' => 'รูปกิจกรรม 2',
            'IMAGE_PATH_2' => 'รูปกิจกรรม 3',
            'IMAGE_PATH_3' => 'รูปกิจกรรม 4',
            'IMAGE_PATH_4' => 'รูปกิจกรรม 5',
            'STATUS' => 'สถานะสถานะ \'A\' : ใช้งาน , \'C\' : ยกเลิก',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลาที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลาที่ปรับปรุงข้อมูล',
            'HEADER_SECTION_1' => 'บันทึกผลกำรดำเนินกำรประจำเดือน สนก.'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbProjLaun()
    {
        return $this->hasOne(RB_PROJ_LAUN::className(), ['RB_PROJ_LAUN_ID' => 'RB_PROJ_LAUN_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbSubProj()
    {
        return $this->hasOne(RB_SUB_PROJ::className(), ['RB_SUB_PROJ_ID' => 'RB_SUB_PROJ_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaProvince()
    {
        return $this->hasOne(WA_PROVINCE::className(), ['PROVINCE_CODE' => 'PROVINCE_CODE']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaAmphoe()
    {
        return $this->hasOne(WA_AMPHOE::className(), ['AMPHOE_CODE' => 'AMPHOE_CODE']);
    }

    public static function getNewID()
    {
        $command = Yii::$app->db->createCommand("select rb_proj_laun_res_id_seq.nextval as ID from dual");
        $result= $command->queryAll();
        return $result[0]['ID'];
    }

    public static function getOpFlagText($opFlag) {
        $result = '';

        switch ($opFlag) {
            case '1':
                $result = 'ทำงาน';
                break;
            case '2':
                $result = 'ร่วมกัน';
                break;
            case '3':
                $result = 'ร่วมงาน';
                break;
        }

        return $result;
    }
}
