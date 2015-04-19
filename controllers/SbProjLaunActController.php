<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SB_PROJ_LAUN;
use app\models\SB_PROJ_LAUN_ACT;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbProjLaunActController extends AppController
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

    	// Parameter from another page
    	$sb_proj_laun_id = $request->post('sb_proj_laun_id'); $sb_proj_laun_id = (empty($sb_proj_laun_id)? $request->get('sb_proj_laun_id'): $sb_proj_laun_id);
    	$sbProjLaun = SB_PROJ_LAUN::findOne($sb_proj_laun_id);

    	//Make prepair breadcrumb.
		$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
		$this->addBreadCrumb('SB002 โครงการที่กำลังดำเนินการ สกฬ.', Url::to(['sbprojlaun/', 'rtparams' => $rtparams]));
		$this->addBreadCrumb('รูปแบบกิจกกรม');
		
    	$params = array('sb_proj_laun_id' => $sb_proj_laun_id, 'sbProjLaun' => $sbProjLaun, 'rtparams' => '');

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
		$act_name_th = $request->post('act_name_th');
		$act_name_en = $request->post('act_name_en');
		$status = $request->post('status');

		// Parameter from another page
    	$sb_proj_laun_id = $request->post('sb_proj_laun_id'); $sb_proj_laun_id = (empty($sb_proj_laun_id)? $request->get('sb_proj_laun_id'): $sb_proj_laun_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = SB_PROJ_LAUN_ACT::find()->count();
    			$where_causes = ['SB_PROJ_LAUN_ID' => $sb_proj_laun_id];
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = SB_PROJ_LAUN_ACT::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['SB_PROJ_LAUN_ACT_ID'], 
													'cell'=>array($seq,
																	$result[$i]['ACT_NAME_TH'],
																	$result[$i]['ACT_NAME_EN'],
																	$result[$i]['STATUS'],
																	$result[$i]['SB_PROJ_LAUN_ID'],
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
    				$projLaunAct = SB_PROJ_LAUN_ACT::findOne($id);
    				$projLaunAct->SB_PROJ_LAUN_ID = $sb_proj_laun_id;
    				$projLaunAct->ACT_NAME_TH = $act_name_th;
    				$projLaunAct->ACT_NAME_EN = $act_name_en;
    				$projLaunAct->STATUS = Utils::getStatus($status);
    				$projLaunAct->LAST_UPD_USER_ID = $user_id;
    				$projLaunAct->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

    				$response = new jqGridResponse();
                    if ($projLaunAct->save()) {  
                        $response->success(['id'=>$projLaunAct->SB_PROJ_LAUN_ACT_ID]);
                    } else {
                        $response->error($projLaunAct->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$projLaunAct = new SB_PROJ_LAUN_ACT();
    				$projLaunAct->SB_PROJ_LAUN_ACT_ID = SB_PROJ_LAUN_ACT::getNewID();
    				$projLaunAct->SB_PROJ_LAUN_ID = $sb_proj_laun_id;
    				$projLaunAct->ACT_NAME_TH = $act_name_th;
    				$projLaunAct->ACT_NAME_EN = $act_name_en;
    				$projLaunAct->STATUS = Utils::getStatus($status);
    				$projLaunAct->CREATE_USER_ID = $user_id;
    				$projLaunAct->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$projLaunAct->LAST_UPD_USER_ID = $user_id;
    				$projLaunAct->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

    				$response = new jqGridResponse();
                    if ($projLaunAct->save()) {  
                        $response->success(['id'=>$projLaunAct->SB_PROJ_LAUN_ACT_ID]);
                    } else {
                        $response->error($projLaunAct->getErrors());
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

                    SB_PROJ_LAUN_ACT::deleteAll(['SB_PROJ_LAUN_ACT_ID' => $arrId]);

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
