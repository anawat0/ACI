<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\KPI_THRD ;
use app\models\KPI_SECD ;
use app\models\KPI_FST;
use app\models\KPI_DIMENTION;
use yii\helpers\Url;

class KpiThrdController extends AppController
{
	public $title = 'KPI001 XXXX 3';
                
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
        
        $kpi_dimention_id = $request->post('kpidimention'); 
        $kpi_dimention_id = (empty($kpi_dimention_id)?$request->get('kpidimention'):$kpi_dimention_id);
	
        $kpi_fst_id = $request->post('kpifst'); 
        $kpi_fst_id = (empty($kpi_fst_id)?$request->get('kpifst'):$kpi_fst_id);
        
        $kpi_secd_id = $request->post('kpisecd'); 
        $kpi_secd_id = (empty($kpi_secd_id)?$request->get('kpisecd'):$kpi_secd_id);
        
        $bugdet_year = $request->post('budgetyear'); 
        $bugdet_year = (empty($bugdet_year)?$request->get('budgetyear'):$bugdet_year);
        
        $dimention_name_th = $request->post('dimentionnameth'); 
        $dimention_name_th = (empty($dimention_name_th)?$request->get('dimentionnameth'):$dimention_name_th);
        
        $fst_name_th = $request->post('fstnameth'); 
        $fst_name_th = (empty($fst_name_th)?$request->get('fstnameth'):$fst_name_th);
        
        $secd_name_th = $request->post('secdnameth'); 
        $secd_name_th = (empty($secd_name_th)?$request->get('secdnameth'):$secd_name_th);
        
        $kpiFst = KPI_FST::find()->where(['KPI_DIMENTION_ID' => $kpi_dimention_id])->limit(1)->asArray()->one();
        
        $kpiSecd = KPI_SECD::find()->where(['KPI_FST_ID' => $kpi_fst_id])->limit(1)->asArray()->one();
                               
        //Make prepair breadcrumb.
    	$breadcrumbs = array();
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	$this->addBreadCrumb('KPI001 ตัวชี้วัดผลการปฏิบัติราชการ' , Url::to(['kpidimention/', 'budgetyear'=>$bugdet_year]) );
    	$this->addBreadCrumb('ตัวชี้วัดผลการปฏิบัติราชการ' , Url::to(['kpifst/','rtparams'=>$rtparams ,                                                                    
                                                                        'kpidimention'=>$kpi_dimention_id , 
                                                                        'kpifst'=>$kpi_fst_id ,
                                                                        'budgetyear'=>$bugdet_year ,
                                                                        'dimentionnameth'=>$dimention_name_th ,
                                                                        'fstnameth'=>$fst_name_th,
                                                                        'kpiFst'=>$kpiFst,]));        
        $this->addBreadCrumb('ตัวชี้วัดรอง' , Url::to(['kpisecd/','rtparams'=>$rtparams ,                                                                    
                                                                        'kpidimention'=>$kpi_dimention_id , 
                                                                        'kpifst'=>$kpi_fst_id ,
                                                                        'kpisecd'=>$kpi_secd_id ,
                                                                        'budgetyear'=>$bugdet_year ,
                                                                        'dimentionnameth'=>$dimention_name_th ,
                                                                        'fstnameth'=>$fst_name_th,
                                                                        'secdnameth'=>$secd_name_th,
                                                                        'kpiFst'=>$kpiFst,
                                                                        'kpiSecd'=>$kpiSecd]) ) ;
        $this->addBreadCrumb('ตัวชี้วัดย่อย') ;
        
        $params = array('title'=>$this->title,  'kpidimention'=>$kpi_dimention_id , 
                                                'kpifst'=>$kpi_fst_id ,
                                                'kpisecd'=>$kpi_secd_id ,
                                                'budgetyear'=>$bugdet_year ,
                                                'dimentionnameth'=>$dimention_name_th ,
                                                'fstnameth'=>$fst_name_th,
                                                'secdnameth'=>$secd_name_th,
                                                'kpiFst'=>$kpiFst,       
                                                'kpiSecd'=>$kpiSecd,
                                                'rtparams'=>$rtparams);
        
        return $this->render('index', $params);
    }

//    public function actionGetclubs(){
//        $request = Yii::$app->request;
//    	$province = $request->post('province', ''); $province = (empty($province)?$request->get('province', ''):$province);
//    	$result = SSB_CLUB::find()->where(['PROVINCE_CODE'=>$province])->asArray()->all();
//    	$this->ajaxResponse($result);
//    }
    
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
		$id = $request->post('id');
		$thrdnameth = $request->post('thrdnameth');
		$targetscore = $request->post('targetscore');
		$status = $request->post('status');
		
		//Parameter from another pages.
		$kpi_dimention_id = $request->post('kpidimention'); $kpi_dimention_id = (empty($kpi_dimention_id)?$request->get('kpidimention'):$kpi_dimention_id);
                $kpi_fst_id = $request->post('kpifst'); $kpi_fst_id = (empty($kpi_fst_id)?$request->get('kpifst'):$kpi_fst_id);
                $kpi_secd_id = $request->post('kpisecd'); $kpi_secd_id = (empty($kpi_secd_id)?$request->get('kpisecd'):$kpi_secd_id);
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
    			
    			$count = KPI_THRD::find()->where('KPI_SECD_ID='.$kpi_secd_id)->count();
    			$result = KPI_THRD::find()->where('KPI_SECD_ID='.$kpi_secd_id)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
                $countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array(  'id'    =>  $result[$i]['KPI_THRD_ID'], 
                                                                            'cell'  =>  array   (   $seq,
                                                                                                    $result[$i]['THRD_NAME_TH'],
                                                                                                    $result[$i]['TARGET_SCORE'],
                                                                                                    $result[$i]['STATUS'],
                                                                                                    '',
                                                                                                    'edit'
                                                                                                )));
				}

    			$result = json_encode($response);
	    		break;
    		case 'edit' :
                try {
                    $kpiThrd = KPI_THRD::findOne($id);
                    $kpiThrd->THRD_NAME_TH = $thrdnameth;
                    $kpiThrd->TARGET_SCORE = $targetscore;                               
                    $kpiThrd->STATUS = Utils::getStatus($status);
                    $kpiThrd->LAST_UPD_USER_ID = $user_id;
                    $kpiThrd->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                    
                    $response = new jqGridResponse();
                    if ($kpiThrd->save()) {  
                        $response->success(['id'=>$kpiThrd->KPI_THRD_ID]);
                    } else {
                        $response->error($kpiThrd->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
		    case 'add':
                try {                                                              
                    $kpiThrd = new KPI_THRD();
                    $kpiThrd->KPI_THRD_ID = KPI_THRD::getNewID();
                    $kpiThrd->KPI_SECD_ID = $kpi_secd_id ;
                    $kpiThrd->THRD_NAME_TH = $thrdnameth;
                    $kpiThrd->TARGET_SCORE = $targetscore;                               
                    $kpiThrd->STATUS = Utils::getStatus($status);
                    $kpiThrd->CREATE_USER_ID = $user_id;
                    $kpiThrd->CREATE_TIME = new \yii\db\Expression('SYSDATE');
                    $kpiThrd->LAST_UPD_USER_ID = $user_id;
                    $kpiThrd->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');				

                    $response = new jqGridResponse();
                    if ($kpiThrd->save()) {  
                        $response->success(['id'=>$kpiThrd->KPI_THRD_ID]);
                    } else {
                        $response->error($kpiThrd->getErrors());
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

                    KPI_THRD::deleteAll(['in', 'KPI_THRD_ID', $arrId]);

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
