<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Utils;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\web\Authentication;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\KPI_DIMENTION;
use app\models\KPI_FST;
use app\models\KPI_SECD;
use app\models\KPI_THRD;
use app\models\KPI_FRTH;
use app\models\KPI_REC;
use yii\base\Exception;

class KpirecController extends AppController
{
	public $title = 'KPI002 XXX';
	public $enableCsrfValidation = false;
                
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
        $this->addBreadCrumb('KPI002 บันทึกผล ตัวชี้วัดผลการปฏิบัติราชการ');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
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
    			
    			$quater = '';
    			
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
    						case 'YEAR':
    							break;
    						case 'QUATER':
    							$quater = $conditions->data;
    							break;
    						default :
    							array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    					}
    					
    				}
    			}
    			
    			$query = new Query;
    			 
    			$query->select('V_KPI_ALL_INDC.KPI_DIMENTION_ID ,
								V_KPI_ALL_INDC.NAME_TH,
						        V_KPI_ALL_INDC.KPI_FST_ID ,
						        V_KPI_ALL_INDC.KPI_SECD_ID ,
						        V_KPI_ALL_INDC.KPI_THRD_ID ,
						        V_KPI_ALL_INDC.KPI_FRTH_ID ,
						        KPI_REC.KPI_REC_ID ,
						        KPI_REC.QUATER ,
						        KPI_REC.POINT ,
						        KPI_REC.WT ,
						        KPI_REC.TARGET_SCORE ,
						        KPI_REC.ACTUAL_SCORE ,
						        KPI_REC.TARGET_QUATER_CODE ,
						        KPI_REC.TARGET_QUATER_REMARK')
    			->from('V_KPI_ALL_INDC')
    			->leftJoin('KPI_REC', 'V_KPI_ALL_INDC.KPI_DIMENTION_ID = KPI_REC.KPI_DIMENTION_ID
                                      and NVL(V_KPI_ALL_INDC.KPI_FST_ID, 0) = NVL(KPI_REC.KPI_FST_ID, 0)
                                      and NVL(V_KPI_ALL_INDC.KPI_SECD_ID, 0) = NVL(KPI_REC.KPI_SECD_ID, 0)
                                      and NVL(V_KPI_ALL_INDC.KPI_THRD_ID, 0) = NVL(KPI_REC.KPI_THRD_ID, 0)
                                      and NVL(V_KPI_ALL_INDC.KPI_FRTH_ID, 0) = NVL(KPI_REC.KPI_FRTH_ID, 0)
    								  and KPI_REC.QUATER='.$quater)
    			->where($where_causes);
    			$count = $query->count();
    			 
    			$query->orderBy(['KPI_DIMENTION_ID'=>SORT_DESC,'KPI_FST_ID'=>SORT_DESC,'KPI_SECD_ID'=>SORT_DESC,'KPI_THRD_ID'=>SORT_DESC,'KPI_FRTH_ID'=>SORT_DESC,])
    			->offset($offset)
    			->limit($rows);
    			$result = $query->all();
    			$command = $query->createCommand();
    			$result = $command->queryAll();
                $countResult = count($result);
                $num = $rows * ($page - 1);
    			
    			
    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
					$num++;

					array_push($response->rows, array('id'=>$result[$i]['KPI_REC_ID'], 
													'cell'=>array($num,
																	$result[$i]['NAME_TH'],
																	$result[$i]['POINT'],
																	$result[$i]['ACTUAL_SCORE'],
																	$result[$i]['TARGET_SCORE'],
																	$result[$i]['WT'],
																	$result[$i]['TARGET_QUATER_CODE'],
																	$result[$i]['TARGET_QUATER_REMARK'],
																	'', //QUATER
																	$result[$i]['KPI_DIMENTION_ID'],
																	$result[$i]['KPI_FST_ID'],
																	$result[$i]['KPI_SECD_ID'],
																	$result[$i]['KPI_THRD_ID'],
																	$result[$i]['KPI_FRTH_ID'],
																	$result[$i]['KPI_REC_ID']
																	)));
				}
				
    			$result = json_encode($response);
    			
	    		break;
	    		
    		case 'edit':
    			break;
		
			case 'add':
				break;
	
			case 'del':
				break;
    	}
    	
    	echo $result;
    }
    
    public function actionSave(){
    	$id = '';
    	$isSuccess = true;
    	
    	try{
    		$user_info = Authentication::getAuthenInfo();
    		$user_id = $user_info->EMAIL;
    		$request = Yii::$app->request;
    		$hidden_return = $request->post('return');
    		
    		$backAction[0] = 'kpirec/';
    		foreach($hidden_return as $key=>$value){
    			$backAction[$key]=$value;
    		}
    		
    		$inputs = $request->post('kpirec');
    		
    		foreach($inputs as $id=>$record){
    			
    			$model = null;
    			if(substr($id, 0, 4 )=="null"){
    				//Add Mode.
    				$model = new KPI_REC();
    				$model->KPI_REC_ID = KPI_REC::getNewID();
    				$model->CREATE_USER_ID = $user_id;
    				$model->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    			}else{
    				//Edit Mode.
    				$model = KPI_REC::findOne($id);
    			}
    			
    			foreach($record as $key=>$val){
    				
    				switch ($key){
    					case 'KPI_FST_ID': 
    						$val = (empty($val)?new \yii\db\Expression('NULL'):$val);
    						break;
    					case 'KPI_SECD_ID': 
    						$val = (empty($val)?new \yii\db\Expression('NULL'):$val);
    						break;
    					case 'KPI_THRD_ID': 
    						$val = (empty($val)?new \yii\db\Expression('NULL'):$val);
    						//print_r($val);
    						break;
    					case 'KPI_FRTH_ID': 
    						$val = (empty($val)?new \yii\db\Expression('NULL'):$val);
    						break;
    					default:
    				}
    				
    				$model->$key = $val;
    			}
    			$model->LAST_UPD_USER_ID = $user_id;
    			$model->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    			
    			if($model->save()){  // equivalent to $customer->insert();
    				
//     				$this->redirect(Url::to($backAction));
//     				echo "Success";
    			}else{
//     				print_r($model->getErrors());
    				//throw new Exception('failed : '.print_r($model->getErrors(),true));
    				$isSuccess = false;
    			}
    			
    		}
    		
    		if($isSuccess){
    			$backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
    			$this->redirect(Url::to($backAction));
    		}else{
    			throw new Exception('Save failed');
    		}
    		
    	}catch (Exception $e){
    		$backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');
//     		Yii::trace($e->getMessage(), 'debugger');
    		$this->redirect(Url::to($backAction));
//     		print_r($e->getMessage());
    	}
    }
    
    public function actionGetddldimention()
    {
    	$request = Yii::$app->request;
    	$budget_year = $request->post('budget_year', ''); $budget_year = (empty($budget_year)?$request->get('budget_year', ''):$budget_year);
    	$result = KPI_DIMENTION::find()->where(['BUDGET_YEAR'=>$budget_year])->asArray()->all();
    	echo json_encode($result);
    }
}
