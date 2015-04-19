<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Authentication;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SB_PROJ_LAUN_TARGET;
use app\models\SB_PROJ_LAUN_RES;
use app\models\SB_PROJ_LAUN_RES_TARGET;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbProjLaunResTargetController extends AppController
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

        $sb_proj_laun_res_id = $request->post('sb_proj_laun_res_id'); $sb_proj_laun_res_id = (empty($sb_proj_laun_res_id)? $request->get('sb_proj_laun_res_id'): $sb_proj_laun_res_id); 
        $sbProjLaunRes = SB_PROJ_LAUN_RES::findOne($sb_proj_laun_res_id);
        $sbProjLaunTargets = SB_PROJ_LAUN_TARGET::findAll(['SB_PRJECT_LAUN_ID' => $sbProjLaunRes->sbProjLaun['SB_PROJ_LAUN_ID']]);

        $params = array('rtparams' => '', 'sb_proj_laun_res_id' => $sb_proj_laun_res_id, 'sbProjLaunTargets' => $sbProjLaunTargets);

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
		$sb_proj_laun_target_id = $request->post('sb_proj_laun_target_id');
		$target_amount = $request->post('target_amount');
		$actual_amount = $request->post('actual_amount');

		// Parameters from Other page.
		$sb_proj_laun_res_id = $request->post('sb_proj_laun_res_id'); $sb_proj_laun_res_id = (empty($sb_proj_laun_res_id)? $request->get('sb_proj_laun_res_id'): $sb_proj_laun_res_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			
    			$count = SB_PROJ_LAUN_RES_TARGET::find()->count();
    			$where_causes = ['SB_PROJ_LAUN_RES_ID' => $sb_proj_laun_res_id];
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = SB_PROJ_LAUN_RES_TARGET::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['SB_PROJ_LAUN_RES_TARGET_ID'], 
													'cell'=>array($seq,
																	$result[$i]->sbProjLaunTarget['TARGET_NAME_TH'],
																	$result[$i]['TARGET_AMOUNT'],
																	$result[$i]['ACTUAL_AMOUNT'],
																	'edit')));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit':
        		try {
    				$projLaunResTarget = SB_PROJ_LAUN_RES_TARGET::findOne($id);
    				$projLaunResTarget->SB_PROJ_LAUN_TARGET_ID = $sb_proj_laun_target_id;
    				$projLaunResTarget->TARGET_AMOUNT = $target_amount;
    				$projLaunResTarget->ACTUAL_AMOUNT = $actual_amount;

    				$response = new jqGridResponse();
                    if ($projLaunResTarget->save()) {  
                        $response->success(['id'=>$projLaunResTarget->SB_PROJ_LAUN_RES_TARGET_ID]);
                    } else {
                        $response->error($projLaunResTarget->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$projLaunResTarget = new SB_PROJ_LAUN_RES_TARGET();
    				$projLaunResTarget->SB_PROJ_LAUN_RES_TARGET_ID = SB_PROJ_LAUN_RES_TARGET::getNewID();
    				$projLaunResTarget->SB_PROJ_LAUN_RES_ID = $sb_proj_laun_res_id;
    				$projLaunResTarget->SB_PROJ_LAUN_TARGET_ID = $sb_proj_laun_target_id;
    				$projLaunResTarget->TARGET_AMOUNT = $target_amount;
    				$projLaunResTarget->ACTUAL_AMOUNT = $actual_amount;
    				
    				$response = new jqGridResponse();
                    if ($projLaunResTarget->save()) {  
                        $response->success(['id'=>$projLaunResTarget->SB_PROJ_LAUN_RES_TARGET_ID]);
                    } else {
                        $response->error($projLaunResTarget->getErrors());
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

                    SB_PROJ_LAUN_RES_TARGET::deleteAll(['in', 'SB_PROJ_LAUN_RES_TARGET_ID', $arrId]);

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
