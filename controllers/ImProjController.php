<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\AppController;
use yii\web\Authentication;
use yii\web\Utils;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\IM_MAIN_PROJ;
use app\models\IM_PROJ;

class ImProjController extends AppController
{
	public $title = 'IM001 รายชื่อโครงการ สอม.';
	
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

    	$imMainProjId = $request->post('im_main_proj_id'); $imMainProjId = (empty($imMainProjId)? $request->get('im_main_proj_id'): $imMainProjId);
    	$imMainProj = IM_MAIN_PROJ::findOne($imMainProjId);

        $this->addBreadCrumb('IM001 รายชื่อโครงการ สอม.', Url::to(['immainproj/']));
        $this->addBreadCrumb('โครงการ');

    	$params = array('rtparams' => '', 'imMainProj' => $imMainProj);
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
		$imMainProjId = $request->post('im_main_proj_id'); $imMainProjId = (empty($imMainProjId)? $request->get('im_main_proj_id'): $imMainProjId);
		$projNameTh = $request->post('proj_name_th');
		$projNameEn = $request->post('proj_name_en');
		$budget = $request->post('budget');
		$status = $request->post('status');
                       
		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array('IM_MAIN_PROJ_ID' => $imMainProjId);
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
    			
    			$count = IM_PROJ::find()->where($where_causes)->count();
    			$result = IM_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;

					array_push($response->rows, array('id' => $result[$i]['IM_PROJ_ID'], 
                                                      'cell' => array($seq,
                                                      					$result[$i]['IM_MAIN_PROJ_ID'],
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
				$imProj = IM_PROJ::findOne($id);
				$imProj->IM_MAIN_PROJ_ID = $imMainProjId;
				$imProj->PROJ_NAME_TH = $projNameTh;
				$imProj->PROJ_NAME_EN = $projNameEn;
				$imProj->BUDGET = $budget; 
				$imProj->STATUS = Utils::getStatus($status);
				$imProj->LAST_UPD_USER_ID = $user_id;
				$imProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($imProj->save()) {  
                    $response->success(['id'=>$imProj->IM_PROJ_ID]);
                } else {
                    $response->error($imProj->getErrors());
                }

                $result = $response->response_encode();
    			break;
			case 'add':
				$imProj = new IM_PROJ();
				$imProj->IM_PROJ_ID = IM_PROJ::getNewID();
				$imProj->IM_MAIN_PROJ_ID = $imMainProjId;
				$imProj->PROJ_NAME_TH = $projNameTh;
				$imProj->PROJ_NAME_EN = $projNameEn;
				$imProj->BUDGET = $budget;
				$imProj->STATUS = Utils::getStatus($status);
				$imProj->CREATE_USER_ID = $user_id;
				$imProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$imProj->LAST_UPD_USER_ID = $user_id;
				$imProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
                if ($imProj->save()) {  
                    $response->success(['id'=>$imProj->IM_PROJ_ID]);
                } else {
                    $response->error($imProj->getErrors());
                }

                $result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    IM_PROJ::deleteAll(['in', 'IM_PROJ_ID', $arrId]);

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
