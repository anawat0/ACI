<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "NP_SAT_SURVEY_AMPHOE".
 *
 * @property  $NP_SAT_SURVEY_AMPHOE_ID
 * @property  $PROVINCE_CODE
 * @property  $AMPHOE_CODE
 * @property  $YEAR
 * @property  $MONTH
 * @property  $NUM_SURVEY
 * @property  $NUM_MALE
 * @property  $NUM_FEMALE
 * @property  $AGE_1
 * @property  $AGE_2
 * @property  $AGE_3
 * @property  $AGE_4
 * @property  $AGE_5
 * @property  $EDU_1
 * @property  $EDU_2
 * @property  $EDU_3
 * @property  $EDU_4
 * @property  $EDU_5
 * @property  $EDU_6
 * @property  $SINGLE
 * @property  $MARRIAGE
 * @property  $WIDOWED
 * @property  $WORK_1
 * @property  $WORK_2
 * @property  $WORK_3
 * @property  $WORK_4
 * @property  $WORK_5
 * @property  $WORK_6
 * @property  $WORK_7
 * @property  $WORK_8
 * @property  $WORK_9
 * @property  $KPI_1
 * @property  $KPI_2
 * @property  $KPI_3
 * @property  $KPI_4
 * @property  $KPI_5
 * @property  $KPI_6
 * @property  $KPI_7
 * @property  $KPI_8
 * @property  $KPI_9
 * @property  $KPI_10
 * @property  $STATUS
 * @property  $CREATE_USER_ID
 * @property  $CREATE_TIME
 * @property  $LAST_UPD_USER_ID
 * @property  $LAST_UPD_TIME
 */
