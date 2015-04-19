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
use app\models\SB_PROJ;
use app\models\SB_SUB_PROJ;
use app\models\SB_PROJ_LAUN;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbProjLaunController extends AppController
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

    	// Parameters.
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
       
    	$listSbProj = array();
    	$sbProj = SB_PROJ::find()->all();
    	$listSbProj[''] = '--- กรุณาเลือก ---';
    	foreach ($sbProj as $key => $value) 
    	{
    		$listSbProj[$value['SB_PROJ_ID']] = $value['PROJ_NAME_TH'];
    	}

    	$params = array('listSbProj' => json_encode($listSbProj), 'rtparams' => $rtparams);

    	$this->addBreadCrumb('SB002 โครงการที่กำลังดำเนินการ สกฬ.');

        return $this->render('index', $params);
    }

    public function actionGetListSbSubProj() 
    {
    	$request = Yii::$app->request;
    	$listSbSubProj = array();

    	$sbProjId = $request->post('sb_proj_id'); $sbProjId = (empty($sbProjId)? $request->get('sb_proj_id'): $sbProjId);
    	$sbSubProj = SB_SUB_PROJ::find()->where(['SB_PROJ_ID' => $sbProjId])->asArray()->all();
    	foreach ($sbSubProj as $key => $value) 
    	{
    		array_push($listSbSubProj, array('SB_SUB_PROJ_ID' => $value['SB_SUB_PROJ_ID'], 
    											'SUB_PROJ_NAME_TH' => $value['SUB_PROJ_NAME_TH']));
    	}

    	return json_encode($listSbSubProj);
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
		$sbProjId = $request->post('sb_proj_id');
		$sbSubProjId = $request->post('sb_sub_proj_id');
		$budgetYear = $request->post('budget_year');
		$projectResponsible = $request->post('project_responsible');
		$budget = $request->post('budget');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = SB_PROJ_LAUN::find()->count();
    			$where_causes = array();
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = SB_PROJ_LAUN::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['SB_PROJ_LAUN_ID'], 
													'cell'=>array($seq,
																	$result[$i]->sbProj['PROJ_NAME_TH'],
																	$result[$i]['SB_SUB_PROJ_ID'],
																	$result[$i]->sbSubProj['SUB_PROJ_NAME_TH'],
																	$result[$i]['BUDGET_YEAR'],
																	$result[$i]['PROJECT_RESPONSIBLE'],
																	$result[$i]['BUDGET'],
																	$result[$i]['STATUS'],
																	$result[$i]['CREATE_USER_ID'],
																	$result[$i]['CREATE_TIME'],
																	$result[$i]['LAST_UPD_USER_ID'],
																	$result[$i]['LAST_UPD_TIME'],
																	'edit')));
				}
    			$result = json_encode($response);
	    		break;
    		case 'edit' :
                try {
    				$projLaun = SB_PROJ_LAUN::findOne($id);
    				$projLaun->SB_PROJ_ID = $sbProjId;
    				$projLaun->SB_SUB_PROJ_ID = $sbSubProjId;
    				$projLaun->BUDGET_YEAR = $budgetYear;
    				$projLaun->PROJECT_RESPONSIBLE = $projectResponsible;
    				$projLaun->BUDGET = $budget;
    				$projLaun->STATUS = Utils::getStatus($status);
    				$projLaun->LAST_UPD_USER_ID = $user_id;
    				$projLaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

    				$response = new jqGridResponse();
                    if ($projLaun->save()) {  
                        $response->success(['id'=>$projLaun->SB_PROJ_LAUN_ID]);
                    } else {
                        $response->error($projLaun->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$projLaun = new SB_PROJ_LAUN();
    				$projLaun->SB_PROJ_LAUN_ID = SB_PROJ_LAUN::getNewID();
    				$projLaun->SB_PROJ_ID = $sbProjId;
    				$projLaun->SB_SUB_PROJ_ID = $sbSubProjId;
    				$projLaun->BUDGET_YEAR = $budgetYear;
    				$projLaun->PROJECT_RESPONSIBLE = $projectResponsible;
    				$projLaun->BUDGET = $budget;
    				$projLaun->STATUS = Utils::getStatus($status);
    				// blank value in require field
    				$projLaun->WA_SECTION_ID = '1';
    				$projLaun->FROM_DATE = new \yii\db\Expression('SYSDATE');
    				$projLaun->TO_DATE = new \yii\db\Expression('SYSDATE');
    				////
    				$projLaun->CREATE_USER_ID = $user_id;
    				$projLaun->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$projLaun->LAST_UPD_USER_ID = $user_id;
    				$projLaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

    				$response = new jqGridResponse();
                    if ($projLaun->save()) {  
                        $response->success(['id'=>$projLaun->SB_PROJ_LAUN_ID]);
                    } else {
                        $response->error($projLaun->getErrors());
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

                    SB_PROJ_LAUN::deleteAll(['SB_PROJ_LAUN_ID' => $arrId]);

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
