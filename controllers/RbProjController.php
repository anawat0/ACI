<?php

namespace app\controllers;

use Yii;
use Yii\base\Exception;
use yii\web\AppController;
use yii\web\Utils;
use yii\web\Authentication;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\helpers\BaseArrayHelper;
use app\models\RB_MAIN_PROJ;
use app\models\RB_PROJ;
use app\models\RB_SUB_PROJ;

class RbProjController extends AppController
{
	public $title = 'RD001 รายชื่อโครงการ สนก.';
	
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
    	$rtparams2 = $request->post('rtparams2'); $rtparams2 = (empty($rtparams2)?$request->get('rtparams2'):$rtparams2);
    	
    	$rbMainProjId = $request->post('rb_main_proj_id'); $rbMainProjId = (empty($rbMainProjId)? $request->get('rb_main_proj_id'): $rbMainProjId);
    	$rbMainProj = RB_MAIN_PROJ::findOne($rbMainProjId);
    	
    	$tmp_params['rb_main_proj_id'] = $rbMainProjId;
    	
    	$backAction = ['rbmainproj/', 'rtparams'=>$rtparams];
    	$backAction = BaseArrayHelper::merge($backAction, $tmp_params);

        $this->addBreadCrumb('RD001 รายชื่อโครงการ สนก.', Url::to($backAction));
        $this->addBreadCrumb('แผนงาน/โครงการ');

    	$params = array('rtparams' => $rtparams, 'rtparams2' => $rtparams2, 'rbMainProj' => $rbMainProj);
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
		$rbMainProjId = $request->post('rb_main_proj_id'); $rbMainProjId = (empty($rbMainProjId)? $request->get('rb_main_proj_id'): $rbMainProjId);
		$projNameTh = $request->post('proj_name_th');
		$projNameEn = $request->post('proj_name_en');
		$budget = $request->post('budget');
		$status = $request->post('status');
                       
		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array('RB_MAIN_PROJ_ID' => $rbMainProjId);
    			$where = 'and';
    			if($isSearch) {
    				$filters = json_decode($filters);
    				// print_r($filters);
    				switch ($filters->groupOp) {
    					case 'AND':
    						$where = 'and';
    						break;
    					case 'OR':
    						$where = 'or';
    						break;
    				}
    				array_push($where_causes, $where);
    				foreach ($filters->rules as $conditions) {
    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				}
    			}
    			
    			$count = RB_PROJ::find()->where($where_causes)->count();
    			$result = RB_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;

					array_push($response->rows, array('id' => $result[$i]['RB_PROJ_ID'], 
                                                      'cell' => array($seq,
                                                      					$result[$i]['RB_MAIN_PROJ_ID'],
                                                                        $result[$i]['PROJ_NAME_TH'],
                                                                        $result[$i]['PROJ_NAME_EN'],
                                                                        $result[$i]['BUDGET'],
                                                                        $result[$i]['STATUS'],
                                                                        '',
                                                                        'edit')));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit':
    			//To update an existing customer record
				$rbProj = RB_PROJ::findOne($id);
				$rbProj->RB_MAIN_PROJ_ID = $rbMainProjId;
				$rbProj->PROJ_NAME_TH = $projNameTh;
				$rbProj->PROJ_NAME_EN = $projNameEn;
				$rbProj->BUDGET = $budget; 
				$rbProj->STATUS = Utils::getStatus($status);
				$rbProj->S_PROJ_STATUS = Utils::getStatus($status);
				$rbProj->LAST_UPD_USER_ID = $user_id;
				$rbProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($rbProj->save()) {  
                    $response->success(['id'=>$rbProj->RB_PROJ_ID]);
                } else {
                    $response->error($rbProj->getErrors());
                }

                $result = $response->response_encode();
    			break;
			case 'add':
				$rbProj = new RB_PROJ();
				$rbProj->RB_PROJ_ID = RB_PROJ::getNewID();
				$rbProj->RB_MAIN_PROJ_ID = $rbMainProjId;
				$rbProj->PROJ_NAME_TH = $projNameTh;
				$rbProj->PROJ_NAME_EN = $projNameEn;
				$rbProj->BUDGET = $budget;
				$rbProj->STATUS = Utils::getStatus($status);
				$rbProj->S_PROJ_STATUS = Utils::getStatus($status);
				$rbProj->CREATE_USER_ID = $user_id;
				$rbProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$rbProj->LAST_UPD_USER_ID = $user_id;
				$rbProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($rbProj->save()) {  
                    $response->success(['id'=>$rbProj->RB_PROJ_ID]);
                } else {
                    $response->error($rbProj->getErrors());
                }

                $result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    RB_PROJ::deleteAll(['in', 'RB_PROJ_ID', $arrId]);

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
