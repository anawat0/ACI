<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\NP_STAFF;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Utils;
use yii\web\Authentication;
use yii\helpers\BaseArrayHelper;

class NpStaffPrintCardController extends AppController
{
	public $title = 'NP008 บันทึกระเบียนประวัติเจ้าหน้าที่พลศึกษา';
	public $enableCsrfValidation = false;
	public $imagePath = 'images/npstaff';
                
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
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $this->addBreadCrumb('NP-RTP003 พิมพ์บัตรประจำตัวเจ้าหน้าที่พลศึกษา');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    
    public function actionAdd(){
    	$request = Yii::$app->request;
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	$id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);
    	
    	$staff_id = $request->get('staff_id');
    	$year = $request->get('year');
    	$month = $request->get('month');
    	$province = $request->get('province');
    	$amphoe = $request->get('amphoe');
    	
    	$tmp_params['staff_id'] = $staff_id;
    	$tmp_params['year'] = $year;
    	$tmp_params['month'] = $month;
    	$tmp_params['province'] = $province;
    	$tmp_params['amphoe'] = $amphoe;
    
    	$this->addBreadCrumb('NP-RTP003 พิมพ์บัตรประจำตัวเจ้าหน้าที่พลศึกษา', Url::to(['npstaff/', 'rtparams'=>$rtparams]));
    	$this->addBreadCrumb('บันทึก บัตรประจำตัวเจ้าหน้าที่พลศึกษา');
    	
    	$backAction = ['npstaff/', 'rtparams'=>$rtparams];
    	 
    	$backAction = BaseArrayHelper::merge($backAction, $tmp_params);
    	$params = $tmp_params;
    	$params['backAction'] = $backAction;
    	 
    	$params['npStaff'] = array();
    	$params['id']=$id;
//     	$request = Yii::$app->request;
    	if(!empty($id)){
    		$params['npStaff'] = NP_STAFF::findOne($id);
    	}else{
    		$params['npStaff'] = new NP_STAFF();
    	}
    	$params['rtparams']=$rtparams;
    	 
