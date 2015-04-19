<?php

namespace app\controllers;

use Yii;
use Yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Utils;
use yii\web\Authentication;
use yii\filters\VerbFilter;
use yii\helpers\BaseArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RB_PROJ_LAUN;
use app\models\RB_PROJ;
use app\models\WA_SECTION;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class RbProjLaunController extends AppController
{
	public $title = 'RD002 โครงการที่กาลังดาเนินการ สนก.';
	public $firstOptionDDL = array('' => 'กรุณาเลือก');
	
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

    	// Parameters.
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
       
    	$rbProjs = RB_PROJ::find()->all();
    	$listRbProj = BaseArrayHelper::merge($this->firstOptionDDL, 
    										BaseArrayHelper::map($rbProjs, 'RB_PROJ_ID', 'PROJ_NAME_TH'));
    	$waSections = WA_SECTION::find()->all();
    	$listWaSection = BaseArrayHelper::merge($this->firstOptionDDL, 
    										BaseArrayHelper::map($waSections, 'WA_SECTION_ID', 'SECTION_NAME_TH'));
    	
    	$params = array('rtparams' => $rtparams,
    					'firstOptionDDL' => $this->firstOptionDDL,
    					'rbProjs' => $rbProjs,
    					'listRbProj' => json_encode($listRbProj),
    					'listWaSection' => json_encode($listWaSection));

    	$this->addBreadCrumb('RD002 โครงการที่กำลังดำเนินการ สนก.');

        return $this->render('index', $params);
    }

    public function actionGridview()
    {
    	$request = Yii::$app->request;
    	
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
		$budgetYear = $request->post('budget_year');
		$rbProjId = $request->post('rb_proj_id');
		$waSectionId = $request->post('wa_section_id');
		$projResponsible = $request->post('proj_responsible');
		$fromDate = $request->post('from_date');
		$toDate = $request->post('to_date');
		$satisfactionLevel = $request->post('satisfaction_level');
		$budget = $request->post('budget');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array();
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
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
    						case 'BUDGET_YEAR':
    							array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    							break;
    						default:
    							array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    							break;
    					}
    				}
    			}
    			
    			$count = RB_PROJ_LAUN::find()->where($where_causes)->count();
    			$result = RB_PROJ_LAUN::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
					$seq = $i + 1;

					array_push($response->rows, array('id'=>$result[$i]['RB_PROJ_LAUN_ID'], 
													'cell'=>array($seq,
																	$result[$i]['BUDGET_YEAR'],
																	$result[$i]->rbProj['PROJ_NAME_TH'],
																	$result[$i]->waSection['SECTION_NAME_TH'],
																	$result[$i]['PROJ_RESPONSIBLE'],
																	$result[$i]['FROM_DATE'],
																	$result[$i]['TO_DATE'],
																	$result[$i]['SATISFACTION_LEVEL'],
																	$result[$i]['BUDGET'],
																	$result[$i]['STATUS'],
																	'edit')));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit' :
    			//To update an existing customer record
				$rbProjLaun = RB_PROJ_LAUN::findOne($id);
				$rbProjLaun->RB_PROJ_ID = $rbProjId;
				$rbProjLaun->WA_SECTION_ID = $waSectionId;
				$rbProjLaun->PROJ_RESPONSIBLE = $projResponsible;
				$rbProjLaun->FROM_DATE = Utils::toDate($fromDate);//new \yii\db\Expression('TO_DATE(\''.$fromDate.'\',\'DD-MM-YYYY\')');//$fromDate;
				$rbProjLaun->TO_DATE = Utils::toDate($toDate);//new \yii\db\Expression('TO_DATE(\''.$toDate.'\',\'DD-MM-YYYY\')');//$fromDate;//$toDate;
				$rbProjLaun->SATISFACTION_LEVEL = $satisfactionLevel;
				$rbProjLaun->BUDGET_YEAR = $budgetYear;
				$rbProjLaun->BUDGET = $budget;
				$rbProjLaun->STATUS = Utils::getStatus($status);
				$rbProjLaun->LAST_UPD_USER_ID = $user_id;
				$rbProjLaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                
				if ($rbProjLaun->save()) {  // equivalent to $customer->insert();
					$response->success(['id'=>$rbProjLaun->RB_PROJ_LAUN_ID]);
				} else {
					$response->error($rbProjLaun->getErrors());
				}
				
				$result = $response->response_encode();
    			break;
			case 'add':
				$rbProjLaun = new RB_PROJ_LAUN();
				$rbProjLaun->RB_PROJ_LAUN_ID = RB_PROJ_LAUN::getNewID();
				$rbProjLaun->RB_PROJ_ID = $rbProjId;
				$rbProjLaun->WA_SECTION_ID = $waSectionId;
				$rbProjLaun->PROJ_RESPONSIBLE = $projResponsible;
				$rbProjLaun->FROM_DATE = Utils::toDate($fromDate);
				$rbProjLaun->TO_DATE =  Utils::toDate($toDate);
				$rbProjLaun->SATISFACTION_LEVEL = $satisfactionLevel;
				$rbProjLaun->BUDGET_YEAR = $budgetYear;
				$rbProjLaun->BUDGET = $budget;
				$rbProjLaun->STATUS = Utils::getStatus($status);
				$rbProjLaun->CREATE_USER_ID = $user_id;
				$rbProjLaun->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$rbProjLaun->LAST_UPD_USER_ID = $user_id;
				$rbProjLaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                
				if ($rbProjLaun->save()) {  // equivalent to $customer->insert();
					$response->success(['id'=>$rbProjLaun->RB_PROJ_LAUN_ID]);
				} else {
					$response->error($rbProjLaun->getErrors());
				}

				$result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    RB_PROJ_LAUN::deleteAll(['in', 'RB_PROJ_LAUN_ID', $arrId]);

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
