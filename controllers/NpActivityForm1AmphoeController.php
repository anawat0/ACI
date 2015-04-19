<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\NP_ACTIVITY_FORM1_AMPHOE;
use app\models\NP_SP_OPEN_AMPHOE;
use app\models\SB_PROJ;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\web\Utils;
use yii\web\Authentication;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\base\Exception;

class NpActivityForm1AmphoeController extends AppController
{
	public $title = 'บันทึกผลการปฏิบัติงานประจำเดือน (จพล. ประจำอำเภอ)';
    public $subTitle = 'การรายงานผลการปฏิบัติงานประจำเดือน';
	public $enableCsrfValidation = false;
    public $imagePath = '/npactivityform1amphoe';

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
    	$request = Yii::$app->request;

    	$act_id = $request->post('act_id');
    	$model = new NP_ACTIVITY_FORM1_AMPHOE();
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $arrPlaseSelect = array('' => 'กรุณาเลือก');
        $arrAct =array();
        $arrAct = array('1' => 'กรุณาเลือก');
        $arrAct2 = array('' => 'ทั้งหมด');
    	$sbProj = WA_PROVINCE::find()->all();
        $currentYear = intval(date('Y')) + 543;
        $currentMonth = date('n');

    	$params = array('title' => $this->title.' > '.$this->subTitle,
                        'currentYear' => $currentYear,
                        'currentMonth' => $currentMonth,
                        'rtparams' => $rtparams,
                        'arrPlaseSelect' =>$arrPlaseSelect,
                        'arrAct' =>$arrAct,
                        'arrAct2' =>$arrAct2);

    	$this->addBreadCrumb('NP001 '.$this->title, Url::to(['npworkamphoe/']));
    	$this->addBreadCrumb($this->subTitle);

        return $this->render('index', $params);
        //return $this->render('index', array('$np_activity_form1_amphoe_id' => $np_activity_form1_amphoe_id, 'sbMainProj' => $sbMainProj));
    }
    
    public function actionAdd(){
    	 
    	// 	$waProvince = WA_PROVINCE::find()->asArray()->all();
    	// 	$result = array('waProvince'=>$waProvince);
    
    	$request = Yii::$app->request;
    	//$province = $request->post('province');
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	$id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);
    
