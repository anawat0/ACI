<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_MENU_MAIN;

class ManmenumainController extends AppController
{
	public $title = 'WA001 จัดการเมนูหลัก';
	
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
        $this->addBreadCrumb('WA001 จัดการเมนู');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    public function actionGridview()
    {
    	$request = Yii::$app->request;

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
		$description = $request->post('description');
		$seq = $request->post('seq');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array();
    			$where = 'and';
    			if($isSearch){
    				$filters = json_decode($filters);
    				// print_r($filters);
    				switch($filters->groupOp){
    					case 'AND':
    						$where = 'and';
    						break;
    					case 'OR':
    						$where = 'or';
    						break;
    				}
    				//['or', 'WA_MENU_MAIN_ID=\'33\'', 'STATUS=\'A\'']
    				array_push($where_causes, $where);
    				foreach($filters->rules as $conditions){
    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				}
    			}
    			
    			$count = WA_MENU_MAIN::find()->where($where_causes)->count();
    			$result = WA_MENU_MAIN::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['WA_MENU_MAIN_ID'], 
													'cell'=>array($seq,
																	$result[$i]['MENU_MAIN_NAME_TH'],
																	$result[$i]['DESCRIPTION'],
																	$result[$i]['SEQ'],
																	$result[$i]['STATUS'],
                                                                    '',
                                                                    'edit'
																	)));
				}
				
    			$result = json_encode($response);
    			
	    		break;
    		case 'edit':
    			//To update an existing customer record
				$menuMain = WA_MENU_MAIN::findOne($id);
				$menuMain->MENU_MAIN_NAME_TH = $nameth;
				// $menuMain->MENU_MAIN_NAME_EN = $nameen;
				$menuMain->DESCRIPTION = $description;
				$menuMain->STATUS = Utils::getStatus($status);
				$menuMain->SEQ = $seq;
				$menuMain->LAST_UPD_USER_ID = '1';
				$menuMain->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
                $response = new jqGridResponse();
                if ($menuMain->save()) {  
                    $response->success(['id'=>$menuMain->WA_MENU_MAIN_ID]);
                } else {
                    $response->error($menuMain->getErrors());
                }

                $result = $response->response_encode();
    			break;
			case 'add':
				$menuMain = new WA_MENU_MAIN();
				$menuMain->WA_MENU_MAIN_ID = WA_MENU_MAIN::getNewID();
				$menuMain->MENU_MAIN_NAME_TH = $nameth;
				// $menuMain->MENU_MAIN_NAME_EN = $nameen;
				$menuMain->DESCRIPTION = $description;
				$menuMain->STATUS = Utils::getStatus($status);
				$menuMain->SEQ = $seq;
				$menuMain->CREATE_USER_ID = '1';
				$menuMain->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$menuMain->LAST_UPD_USER_ID = '1';
				$menuMain->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
                $response = new jqGridResponse();
                if ($menuMain->save()) {  
                    $response->success(['id'=>$menuMain->WA_MENU_MAIN_ID]);
                } else {
                    $response->error($menuMain->getErrors());
                }

                $result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    WA_MENU_MAIN::deleteAll(['in', 'WA_MENU_MAIN_ID', $arrId]);

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
