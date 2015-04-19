<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SB_MAIN_PROJ;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbmainprojController extends AppController
{

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

    	$this->addBreadCrumb('SB001 รายชื่อโครงการ สกฬ.');

        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
        $params = array('rtparams'=>$rtparams);

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
		$nameth = $request->post('nameth');
		$nameen = $request->post('nameen');
		$budgetYear = $request->post('budget_year');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = SB_MAIN_PROJ::find()->count();
    			$where_causes = array();
    			if($isSearch){
    				//['type' => 1, 'status' => 2]
    			}
    			
    			$result = SB_MAIN_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
    			$seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['SB_MAIN_PROJ_ID'], 
													'cell'=>array($seq,
																	$result[$i]['MAIN_PROJ_NAME_TH'],
																	$result[$i]['MAIN_PROJ_NAME_EN'],
																	$result[$i]['BUDGET_YEAR'],
																	$result[$i]['STATUS'],
																	$result[$i]['SB_MAIN_PROJ_ID'],
																	$result[$i]['CREATE_USER_ID'],
																	$result[$i]['CREATE_TIME'],
																	$result[$i]['LAST_UPD_USER_ID'],
																	$result[$i]['LAST_UPD_TIME'],
																	'edit')));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit':
                try {
    				$mainProj = SB_MAIN_PROJ::findOne($id);
    				$mainProj->MAIN_PROJ_NAME_TH = $nameth;
    				$mainProj->MAIN_PROJ_NAME_EN = $nameen;
    				$mainProj->BUDGET_YEAR = $budgetYear;
    				$mainProj->STATUS = Utils::getStatus($status);
    				$mainProj->LAST_UPD_USER_ID = $user_id;
    				$mainProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    				
    				$response = new jqGridResponse();
    				if ($mainProj->save()) {  
    					$response->success(['id'=>$mainProj->SB_MAIN_PROJ_ID]);
    				} else {
    					$response->error($mainProj->getErrors());
    				}
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$mainProj = new SB_MAIN_PROJ();
    				$mainProj->SB_MAIN_PROJ_ID = SB_MAIN_PROJ::getNewID();
    				$mainProj->MAIN_PROJ_NAME_TH = $nameth;
    				$mainProj->MAIN_PROJ_NAME_EN = $nameen;
    				$mainProj->BUDGET_YEAR = $budgetYear;
    				$mainProj->STATUS = Utils::getStatus($status);
    				$mainProj->CREATE_USER_ID = $user_id;
    				$mainProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$mainProj->LAST_UPD_USER_ID = $user_id;
    				$mainProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

    				$response = new jqGridResponse();
    				if ($mainProj->save()) {  
    					$response->success(['id'=>$mainProj->SB_MAIN_PROJ_ID]);
    				} else {
    					$response->error($mainProj->getErrors());
    				}
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    SB_MAIN_PROJ::deleteAll(['in', 'SB_MAIN_PROJ_ID', $arrId]);

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
