<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_GROUP;

class WagroupController extends AppController
{
	public $title = 'WA002 จัดการกลุ่มผู้ใช้งาน';
	
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
    	
    	$this->addBreadCrumb('WA002 จัดการกลุ่มผู้ใช้งาน');
    	
    	$params['rtparams']=$rtparams;
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
		$ldaporgcode = $request->post('ldaporgcode');

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			
    			$offset = ($page-1)*$rows;
    			
    			$count = WA_GROUP::find()->count();
    			$where_causes = array();
    			if($isSearch){
    				//['type' => 1, 'status' => 2]
    				//TODO: add condition to $where_causes.
    				
    			}
    			
    			$result = WA_GROUP::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['WA_GROUP_ID'], 
														'cell'=>array($seq,
																	$result[$i]['GROUP_NAME_TH'],
																	'',
																	'',
																	$result[$i]['LDAP_ORG_CODE'],
																	'edit'
																	)));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit':
    			//To update an existing customer record
				$waGroup = WA_GROUP::findOne($id);
				$waGroup->GROUP_NAME_TH = $nameth;
				$waGroup->LDAP_ORG_CODE = $ldaporgcode;
				$waGroup->LAST_UPD_USER_ID = '1';
				$waGroup->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
				if ($waGroup->save()) {  
					$response->success(['id'=>$waGroup->WA_GROUP_ID]);
				} else {
					$response->error($waGroup->getErrors());
				}

                $result = $response->response_encode();
    			break;
			case 'add':
				$waGroup = new WA_GROUP();
				$waGroup->WA_GROUP_ID = WA_GROUP::getNewID();
				$waGroup->GROUP_NAME_TH = $nameth;
				$waGroup->LDAP_ORG_CODE = $ldaporgcode;
				$waGroup->STATUS = 'A';
				$waGroup->CREATE_USER_ID = '1';
				$waGroup->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$waGroup->LAST_UPD_USER_ID = '1';
				$waGroup->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
				if ($waGroup->save()) {  
					$response->success(['id'=>$waGroup->WA_GROUP_ID]);
				} else {
					$response->error($waGroup->getErrors());
				}

                $result = $response->response_encode();
				break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    WA_GROUP::deleteAll(['in', 'WA_GROUP_ID', $arrId]);

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
