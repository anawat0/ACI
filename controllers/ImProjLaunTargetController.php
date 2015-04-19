<?php

namespace app\controllers;

use Yii;
use yii\web\AppController;
use yii\web\Authentication;
use yii\web\Utils;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\IM_PROJ_LAUN;
use app\models\IM_PROJ_LAUN_TARGET;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class ImProjLaunTargetController extends AppController
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

    	//Parameter from another pages.
		$im_proj_laun_id = $request->post('im_proj_laun_id'); $im_proj_laun_id = (empty($im_proj_laun_id)? $request->get('im_proj_laun_id'): $im_proj_laun_id);
		$imProjLaun = IM_PROJ_LAUN::findOne($im_proj_laun_id);

		//Make prepair breadcrumb.
		$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
		$this->addBreadCrumb('IM002 โครงการที่กำลังดำเนินการ สอม.', Url::to(['improjlaun/', 'rtparams' => $rtparams]));
		$this->addBreadCrumb('กลุ่มเป้าหมาย');

		$params = array('im_proj_laun_id' => $im_proj_laun_id, 
						'imProjLaun' => $imProjLaun, 
						'rtparams' => '');

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
		$target_name_th = $request->post('target_name_th');
		$target_name_en = $request->post('target_name_en');
		$target_amount = $request->post('target_amount');
		$status = $request->post('status');

		//Parameter from another pages.
		$im_proj_laun_id = $request->post('im_proj_laun_id'); $im_proj_laun_id = (empty($im_proj_laun_id)? $request->get('im_proj_laun_id'): $im_proj_laun_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = IM_PROJ_LAUN_TARGET::find()->count();
    			$where_causes = ['IM_PROJ_LAUN_ID' => $im_proj_laun_id];
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = IM_PROJ_LAUN_TARGET::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['IM_PROJ_LAUN_TARGET_ID'], 
													'cell'=>array($seq,
																	$result[$i]['TARGET_NAME_TH'],
																	$result[$i]['TARGET_NAME_EN'],
																	$result[$i]['TARGET_AMOUNT'],
																	$result[$i]['STATUS'],
																	'edit')));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit' :
    			//To update an existing customer record
				$projLaunTarget = IM_PROJ_LAUN_TARGET::findOne($id);
				$projLaunTarget->IM_PROJ_LAUN_ID = $im_proj_laun_id;
				$projLaunTarget->TARGET_NAME_TH = $target_name_th;
				$projLaunTarget->TARGET_NAME_EN = $target_name_en;
				$projLaunTarget->TARGET_AMOUNT = $target_amount;
				$projLaunTarget->STATUS = Utils::getStatus($status);
				$projLaunTarget->LAST_UPD_USER_ID = $user_id;
				$projLaunTarget->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

				$response = new jqGridResponse();
				if ($projLaunTarget->save()) {  
					$response->success(['id'=>$projLaunTarget->IM_PROJ_LAUN_TARGET_ID]);
				} else {
					$response->error($projLaunTarget->getErrors());
				}

                $result = $response->response_encode();
    			break;
			case 'add':
				$projLaunTarget = new IM_PROJ_LAUN_TARGET();
				$projLaunTarget->IM_PROJ_LAUN_TARGET_ID = IM_PROJ_LAUN_TARGET::getNewID();
				$projLaunTarget->IM_PROJ_LAUN_ID = $im_proj_laun_id;
				$projLaunTarget->TARGET_NAME_TH = $target_name_th;
				$projLaunTarget->TARGET_NAME_EN = $target_name_en;
				$projLaunTarget->TARGET_AMOUNT = $target_amount;
				$projLaunTarget->STATUS = Utils::getStatus($status);
				$projLaunTarget->CREATE_USER_ID = $user_id;
				$projLaunTarget->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$projLaunTarget->LAST_UPD_USER_ID = $user_id;
				$projLaunTarget->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

				$response = new jqGridResponse();
				if ($projLaunTarget->save()) {  
					$response->success(['id'=>$projLaunTarget->IM_PROJ_LAUN_TARGET_ID]);
				} else {
					$response->error($projLaunTarget->getErrors());
				}

                $result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    IM_PROJ_LAUN_TARGET::deleteAll(['in', 'IM_PROJ_LAUN_TARGET_ID', $arrId]);

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
