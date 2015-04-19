<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use yii\web\Utils;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\RB_NETWORK;
use yii\web\Authentication;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;

class RbNetworkController extends AppController
{
	public $title = 'RB005 XXXX';
	public $enableCsrfValidation = false;
	public $imagePath = '/rbnetwork';
                
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
        $this->addBreadCrumb('RD005 บันทึกเครือข่ายนันทนาการ กรมพลศึกษา');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    public function actionDetail(){
    	
        $request = Yii::$app->request;
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);
        
        $region_code = $request->get('region_code');
        $province_code = $request->get('province_code');
        $amphoe_code = $request->get('amphoe_code');

        $tmp_params['region_code'] = $region_code;
        $tmp_params['province_code'] = $province_code;
        $tmp_params['amphoe_code'] = $amphoe_code;
        
        $backAction = ['rbnetwork/', 'rtparams'=>$rtparams];
        $backAction = BaseArrayHelper::merge($backAction, $tmp_params);

        $this->addBreadCrumb('RD005 บันทึกเครือข่ายนันทนาการ กรมพลศึกษา', Url::to($backAction));
        $this->addBreadCrumb('บันทึกข้อมูล เครือข่ายนันทนาการ');
        
    	$params = $tmp_params;
    	$params['backAction'] = $backAction;
    	$params['rbNetwork'] = array();
    	$request = Yii::$app->request;
    	
    	$params['id']=$id;
    	if(!empty($id)){
            $params['rbNetwork'] = RB_NETWORK::findOne($id);
    	}else{
            //echo('1111111111111') ;
    	    $params['rbNetwork'] = new RB_NETWORK();
    	}
    	
        $params['rtparams']=$rtparams;
    	return $this->render('detail', $params);        
    	
    }    
    
    public function actionSave()
    {
    	$request = Yii::$app->request;

        // Authentication
        $user_info = Authentication::getAuthenInfo();
        $user_id = $user_info->EMAIL;

    	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
    	$input_rbNetwork = $request->post('RB_NETWORK');
    	$hidden_return = $request->post('return');
    	
    	foreach($hidden_return as $key=>$value){
    		$backAction[$key]=$value;
    	}
    	
    	if($mode=='edit'){
            $id = $input_rbNetwork['RB_NETWORK_ID'];
    		$rbNetwork = RB_NETWORK::findOne($id);
    	}else if($mode=='add'){
    		$rbNetwork = new RB_NETWORK();
			$rbNetwork->RB_NETWORK_ID = RB_NETWORK::getNewID();
			$year = intval(date('Y'))+543;
    		$rbNetwork->CREATE_USER_ID = $user_id;
    		$rbNetwork->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    		$rbNetwork->ACT_IMAGE_PATH = $this->uploadFile('IMAGE_FILE', $rbNetwork->RB_NETWORK_ID, $year);
    		
    	}

    	foreach($input_rbNetwork as $key=>$value){
            //Custom value before save to database.
    	    switch(strtoupper($key)){
    	        case 'RB_NETWORK_ID':
    	        	//echo 'RB_NETWORK_ID';
    	            continue 2;
                    break;
    	        case 'RB_ACT_1_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
               	case 'RB_ACT_2_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_3_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_4_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_5_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_6_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_7_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_8_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                case 'RB_ACT_9_FLAG':
    	            $value = ($value?'Y':'N');
    	            break;
                default :    	        
    	    }        	    
            $rbNetwork->$key = $value;
    	}
    	
		$rbNetwork->LAST_UPD_USER_ID = $user_id;
		$rbNetwork->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
		      
        if($rbNetwork->save()){  // equivalent to $customer->insert();
//             return $this->actionIndex();
            $backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
            $this->redirect(Url::to($backAction));
        }else{
//             return $this->actionDetail();
			if(empty($id)) $id='';
        	$backAction[0] = 'rbnetwork/detail';
        	$backAction['id'] = $id;
        	$backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');
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

		//Input parameter from jqGrid Form.
		$networkname = $request->post('networkname');
		$cofirstname = $request->post('cofirstname');             
                $cotel = $request->post('cotel');
                $amphoecode = $request->post('amphoecode');
                $provincecode = $request->post('provincecode');
		$regioncode = $request->post('regioncode');

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
    				//['or', 'WA_MENU_MAIN_ID=\'33\'', 'STATUS=\'A\'']
    				array_push($where_causes, $where);
    				foreach($filters->rules as $conditions){
    				    switch($conditions->field){
    				        case 'NETWORK_NAME':
    				            array_push($where_causes, $conditions->field." LIKE '%".$conditions->data."%'" );
    				            break;
    				        default:
            					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
            					break;
    				    }
    				}
    			}
    			
    			$count = RB_NETWORK::find()->where($where_causes)->count();
    			$result = RB_NETWORK::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['RB_NETWORK_ID'], 
													'cell'=>array($seq,
																	$result[$i]['NETWORK_NAME'] ,
																	$result[$i]['CO_FIRST_NAME'] ,
																	$result[$i]['CO_TEL'],
																	'',//$result[$i]['REGION_CODE'] ,
                                                                                                                                        '',//$result[$i]['PROVINCE_CODE'] ,
																	''//$result[$i]['AMPHOE_CODE']                                                                                                                                         
																	)));
				}
				
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

                RB_NETWORK::deleteAll(['in', 'RB_NETWORK_ID', $arrId]);

                $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
            } catch (Exception $e) {
                $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
            }
                
            $result = $response->response_encode();
			break;
    	}
    	
    	echo $result;

    }
    private function uploadFile($files_name, $id, $year){
    	
    	$directory = 'images'.$this->imagePath.'/'.$year;
    
    	$fileName = $_FILES[$files_name]["name"];
    
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.date('-Ymd-His').$extensionFile;
    
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    
    	$response = new jqGridResponse();
    
    	if ( Utils::checkDirectory($directory) ) {
    		 
    		if ( move_uploaded_file($_FILES[$files_name]["tmp_name"]	// temp_file
    				, $directory."/".$fileName) ) {	// path file
    			//Successssssssssss.
    
    		} else {
    			throw new Exception('Can\'t save image');
    		}
    		 
    	} else {
    		throw new Exception('Can\'t create directory for save image');
    	}
    	return empty($fileName)?$fileName:$this->imagePath.'/'.$year.'/'.$fileName;
    }
    
    public function actionUploadfile(){ //Edit mode.
    
    	$request = Yii::$app->request;
    
    	$year = intval(date('Y'))+543;
    	$directory = 'images'.$this->imagePath.'/'.$year;
    
    	$fileName = $_FILES["fileToUpload"]["name"];
    	$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
    
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.date('-Ymd-His').$extensionFile;
    
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    
    	$response = new jqGridResponse();
    
    	if ( Utils::checkDirectory($directory) ) {
    		//print_r($_FILES["fileToUpload"]);
    		if ( move_uploaded_file($_FILES["fileToUpload"]["tmp_name"]	// temp_file
    				, $directory."/".$fileName) ) {	// path file
    
    			$rbNetwork = RB_NETWORK::findOne($id);
    			$rbNetwork->ACT_IMAGE_PATH = $this->imagePath.'/'.$year.'/'.$fileName;
    			if($rbNetwork->save()){
    				$response->success(['imagePath'=>$directory."/".$fileName]);
    			}else{
    				$response->error('DB Save failed. '+print_r($rbNetwork->getErrors(), true)); //3
    			}
    		} else {
    			$response->error('Can\'t save image'); //1
    		}
    		 
    	} else {
    		$response->error('Can\'t Create folder'); //2
    	}
    	echo $response->response_encode();
    }
}

