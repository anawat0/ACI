<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SB_MAIN_PROJ;
use app\models\SB_PROJ;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbprojController extends AppController
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
    	$sb_main_proj_id = $request->post('sb_main_proj_id'); $sb_main_proj_id = (empty($sb_main_proj_id)? $request->get('sb_main_proj_id'): $sb_main_proj_id);
		$sbMainProj = SB_MAIN_PROJ::findOne($sb_main_proj_id);

		//Make prepair breadcrumb.
		$breadcrumbs = array();
		$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
		$rtparams2 = $request->post('rtparams2'); $rtparams2 = (empty($rtparams2)?$request->get('rtparams2'):$rtparams2);
		$this->addBreadCrumb('SB001 รายชื่อโครงการ สกฬ.', Url::to(['sbmainproj/', 'rtparams'=>$rtparams]));
		$this->addBreadCrumb('โครงการ');

		$params = array('sb_main_proj_id' => $sb_main_proj_id, 'sbMainProj' => $sbMainProj, 'rtparams' => $rtparams, 'rtparams2' => $rtparams2);

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
		$budget = $request->post('budget');
		$status = $request->post('status');

		//Parameter from another pages.
		$sb_main_proj_id = $request->post('sb_main_proj_id'); $sb_main_proj_id = (empty($sb_main_proj_id)? $request->get('sb_main_proj_id'): $sb_main_proj_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = SB_PROJ::find()->count();
    			$where_causes = ['SB_MAIN_PROJ_ID' => $sb_main_proj_id];
    			// if($isSearch){
    			// 	$where_causes = ['SB_MAIN_PROJ_ID' => $sb_main_proj_id];
    			// }
    			
    			$result = SB_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
    			$seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
					$seq++;
					
					array_push($response->rows, array('id'=>$result[$i]['SB_PROJ_ID'], 
													'cell'=>array($seq,
																	$result[$i]['PROJ_NAME_TH'],
																	$result[$i]['PROJ_NAME_EN'],
																	$result[$i]['BUDGET'],
																	$result[$i]['STATUS'],
																	$result[$i]['SB_PROJ_ID'],
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
					$proj = SB_PROJ::findOne($id);
					$proj->PROJ_NAME_TH = $nameth;
					$proj->PROJ_NAME_EN = $nameen;
					$proj->BUDGET = $budget;
					$proj->STATUS = Utils::getStatus($status);
					$proj->LAST_UPD_USER_ID = $user_id;
					$proj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
					
					$response = new jqGridResponse();
					if ($proj->save()) {  
						$response->success(['id'=>$proj->SB_PROJ_ID]);
					} else {
						$response->error($proj->getErrors());
					}
				} catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
				try {
					$proj = new SB_PROJ();
					$proj->SB_PROJ_ID = SB_PROJ::getNewID();
					$proj->SB_MAIN_PROJ_ID = $sb_main_proj_id;
					$proj->PROJ_NAME_TH = $nameth;
					$proj->PROJ_NAME_EN = $nameen;
					$proj->BUDGET = $budget;
					$proj->STATUS = Utils::getStatus($status);
					$proj->CREATE_USER_ID = $user_id;
					$proj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
					$proj->LAST_UPD_USER_ID = $user_id;
					$proj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

					$response = new jqGridResponse();
					if ($proj->save()) {  
						$response->success(['id'=>$proj->SB_PROJ_ID]);
					} else {
						$response->error($proj->getErrors());
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

                    SB_PROJ::deleteAll(['in', 'SB_PROJ_ID', $arrId]);

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
