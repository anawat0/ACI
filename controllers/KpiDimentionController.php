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
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\KPI_DIMENTION;


class KpiDimentionController extends AppController
{
	public $title = 'KPI001 XXXX';
                
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
        $this->addBreadCrumb('KPI001 ตัวชี้วัดผลการปฏิบัติราชการ');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }

    public function actionGetclubs(){
        $request = Yii::$app->request;
    	$province = $request->post('province', ''); $province = (empty($province)?$request->get('province', ''):$province);
    	$result = SSB_CLUB::find()->where(['PROVINCE_CODE'=>$province])->asArray()->all();
    	$this->ajaxResponse($result);
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
		$dimentionnameth = $request->post('dimentionnameth');
		$budgetyear = $request->post('budgetyear');
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
    				array_push($where_causes, $where);
    				foreach($filters->rules as $conditions){
    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				}
    			}
    			
    			$count = KPI_DIMENTION::find()->where($where_causes)->count();
    			$result = KPI_DIMENTION::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array(  'id'    =>  $result[$i]['KPI_DIMENTION_ID'], 
                                                                            'cell'  =>  array   ($seq,
                                                                                                    $result[$i]['DIMENTION_NAME_TH'],
                                                                                                    $result[$i]['BUDGET_YEAR'],
                                                                                                    //$result[$i]['SEQ'],
                                                                                                    $result[$i]['STATUS'],
                                                                                                    '',
                                                                                                    'edit'
                                                                                                )));
				}

    			$result = json_encode($response);
    			
	    		break;
	    		
    		case 'edit' :
    			try {
    				$kpiDimention = KPI_DIMENTION::findOne($id);
    				$kpiDimention->BUDGET_YEAR = $budgetyear;
    				$kpiDimention->DIMENTION_NAME_TH = $dimentionnameth;
    				$kpiDimention->STATUS = Utils::getStatus($status);
    				$kpiDimention->LAST_UPD_USER_ID = $user_id;
    				$kpiDimention->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    				
                    $response = new jqGridResponse();
                    if ($kpiDimention->save()) {  
                        $response->success(['id'=>$kpiDimention->KPI_DIMENTION_ID]);
                    } else {
                        $response->error($kpiDimention->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$kpiDimention = new KPI_DIMENTION();
    				$kpiDimention->KPI_DIMENTION_ID = KPI_DIMENTION::getNewID();
    				$kpiDimention->BUDGET_YEAR = $budgetyear;
    				$kpiDimention->DIMENTION_NAME_TH = $dimentionnameth;
    				$kpiDimention->STATUS = Utils::getStatus($status);
    				$kpiDimention->SEQ = 0;
    				$kpiDimention->CREATE_USER_ID = $user_id;
    				$kpiDimention->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$kpiDimention->LAST_UPD_USER_ID = $user_id;
    				$kpiDimention->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    				
                    $response = new jqGridResponse();
                    if ($kpiDimention->save()) {  
                        $response->success(['id'=>$kpiDimention->KPI_DIMENTION_ID]);
                    } else {
                        $response->error($kpiDimention->getErrors());
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

                    KPI_DIMENTION::deleteAll(['in', 'KPI_DIMENTION_ID', $arrId]);

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