//     	$this->title = 'NP001';
    	$act_id = $request->get('act_id');
    	$work_id = $request->get('work_id');
    	$year = $request->get('year');
    	$month = $request->get('month');
    	$province = $request->get('province');
    	$amphoe = $request->get('amphoe');

    	$tmp_params['act_id'] = $act_id;
    	$tmp_params['work_id'] = $work_id;
    	$tmp_params['year'] = $year;
    	$tmp_params['month'] = $month;
    	$tmp_params['province'] = $province;
    	$tmp_params['amphoe'] = $amphoe;
    	
    	$backAction = ['npactivityform1amphoe/', 'rtparams'=>$rtparams];
    	
    	$backAction = BaseArrayHelper::merge($backAction, $tmp_params);
    	
    	$this->addBreadCrumb('NP001 บันทึกผลการปฏิบัติงานประจำเดือน (จพล. ประจำอำเภอ)', Url::to(['npworkamphoe/']));
    	$this->addBreadCrumb('การรายงานผลการปฏิบัติงานประจำเดือน', Url::to($backAction));
    	$this->addBreadCrumb('บันทึก การรายงานผลการปฏิบัติงานประจำเดือน');
    	
    	$params = $tmp_params;
    	$params['backAction'] = $backAction;
    	$params['npactivity'] = array();
		$params['id']=$id;
        $params['isCurrentMonth'] = 'false';
    	if(!empty($id)){
            $params['npactivity'] = NP_ACTIVITY_FORM1_AMPHOE::findOne($id);
            $params['isCurrentMonth'] = (Utils::isCurrentMonth($params['npactivity']['YEAR'],
                                                                         $params['npactivity']['MONTH'],
                                                                         true))? 'true': 'false';
    	}else{
    		$params['npactivity'] = new NP_ACTIVITY_FORM1_AMPHOE();
    	}
    	$params['rtparams']=$rtparams;
        // Yii::trace(print_r($params, true), 'debug');

        return $this->render('add', $params,$province);
    }

    public function actionSave()
    {
    	$id = '';
    	try{
    		$user_info = Authentication::getAuthenInfo();
	    	$user_id = $user_info->EMAIL;//Yii::$app->user->identity->WA_USER_ID;
	    	$request = Yii::$app->request;
	    	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
			$hidden_return = $request->post('return');

			foreach($hidden_return as $key=>$value){
				$backAction[$key]=$value;
			}
			
	    	$input_npactivity = $request->post('NP_ACTIVITY_FORM1_AMPHOE');
	    	$npactivity;
	    	if($mode=='edit'){
	    		$id = $input_npactivity['NP_ACTIVITY_FORM1_AMPHOE_ID'];
	    		$npactivity = NP_ACTIVITY_FORM1_AMPHOE::findOne($id);
	    	}else if($mode=='add'){
	    		$npactivity = new NP_ACTIVITY_FORM1_AMPHOE();
	    		$year = $input_npactivity['YEAR'];
	    		$npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID = NP_ACTIVITY_FORM1_AMPHOE::getNewID();
                $npactivity->IMAGE_PATH = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                            'file_name' => 'IMAGE_PATH', 
                                                            'id' => $npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID,
                											'year' => $year]);
                $npactivity->IMAGE_PATH_1 = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                                'file_name' => 'IMAGE_PATH_1', 
                                                                'id' => $npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID.'-1',
                												'year' => $year]);
                $npactivity->IMAGE_PATH_2 = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                                'file_name' => 'IMAGE_PATH_2', 
                                                                'id' => $npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID.'-2',
                												'year' => $year]);
                $npactivity->IMAGE_PATH_3 = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                                'file_name' => 'IMAGE_PATH_3', 
                                                                'id' => $npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID.'-3',
                												'year' => $year]);
	    		$npactivity->IMAGE_PATH_4 = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                                'file_name' => 'IMAGE_PATH_4', 
                                                                'id' => $npactivity->NP_ACTIVITY_FORM1_AMPHOE_ID.'-4',
                												'year' => $year]);
	    		$npactivity->STATUS = 'A';
	    		$npactivity->CREATE_USER_ID = $user_id;
	    		$npactivity->CREATE_TIME = new \yii\db\Expression('SYSDATE');
	    	}else{
	    
	    	}
	
	    	foreach($input_npactivity as $key=>$value){
	    		switch(strtoupper($key)){
	    			case 'NP_ACTIVITY_FORM1_AMPHOE_ID':
                        continue 2; 
	    				break;
	    			case 'START_DATE':
                        if ($value) {
                            $value = Utils::toDate($value); 
                        }
	                    break;
	    	        case 'END_DATE':
                        if ($value) {
	    	         	    $value = Utils::toDate($value);
                        }
	        	        break;
	    		}
	    		 
	    		$npactivity->$key = $value;
	    	}
	    	$npactivity->LAST_UPD_USER_ID = $user_id;
	    	$npactivity->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

	    	if($npactivity->save()){  // equivalent to $customer->insert();
	    		$backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
	    		$this->redirect(Url::to($backAction));
	    	}else{
	    		throw new Exception('failed : '.print_r($npactivity->getErrors(),true));
	    	}
    	}catch (Exception $e){
    		$backAction[0] = 'npactivityform1amphoe/add';
    		$backAction['id'] = $id;
    		$backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');//base64_encode($e);
    		Yii::trace($e->getMessage(), 'debug');
            $this->redirect(Url::to($backAction));
    	}
    
    }
    
    // Edit mode.
    public function actionUploadfile()
    {
        $request = Yii::$app->request;

        // Authentication
        $user_info = Authentication::getAuthenInfo();
        $user_id = $user_info->EMAIL;

    
    	$fileName = $_FILES["fileToUpload"]["name"];
    	$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
        $image_field = $request->post('image_field');
        $year = $request->post('year');
        
    	$directory = 'images'.$this->imagePath.'/'.$year;
    	
        // Format => IMAGE_PATH_0
        $image_seq = '';
        $split_image_field = explode('_', $image_field);
        if (isset($split_image_field[2]) && is_numeric($split_image_field[2])) {
            $image_seq = $split_image_field[2];
        }
    
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.(empty($image_seq)? '': '-'.$image_seq).date('-Ymd-His').$extensionFile;  
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    
    	$response = new jqGridResponse();
    
    	if ( Utils::checkDirectory($directory) ) {
    		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"]	// temp_file
    				, $directory."/".$fileName)) {	// path file
    				
    			$npactivity = NP_ACTIVITY_FORM1_AMPHOE::findOne($id);
    			$npactivity->$image_field = $this->imagePath.'/'.$year.'/'.$fileName;  //TODO: new image path
                $npactivity->LAST_UPD_USER_ID = $user_id;
                $npactivity->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    			if($npactivity->save()){
    				$response->success(['imagePath'=>$directory."/".$fileName]);
    			}else{
    				$response->error('DB Save failed. '+print_r($npactivity->getErrors(), true)); //3
    			}
    		} else {
    			$response->error('Can\'t save image'); //1
    		}
    	} else {
    		$response->error('Can\'t Create folder'); //2
    	}
    	echo $response->response_encode();
    }
    
 	public function actionGridview()
    {
//     	$user_id = Yii::$app->user->identity->WA_USER_ID;
    	$request = Yii::$app->request;
    	$workId = $request->post('w_id');

		//Parameter from jqGrid
		$oper = $request->post('oper', ''); $oper = (empty($oper)?$request->get('oper', ''):$oper);
		$page = $request->post('page', ''); $page = (empty($page)?$request->get('page', ''):$page);
		$rows = $request->post('rows', ''); $rows = (empty($rows)?$request->get('rows', ''):$rows);
		$sidx = $request->post('sidx', ''); $sidx = (empty($sidx)?$request->get('sidx', ''):$sidx);
		$w_id = $request->post('w_id', ''); $w_id = (empty($sord)?$request->get('w_id', ''):$w_id);
		$sord = $request->post('sord', ''); $sord = (empty($sord)?$request->get('sord', ''):$sord);
		$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
		$isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
		$isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
		$filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);
        // Yii::trace(print_r($filters, true), 'debug');

		//Input parameter from jqGrid Form.
		$subject = $request->post('subject');
		$s_date = $request->post('s_date');
		$month = $request->post('month');
		$province = $request->post('province');
		$amphoe = $request->post('amphoe');
		$s_detial = $request->post('s_detial');
		$s_result = $request->post('s_result');
		$year = $request->post('year');
		$p_num = $request->post('p_num');
		$p_num = $request->post('p_num');

		//Parameter from another pages.
		$np_activity_form1_amphoe_id = $request->post('np_activity_form1_amphoe_id'); $np_activity_form1_amphoe_id = (empty($np_activity_form1_amphoe_id)? $request->get('np_activity_form1_amphoe_id'): $np_activity_form1_amphoe_id);

		//response parameter to jqGrid
    	$result = '';

    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			$where_causes = array();
                $valueWhereAddStatus = [];
    			$where = 'and';
    			if($isSearch){
    				$filters = json_decode($filters);
    				// print_r($filters);
    				switch($filters->groupOp){
    					case 'AND':
    						$where = 'and';
    						break;
    					case 'OR':
    						$where = 'or';
    						break;
    				}
    				array_push($where_causes, $where);
    				foreach($filters->rules as $conditions){
    				    switch($conditions->field){
    				        case 'START_DATE':
    				            array_push($where_causes, $conditions->field." BETWEEN '01-JAN-".$conditions->data."' AND '31-DEC-".$conditions->data."'" );
    				            break;
    				        default:
                                array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );

                                if ($conditions->field != 'NP_ACTIVITY_FORM1_AMPHOE.NP_WORK_GROUP_ACTIVITY_ID') {
                                    // format of field => MODEL_NAME.FIELD_NAME
                                    $splitFieldName = explode(".", $conditions->field);

                                    $valueWhereAddStatus[":".$splitFieldName[1]] = $conditions->data;
                                }
                                break;
    				    }
    				}
    			}
                // Yii::trace(print_r($valueWhereAddStatus, true), 'debug');

    			$count = NP_ACTIVITY_FORM1_AMPHOE::find()->where($where_causes)->count();
    			//$result = NP_ACTIVITY_FORM1_AMPHOE::find()->joinWith('WA_PROVINCE')->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();

    			//$command = Yii::$app->db->createCommand("select NP_ACTIVITY_FORM1_AMPHOE.* ,from NP_ACTIVITY_FORM1_AMPHOE a join WA_PROVINCE b on (a.PROVINCE_CODE = b.PROVINCE_CODE) join WA_AMPHOE c on (C.AMPHOE_CODE = a.AMPHOE_CODE)");
    			$query = new Query;
    			$query->select('NP_ACTIVITY_FORM1_AMPHOE.*,
                                NP_SP_OPEN_AMPHOE.STATUS AS STATUS_2,
                                WA_PROVINCE.PROVINCE_NAME_TH,
                                WA_AMPHOE.AMPHOE_NAME_TH')
	    			->from('NP_ACTIVITY_FORM1_AMPHOE')
                    ->leftJoin('NP_SP_OPEN_AMPHOE', 'NP_ACTIVITY_FORM1_AMPHOE.YEAR=NP_SP_OPEN_AMPHOE.YEAR 
                                                        AND NP_ACTIVITY_FORM1_AMPHOE.MONTH=NP_SP_OPEN_AMPHOE.MONTH 
                                                        AND NP_ACTIVITY_FORM1_AMPHOE.PROVINCE_CODE=NP_SP_OPEN_AMPHOE.PROVINCE_CODE 
                                                        AND NP_ACTIVITY_FORM1_AMPHOE.AMPHOE_CODE=NP_SP_OPEN_AMPHOE.AMPHOE_CODE')
	    			->innerJoin('WA_PROVINCE', 'NP_ACTIVITY_FORM1_AMPHOE.PROVINCE_CODE=WA_PROVINCE.PROVINCE_CODE')
	    			->innerJoin('WA_AMPHOE', 'NP_ACTIVITY_FORM1_AMPHOE.AMPHOE_CODE=WA_AMPHOE.AMPHOE_CODE')
	    			->where($where_causes)
	    			->orderBy($sidx.' '.$sord)
	    			->offset($offset)
	    			->limit($rows);
    			$result = $query->all();
    			$command = $query->createCommand();
    			$result = $command->queryAll();
                $countResult = count($result);
                $seq = $rows * ($page - 1);
                // Yii::trace(print_r($result, true), 'debug');

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;
                $addStatus = 'C';
                if (count($valueWhereAddStatus) == 4) {
                    $npSpOpenAmphoe = NP_SP_OPEN_AMPHOE::find()
                                        ->where('YEAR=:YEAR
                                                    AND MONTH=:MONTH
                                                    AND PROVINCE_CODE=:PROVINCE_CODE
                                                    AND AMPHOE_CODE=:AMPHOE_CODE'
                                                , $valueWhereAddStatus)
                                        ->one();
                    if (!empty($npSpOpenAmphoe)) {
                        $addStatus = $npSpOpenAmphoe->STATUS;
                    }
                }
                $response->add_status = $addStatus;

                $months = Utils::getArrMonth();

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    $monthStr = $months[$result[$i]['MONTH']];

					array_push($response->rows, array('id'=>$result[$i]['NP_ACTIVITY_FORM1_AMPHOE_ID'], 
													'cell'=>array($seq,
																	$result[$i]['SUBJECT'],
																	$result[$i]['START_DATE'],
																	$result[$i]['YEAR'],
																	$result[$i]['MONTH'],
                                                                    $monthStr,
																	$result[$i]['PROVINCE_NAME_TH'],
																	$result[$i]['AMPHOE_NAME_TH'],
																	$result[$i]['SUBJECT_DETAIL'],
																	$result[$i]['SUBJECT_RESULT'],
																	$result[$i]['PARTICIPANTS_NUM'],
																	empty($result[$i]['STATUS_2'])? $result[$i]['STATUS']: $result[$i]['STATUS_2']
                                                                )));
				}
                // Yii::trace(print_r($response, true), 'debug');

				$result = json_encode($response);
				break;
			case 'edit' :
				break;
			case 'add':
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    NP_ACTIVITY_FORM1_AMPHOE::deleteAll(['in', 'NP_ACTIVITY_FORM1_AMPHOE_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                $result = $response->response_encode();
				break;
        }

        echo $result;
    }
}
