<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Authentication;
use yii\web\AppController;
use yii\filters\VerbFilter;
use yii\web\Utils;
use yii\db\Query;
use yii\helpers\BaseArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\CONST_PROJ;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;

class ConstprojController extends AppController
{
	public $title = 'CON001 โครงการงานก่อสร้างสนามกีฬา';
	
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
        $this->addBreadCrumb('CON001 โครงการงานก่อสร้างสนามกีฬา');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    public function actionAdd(){
    	
    // 	$waProvince = WA_PROVINCE::find()->asArray()->all();
    // 	$result = array('waProvince'=>$waProvince);
    
        $request = Yii::$app->request;
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);
    	
        $year = $request->get('year');
        $province = $request->get('province');
        $amphoe = $request->get('amphoe');
        
        $tmp_params['year'] = $year;
        $tmp_params['province'] = $province;
        $tmp_params['amphoe'] = $amphoe;
        
        $backAction = ['constproj/', 'rtparams'=>$rtparams];
        $backAction = BaseArrayHelper::merge($backAction, $tmp_params);
         
        $this->title = 'CON001 บันทึกข้อมูล';
        $this->addBreadCrumb('CON001 โครงการงานก่อสร้างสนามกีฬา', Url::to($backAction));
        $this->addBreadCrumb('บันทึกโครงการงานก่อสร้างสนามกีฬา');
    	
        $params = $tmp_params;
        $params['backAction'] = $backAction;
    	$params['constProj'] = array();
    	$params['id']=$id;
    	if(!empty($id)){
    		$params['constProj'] = CONST_PROJ::findOne($id);
    	}else{
    	    $params['constProj'] = new CONST_PROJ();
    	}
    	$params['rtparams']=$rtparams;
    	
    	return $this->render('add', $params);
    	
    }
    
    public function actionSave() {
    	try {
        	$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

        	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
        	$input_constProj = $request->post('CONST_PROJ');
        	
        	$constProj;
        	if($mode=='edit'){
        	    $id = $input_constProj['CONST_PROJ_ID'];
        		$constProj = CONST_PROJ::findOne($id);
        	}else if($mode=='add'){
        		$constProj = new CONST_PROJ();
    			$constProj->CONST_PROJ_ID = CONST_PROJ::getNewID();
        		$constProj->CREATE_USER_ID = $user_id;
        		$constProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
        	}else{
        		
        	}
        	
        	//Custom value before save to database.
        	foreach($input_constProj as $key=>$value){
        	    switch(strtoupper($key)){
        	        case 'CONST_PROJ_ID':
        	            continue 2;
        	            break;
        	        case 'STATUS':
        	            $value = ($value?'A':'C');
        	            break;
                    case 'START_DATE':
                        $value = Utils::toDate($value);
                        break;
        	        case 'END_DATE':
        	         $value = Utils::toDate($value);
            	            break;
            	        default :
        	        
        	    }
            	    
                $constProj->$key = $value;
        	}
    		
    		$constProj->LAST_UPD_USER_ID = $user_id;
    		$constProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    		
    		if ($constProj->save()) {
                Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
                return $this->redirect(Url::to(['constproj/index']));
    		} else {
                Utils::setErrorFlashMessage($constProj->getErrors());
                return $this->redirect(Url::to(['constproj/add']));
    		}
    	} catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));
            return $this->redirect(Url::to(['constproj/add']));
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
    				        case 'START_DATE':
    				            array_push($where_causes, $conditions->field." BETWEEN to_date('01/01/".$conditions->data."','DD/MM/YYYY') AND to_date('31/12/".$conditions->data."','DD/MM/YYYY')");
    				            break;
    				        default:
    				            array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				            break;
    				    }
    				}
    			}
                //Yii::trace($where_causes, 'debugger');
    			
    			// print_r($result);
    			$count = CONST_PROJ::find()->where($where_causes)->count();
    			//$result = CONST_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();

                $query = new Query;
        		$query->select('CONST_PROJ.*, WA_PROVINCE.PROVINCE_NAME_TH, WA_AMPHOE.AMPHOE_NAME_TH')
        		    ->from('CONST_PROJ')
        		    ->innerJoin('WA_PROVINCE', 'CONST_PROJ.PROVINCE_CODE=WA_PROVINCE.PROVINCE_CODE') 
        		    ->innerJoin('WA_AMPHOE', 'CONST_PROJ.AMPHOE_CODE=WA_AMPHOE.AMPHOE_CODE')
        		    ->where($where_causes)
        		    ->orderBy($sidx.' '.$sord)
        		    ->offset($offset)
        		    ->limit($rows);
        		$result = $query->all();
        		$command = $query->createCommand();
        		$result = $command->queryAll();
                $countResult = count($result);
                $seq = $rows * ($page - 1);	
    			
    // 			print_r($result);
    			
    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
					array_push($response->rows, array('id'=>$result[$i]['CONST_PROJ_ID'], 
													'cell'=>array($seq,
																	$result[$i]['PROJ_NAME_TH'],
																	$result[$i]['BUDGET'],
																	$result[$i]['START_DATE'],
																	$result[$i]['PROVINCE_NAME_TH'],
																	$result[$i]['AMPHOE_NAME_TH'],
																	'',
																	$result[$i]['STATUS'],
																	''
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

                    CONST_PROJ::deleteAll(['in', 'CONST_PROJ_ID', $arrId]);

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
