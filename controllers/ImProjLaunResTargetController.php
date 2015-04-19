<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Authentication;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\IM_PROJ_LAUN_TARGET;
use app\models\IM_PROJ_LAUN_RES;
use app\models\IM_PROJ_LAUN_RES_TARGET;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class ImProjLaunResTargetController extends AppController
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

        $im_proj_laun_res_id = $request->get('im_proj_laun_res_id');
        $imProjLaunRes = IM_PROJ_LAUN_RES::findOne($im_proj_laun_res_id);
        $imProjLaunTargets = IM_PROJ_LAUN_TARGET::findAll(['IM_PROJ_LAUN_ID' => $imProjLaunRes->imProjLaun['IM_PROJ_LAUN_ID']]);

        $params = ['rtparams' => '', 
			        'im_proj_laun_res_id' => $im_proj_laun_res_id, 
			        'imProjLaunTargets' => $imProjLaunTargets];

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
		$im_proj_laun_target_id = $request->post('im_proj_laun_target_id');
		$target_qt = $request->post('target_qt');
		$actual_qt = $request->post('actual_qt');
		$status = $request->post('status');

		// Parameters from Other page.
		$im_proj_laun_res_id = $request->post('im_proj_laun_res_id'); $im_proj_laun_res_id = (empty($im_proj_laun_res_id)? $request->get('im_proj_laun_res_id'): $im_proj_laun_res_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			
    			$count = IM_PROJ_LAUN_RES_TARGET::find()->count();
    			$where_causes = ['IM_PROJ_LAUN_RES_ID' => $im_proj_laun_res_id];
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = IM_PROJ_LAUN_RES_TARGET::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
    			$seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['IM_PROJ_LAUN_RES_TARGET_ID'], 
													'cell'=>array($seq,
																	$result[$i]->imProjLaunTarget['TARGET_NAME_TH'],
																	$result[$i]['TARGET_QT'],
																	$result[$i]['ACTUAL_QT'],
																	$result[$i]['STATUS'],
																	'edit')));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit':
    			//To update an existing customer record
				$projLaunResTarget = IM_PROJ_LAUN_RES_TARGET::findOne($id);
				$projLaunResTarget->IM_PROJ_LAUN_RES_ID = $im_proj_laun_res_id;
				$projLaunResTarget->IM_PROJ_LAUN_TARGET_ID = $im_proj_laun_target_id;
				$projLaunResTarget->TARGET_QT = $target_qt;
				$projLaunResTarget->ACTUAL_QT = $actual_qt;
				$projLaunResTarget->STATUS = Utils::getStatus($status);
				$projLaunResTarget->LAST_UPD_USER_ID = $user_id;
				$projLaunResTarget->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

				$response = new jqGridResponse();
				if ($projLaunResTarget->save()) {  
					$response->success(['id'=>$projLaunResTarget->IM_PROJ_LAUN_RES_TARGET_ID]);
				} else {
					$response->error($projLaunResTarget->getErrors());
				}

                $result = $response->response_encode();
    			break;
			case 'add':
				$projLaunResTarget = new IM_PROJ_LAUN_RES_TARGET();
				$projLaunResTarget->IM_PROJ_LAUN_RES_TARGET_ID = IM_PROJ_LAUN_RES_TARGET::getNewID();
				$projLaunResTarget->IM_PROJ_LAUN_RES_ID = $im_proj_laun_res_id;
				$projLaunResTarget->IM_PROJ_LAUN_TARGET_ID = $im_proj_laun_target_id;
				$projLaunResTarget->TARGET_QT = $target_qt;
				$projLaunResTarget->ACTUAL_QT = $actual_qt;
				$projLaunResTarget->STATUS = Utils::getStatus($status);
				$projLaunResTarget->CREATE_USER_ID = $user_id;
				$projLaunResTarget->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$projLaunResTarget->LAST_UPD_USER_ID = $user_id;
				$projLaunResTarget->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
				if ($projLaunResTarget->save()) {  
					$response->success(['id'=>$projLaunResTarget->IM_PROJ_LAUN_RES_TARGET_ID]);
				} else {
					$response->error($projLaunResTarget->getErrors());
				}
				$result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    IM_PROJ_LAUN_RES_TARGET::deleteAll(['in', 'IM_PROJ_LAUN_RES_TARGET_ID', $arrId]);

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
