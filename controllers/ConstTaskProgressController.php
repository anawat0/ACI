<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\BaseArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\CONST_PROJ;
use app\models\CONST_TASK;

class ConstTaskProgressController extends AppController
{
    public $title = 'CON002 บันทึกติดตามงานก่อสร้างสนามกีฬา';
	
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
    	$this->addBreadCrumb('CON002 บันทึกติดตามงานก่อสร้างสนามกีฬา');
    	$arrPlaseSelect = array('' => 'กรุณาเลือก');
    	$params = array();
    	$params['rtparams'] = $rtparams;
    	$params['arrPlaseSelect'] = $arrPlaseSelect;
        return $this->render('index', $params);
    }
    public function actionGridview()
    {
    	$request = Yii::$app->request;

        // Authentication
        $user_info = Authentication::getAuthenInfo();
        $user_id = $user_info->EMAIL;

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
		$taskNameTh = $request->post('taskNameTh');
		$progress = $request->post('progress');
		
		//Parameter from another pages.
		$const_proj = $request->post('constproj'); $const_proj = (empty($const_proj)?$request->get('constproj'):$const_proj);
		
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
    				    if(!empty($conditions->data)){
        					switch($conditions->field){
        				        case 'CONST_TASK.START_DATE':
        				            array_push($where_causes, $conditions->field." BETWEEN '01-JAN-".$conditions->data."' AND '31-DEC-".$conditions->data."'" );
        				            break;
        				        default:
        				            array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
        				            break;
        				    }
    				    }
    					//array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				}
    			}
    			
    			$query = new Query;
    			$query->select('CONST_TASK.*')
        		    ->from('CONST_PROJ')
        		    ->innerJoin('CONST_TASK', 'CONST_PROJ.CONST_PROJ_ID=CONST_TASK.CONST_PROJ_ID')
        		    ->where($where_causes);
        		$count = $query->count();
    			
    			$query = new Query;
        		$query->select('CONST_TASK.*')
        		    ->from('CONST_PROJ')
        		    ->innerJoin('CONST_TASK', 'CONST_PROJ.CONST_PROJ_ID=CONST_TASK.CONST_PROJ_ID') 
        		    ->where($where_causes)
        		    ->orderBy($sidx.' '.$sord)
        		    ->offset($offset)
        		    ->limit($rows);
        		$result = $query->all();
        		$command = $query->createCommand();
        		$result = $command->queryAll();
                $countResult = count($result);
                $seq = $rows * ($page - 1);
    			
    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;
    			
				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    
					array_push($response->rows, array('id'=>$result[$i]['CONST_TASK_ID'], 
													'cell'=>array($seq,
																	$result[$i]['TASK_NAME_TH'],
																	$result[$i]['PROGRESS'],
																	'',
																	'',
																	$result[$i]['LAST_UPD_TIME']
																	)));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit' :
    			//To update an existing customer record
				$constTask = CONST_TASK::findOne($id);
				$constTask->TASK_NAME_TH = $taskNameTh ;
                $constTask->PROGRESS = $progress ;                        
				$constTask->LAST_UPD_USER_ID = $user_id;
				$constTask->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                                
				if($constTask->save()){  // equivalent to $customer->insert();
					$result = 'success';
				}else{
					$result = 'failed : '.print_r($constTask->getErrors(),true);
				}
    			break;
		  case 'add':
				$constTask = new CONST_TASK();
				$constTask->CONST_TASK_ID = CONST_TASK::getNewID();
				$constTask->CONST_PROJ_ID = $const_proj;
				$constTask->TASK_NAME_TH = $taskNameTh ;
                $constTask->PROGRESS = $progress;
				$constTask->CREATE_USER_ID = $user_id;
				$constTask->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$constTask->LAST_UPD_USER_ID = $user_id;
				$constTask->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                                
				if($constTask->save()){  // equivalent to $customer->insert();
					$result = 'success';
				}else{
					$result = 'failed : '.print_r($constTask->getErrors(),true);
				}
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    CONST_TASK::deleteAll(['in', 'CONST_TASK_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;

    }
    /*
    public function actionGetajaxAmphoe(){
    	$request = Yii::$app->request;
    	$province = $request->post('province', ''); $province = (empty($province)?$request->get('province', ''):$province);
    	$where_cause = [];
    	if(!empty($province)){
    		$where_cause['PROVINCE_CODE']=$province;
    	}
    	$result = CONST_PROJ::find()->where($where_cause)->asArray()->all();
    	$this->ajaxResponse($result);
    }
    */
    public function actionGetajaxconstproj(){
    	$request = Yii::$app->request;
    	$province = $request->post('province', ''); $province = (empty($province)?$request->get('province', ''):$province);
    	$amphoe = $request->post('amphoe', ''); $amphoe = (empty($amphoe)?$request->get('amphoe', ''):$amphoe);
    	$where_cause = [];
    	if(!empty($province)){
    		$where_cause['PROVINCE_CODE']=$province;
    	}
    	if(!empty($amphoe)){
    		$where_cause['AMPHOE_CODE']=$amphoe;
    	}
    	$result = CONST_PROJ::find()->where($where_cause)->asArray()->all();
    	$this->ajaxResponse($result);
    }
}
