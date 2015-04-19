<?php

namespace app\controllers;

use Yii;
use Yii\BaseYii;
use yii\web\AppController;
use yii\web\Utils;
use yii\web\Authentication;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\helpers\BaseArrayHelper;
use app\models\RB_PROJ;
use app\models\RB_SUB_PROJ;

class RbSubProjController extends AppController
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
    	
    	$user_info = Authentication::getAuthenInfo();
    	$user_id = $user_info->EMAIL;
    	
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	$rtparams2 = $request->post('rtparams2'); $rtparams2 = (empty($rtparams2)?$request->get('rtparams2'):$rtparams2);

    	$rb_main_proj_id = $request->post('rb_main_proj_id'); $rb_main_proj_id = (empty($rb_main_proj_id)? $request->get('rb_main_proj_id'): $rb_main_proj_id);
		$rbProjId = $request->post('rb_proj_id'); $rbProjId = (empty($rbProjId)? $request->get('rb_proj_id'): $rbProjId);
		$rbProj = RB_PROJ::findOne($rbProjId);
		
		$tmp_params['rb_main_proj_id'] = $rb_main_proj_id;
		$tmp_params['rb_proj_id'] = $rbProjId;
		
		$backAction1 = ['rbmainproj/', 'rtparams'=>$rtparams];
		$backAction1 = BaseArrayHelper::merge($backAction1, ['rb_main_proj_id'=>$rb_main_proj_id]);
    	
    	$backAction2 = ['rbproj/', 'rtparams'=>$rtparams];
    	$backAction2 = BaseArrayHelper::merge($backAction2, $tmp_params);

		$this->addBreadCrumb('RD001 รายชื่อโครงการ สนก.', Url::to($backAction1));
    	$this->addBreadCrumb('แผนงาน/โครงการ', Url::to($backAction2));
    	$this->addBreadCrumb('โครงการย่อย');

		$params = array('rtparams' => $rtparams, 'rtparams2' => $rtparams2, 'rbProj' => $rbProj);
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
		$rbProjId = $request->post('rb_proj_id'); $rbProjId = (empty($rbProjId)? $request->get('rb_proj_id'): $rbProjId);
		$subProjNameTh = $request->post('sub_proj_name_th');
		$subProjNameEn = $request->post('sub_proj_name_en');
		$budget = $request->post('budget');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array('RB_PROJ_ID' => $rbProjId);
    			$where = 'and';
    			if ($isSearch) {
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
    			
    			$count = RB_SUB_PROJ::find()->where($where_causes)->count();
    			$result = RB_SUB_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;

					array_push($response->rows, array('id' => $result[$i]['RB_SUB_PROJ_ID'], 
                                                      'cell' => array($seq,
                                                      					$result[$i]['RB_PROJ_ID'],
                                                                        $result[$i]['SUB_PROJ_NAME_TH'],
                                                                        $result[$i]['SUB_PROJ_NAME_EN'],
                                                                        $result[$i]['BUDGET'],
                                                                        $result[$i]['STATUS'],
                                                                        'edit')));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit':
    			//To update an existing customer record
				$rbSubProj = RB_SUB_PROJ::findOne($id);
				$rbSubProj->RB_PROJ_ID = $rbProjId;
				$rbSubProj->SUB_PROJ_NAME_TH = $subProjNameTh;
				$rbSubProj->SUB_PROJ_NAME_EN = $subProjNameEn;
				$rbSubProj->BUDGET = $budget; 
				$rbSubProj->STATUS = Utils::getStatus($status);
				$rbSubProj->LAST_UPD_USER_ID = $user_id;
				$rbSubProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($rbSubProj->save()) {  
                    $response->success(['id'=>$rbSubProj->RB_SUB_PROJ_ID]);
                } else {
                    $response->error($rbSubProj->getErrors());
                }

                $result = $response->response_encode();
    			break;
			case 'add':
				$rbSubProj = new RB_SUB_PROJ();
				$rbSubProj->RB_SUB_PROJ_ID = RB_PROJ::getNewID();
				$rbSubProj->RB_PROJ_ID = $rbProjId;
				$rbSubProj->SUB_PROJ_NAME_TH = $subProjNameTh;
				$rbSubProj->SUB_PROJ_NAME_EN = $subProjNameEn;
				$rbSubProj->BUDGET = $budget;
				$rbSubProj->STATUS = Utils::getStatus($status);
				$rbSubProj->CREATE_USER_ID = $user_id;
				$rbSubProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$rbSubProj->LAST_UPD_USER_ID = $user_id;
				$rbSubProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($rbSubProj->save()) {  
                    $response->success(['id'=>$rbSubProj->RB_SUB_PROJ_ID]);
                } else {
                    $response->error($rbSubProj->getErrors());
                }

                $result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    RB_SUB_PROJ::deleteAll(['in', 'RB_SUB_PROJ_ID', $arrId]);

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