    	return $this->render('add', $params);
    	 
    }
    
    public function actionSave(){
    	 

    try{ 	 
    	$npStaff;
    	
    	$user_info = Authentication::getAuthenInfo();
    	$user_id = $user_info->EMAIL;
    	
    	$request = Yii::$app->request;
    	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
    	$input_npStaff = $request->post('NP_STAFF');
    	$hidden_return = $request->post('return');
    	foreach($hidden_return as $key=>$value){
    		//if($key==0) continue;
    		$backAction[$key]=$value;
    	}
    		
    	if($mode=='edit'){
    		$id = $input_npStaff['STAFF_ID'];
    		$npStaff = NP_STAFF::findOne($id);
    	}else if($mode=='add'){
    		$npStaff = new NP_STAFF();
    		$npStaff->STAFF_ID = NP_STAFF::getNewID();
    		$npStaff->YEAR = $request->post('YEAR');
    		$npStaff->IMAGE_PATH = $this->uploadFile('IMAGE_FILE', $npStaff->STAFF_ID);
    		$npStaff->STATUS = 'A';
    		$npStaff->CREATE_USER_ID = $user_id;
    		$npStaff->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    	}else{
    
    	}
    	 
    	foreach($input_npStaff as $key=>$value){
    		switch(strtoupper($key)){
    			case 'STAFF_ID':
    				continue 2;
    				break;
    			case 'BIRTH_DATE':
    				$value = Utils::toDate($value);
    				break;
    			case 'ID_NO_EXPIRE_DATE':
    				$value = Utils::toDate($value);
    				break;
    			default :
    				 
    		}
    		 
    		$npStaff->$key = $value;
    	}
    
    	$npStaff->LAST_UPD_USER_ID = $user_id;
    	$npStaff->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    
        if($npStaff->save()){  // equivalent to $customer->insert();
	    		//$backAction['message'] = base64_encode('Completed');
	    		$this->redirect(Url::to($backAction));
	    	}else{
	    		
	    		throw new Exception('failed : '.print_r($npStaff->getErrors(),true));
	
	    	}
    	}catch (Exception $e){
    		$backAction[0] = 'npstaff/add';
    		$backAction['id'] = $id;
    		//$backAction['message'] = base64_encode($e);
    		$this->redirect(Url::to($backAction));
    	}
    	 
    }
    
    public function actionGridview()
    {
    	$request = Yii::$app->request;
    
    	//Parameter from jqGrid
    	$oper = $request->post('oper', ''); $oper = (empty($oper)?$request->get('oper', ''):$oper);
    	$page = $request->post('page', ''); $page = (empty($page)?$request->get('page', ''):$page);
    	$rows = $request->post('rows', ''); $rows = (empty($rows)?$request->get('rows', ''):$rows);
    	$sidx = $request->post('sidx', ''); $sidx = (empty($sidx)?$request->get('sidx', ''):$sidx);
    	$sord = $request->post('sord', ''); $sord = (empty($sord)?$request->get('sord', ''):$sord);
    	$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
    	$isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
    	$isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
    	$filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);
    
    	//response parameter to jqGrid
    	$result = '';
    	 
    	switch($oper){
    		case 'request' :
    			 
    			$offset = ($page-1)*$rows;
    			$where_causes = array();
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
    						case 'FIRST_NAME':
    							array_push($where_causes, $conditions->field." LIKE '%".$conditions->data."%'" );
    							break;
    					    case 'LAST_NAME':
    								array_push($where_causes, $conditions->field." LIKE '%".$conditions->data."%'" );
    							break;
    						case 'ORG_TYPE':
    								array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    							break;
    						case 'YEAR':
    							array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    							break;
    						default:
    							array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    							break;
    					}
    				}
    			}
    			 
    			// 			//print_r($where_causes);
    			 
    			// print_r($result);
    			$count = NP_STAFF::find()->where($where_causes)->count();
    
    			$query = new Query;
    			$query->select('NP_STAFF.*, WA_PROVINCE.PROVINCE_NAME_TH, WA_AMPHOE.AMPHOE_NAME_TH')
    			->from('NP_STAFF')
    			->innerJoin('WA_PROVINCE', 'NP_STAFF.ORG_PROVINCE_CODE=WA_PROVINCE.PROVINCE_CODE')
    			->innerJoin('WA_AMPHOE', 'NP_STAFF.ORG_AMPHOE_CODE=WA_AMPHOE.AMPHOE_CODE')
    			->where($where_causes)
    			->orderBy($sidx.' '.$sord)
    			->offset($offset)
    			->limit($rows);
    			$result = $query->all();
    			$command = $query->createCommand();
    			$result = $command->queryAll();
    			 
    			// 			print_r($result);
    			 
    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;
    
    			for($i=0; $i<count($result); $i++){
    				if($result[$i]['ORG_TYPE'] == '1'){
    					$orgType = "สำนักการท่องเที่ยว";
    				}else if($result[$i]['ORG_TYPE'] == '2'){
    					$orgType = "ที่ว่าการอำเภอ";
    				}else{
    					$orgType = "-";
    				}
    				array_push($response->rows, array('id'=>$result[$i]['STAFF_ID'],
    					'cell'=>array($result[$i]['STAFF_ID'],
				    				$result[$i]['YEAR'],
				    				$result[$i]['FIRST_NAME'].' '.$result[$i]['LAST_NAME'],
				    				$orgType.' จังหวัด'.$result[$i]['PROVINCE_NAME_TH'].' อำเภอ'.$result[$i]['AMPHOE_NAME_TH'],
				    				$result[$i]['MOBILE_NO'],
				    				//'<a href='.$result[$i]['IMAGE_PATH'].' target="_blank">'.$result[$i]['IMAGE_PATH'].'</a>',
				    				$this->imagePath.'/'.$result[$i]['IMAGE_PATH']
    						)));
    			}
    
    			$result = json_encode($response);
    			 
    			break;
    	   
    		case 'edit' :
    			break;
    
    		case 'add':
    			break;
    
    		case 'del':
    
    			// to delete an existing customer record
    			$menuMain = WA_MENU_MAIN::findOne($id);
    			if($menuMain->delete()){
    				$result = 'success';
    			}else{
    				$result = 'failed : '.print_r($menuMain->getErrors(),true);
    			}
    			break;
    	}
    	 
    	echo $result;
    
    }

}