class NP_SAT_SURVEY_AMPHOE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NP_SAT_SURVEY_AMPHOE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PROVINCE_CODE', 'AMPHOE_CODE', 'YEAR', 'MONTH', 'STATUS', 'CREATE_USER_ID', 'CREATE_TIME', 'LAST_UPD_USER_ID', 'LAST_UPD_TIME'], 'required'],
            [['MONTH', 'NUM_SURVEY', 'NUM_MALE', 'NUM_FEMALE', 'AGE_1', 'AGE_2', 'AGE_3', 'AGE_4', 'AGE_5', 'EDU_1', 'EDU_2', 'EDU_3', 'EDU_4', 'EDU_5', 'EDU_6', 'SINGLE', 'MARRIAGE', 'WIDOWED', 'WORK_1', 'WORK_2', 'WORK_3', 'WORK_4', 'WORK_5', 'WORK_6', 'WORK_7', 'WORK_8', 'WORK_9'], 'integer'],
            [['PROVINCE_CODE'], 'string', 'max' => 2],
            [['AMPHOE_CODE', 'YEAR'], 'string', 'max' => 4],
            [['KPI_1', 'KPI_2', 'KPI_3', 'KPI_4', 'KPI_5', 'KPI_6', 'KPI_7', 'KPI_8', 'KPI_9', 'KPI_10', 'CREATE_USER_ID', 'LAST_UPD_USER_ID'], 'string', 'max' => 50],
            [['STATUS'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NP_SAT_SURVEY_AMPHOE_ID' => 'เลขที่อ้ำงอิง ตำรำงบันทึกผลสำรวจควำมพึงพอใจ(ศูนย์กำรเรียนรู้ฯ) ประจำอำเภอ',
            'PROVINCE_CODE' => 'จังหวัด',
            'AMPHOE_CODE' => 'อำเภอ',
            'YEAR' => 'ประจำปี',
            'MONTH' => 'เดือน',
            'NUM_SURVEY' => 'Num  Survey',
            'NUM_MALE' => 'ชาย',
            'NUM_FEMALE' => 'หญิง',
            'AGE_1' => '3-5',
            'AGE_2' => '6-24',
            'AGE_3' => '25-34',
            'AGE_4' => '35-60',
            'AGE_5' => '> 60',
            'EDU_1' => 'ประถมศึกษา',
            'EDU_2' => 'มันธยมศึกษาตอนต้น',
            'EDU_3' => 'มัธยมศึกษาตอนปลาย',
            'EDU_4' => 'ปวส./อนุปริญญา',
            'EDU_5' => 'ปริญญาตรี',
            'EDU_6' => 'สูงกว่าปริญญาตรี',
            'SINGLE' => 'โสด',
            'MARRIAGE' => 'สมรส',
            'WIDOWED' => 'หม้าย',
            'WORK_1' => 'นักเรียน/นักศึกษา',
            'WORK_2' => 'ข้าราชการ/พนักงานราชการ',
            'WORK_3' => 'ข้าราชการบำนาญ',
            'WORK_4' => 'เจ้าของกิจการ/ธุรกิจ',
            'WORK_5' => 'ค้าขาย',
            'WORK_6' => 'แม่บ้าน/พ่อบ้าน',
            'WORK_7' => 'เกษตรกร',
            'WORK_8' => 'รับจ้าง',
            'WORK_9' => 'อื่นๆ',
            'KPI_1' => 'คะแนนควำมพึงพอใจ ด้ำนบุคลำกร / มีควำมรู้และควำมเชี่ยวชำญในงำนที่',
            'KPI_2' => 'คะแนนควำมพึงพอใจ ด้ำนบุคลำกร / มีควำมกระตือรือร้นและเต็มใจให้บริกำร',
            'KPI_3' => 'คะแนนควำมพึงพอใจ ด้ำนบุคลำกร / ให้บริำกำรด้วยควำมสุภำพและเหมำะสม',
            'KPI_4' => 'คะแนนควำมพึงพอใจ ด้ำนบริกำร / แจ้งขอบเขตกำรให้บริกำรไว้อย่ำงชัดเจน',
            'KPI_5' => 'คะแนนควำมพึงพอใจ ด้ำนบริกำร / บริกำรได้อย่ำงรวดเร็วคล่องตัวและมี',
            'KPI_6' => 'คะแนนควำมพึงพอใจ ด้ำนบริกำร / ให้ควำมรู้ที่สอดคล้องกับควำมต้องกำร',
            'KPI_7' => 'คะแนนควำมพึงพอใจ ด้ำนบริกำร / ให้คำปรึกษำแนะนำได้อย่ำงถูกต้องและชัดเจน',
            'KPI_8' => 'คะแนนควำมพึงพอใจ ด้ำนบริกำร / เผยแพร่ข้อมูลข่ำวสำรอย่ำงทั่วถึงและต่อเนื่อง',
            'KPI_9' => 'คะแนนควำมพึงพอใจ ด้ำนสิ่งอำนวยควำมสะดวก / สื่อ/คู่มือ/อุปกรณ์กีฬำ สอดคล้องกับควำมต้องกำร',
            'KPI_10' => 'คะแนนควำมพึงพอใจ ด้ำนสิ่งอำนวยควำมสะดวก / สื่อ/คู่มือ/อุปกรณ์กีฬำ เพียงพอต่อควำมต้องกำร',
            'STATUS' => 'สถานะ',
            'CREATE_USER_ID' => 'ผู้บันทึกข้อมูล',
            'CREATE_TIME' => 'เวลำที่บันทึกข้อมูล',
            'LAST_UPD_USER_ID' => 'รหัสผู้ปรับปรุงข้อมูล',
            'LAST_UPD_TIME' => 'เวลำที่ปรับปรุงข้อมูล',
            'HEADER_SECTION_1' => 'ข้อมูลทั่วไปของผู้กรอกแบบสอบถาม',
            'HEADER_SECTION_2' => 'ความพึงพอใจ/ไม่พึงพอใจต่อการให้บริการ'
        ];
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
        $command = Yii::$app->db->createCommand("select np_sat_survey_amphoe_id_seq.nextval as ID from dual");
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
