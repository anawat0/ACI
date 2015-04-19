<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Authentication;
use yii\filters\VerbFilter;
use yii\web\Utils;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\RB_NETWORK;
use app\models\RB_NETWORK_YEAR;
use app\models\WA_AMPHOE;
use yii\db\Query;

class RbNetworkYearController extends AppController
{
	public $title = 'RD006 บันทึกเครือข่ายนันทนาการ กรมพลศึกษา ประจาปี';
                
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
        $this->addBreadCrumb('RD006 บันทึกเครือข่ายนันทนาการ กรมพลศึกษา ประจำปี');
        $params = array('rtparams'=>$rtparams);
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
		$networkname = $request->post('networkname');
		$cofirstname = $request->post('cofirstname');             
        $cotel = $request->post('cotel');
        $amphoecode = $request->post('amphoecode');
        $provincecode = $request->post('provincecode');
		$regioncode = $request->post('regioncode');
		$year = $request->post('year');
		$networkId = $request->post('networkname');
		
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
    			
    			$query = new Query;
    			 
    			$query->select('RB_NETWORK.*, RB_NETWORK_YEAR.YEAR, RB_NETWORK_YEAR.RB_NETWORK_YEAR_ID')
    			->from('RB_NETWORK')
    			->innerJoin('RB_NETWORK_YEAR', 'RB_NETWORK_YEAR.RB_NETWORK_ID=RB_NETWORK.RB_NETWORK_ID')
    			->where($where_causes);
    			$count = $query->count();
    			 
//     			$query = new Query;
    			//->orderBy($sidx.' '.$sord)
    			$query->offset($offset)
    			->limit($rows);
    			$result = $query->all();
    			$command = $query->createCommand();
    			$result = $command->queryAll();
                $countResult = count($result);
                $seq = $rows * ($page - 1);
    			
    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['RB_NETWORK_YEAR_ID'], 
													'cell'=>array($seq,
													                $result[$i]['YEAR'],
													                $result[$i]['RB_NETWORK_ID'] ,
																	$result[$i]['NETWORK_NAME'] ,
																	$result[$i]['CO_FIRST_NAME'] ,
																	$result[$i]['CO_TEL'],
																	'',//$result[$i]['REGION_CODE'] ,
                                                                    '',//$result[$i]['PROVINCE_CODE'] ,
																	'',
																	'',//$result[$i]['AMPHOE_CODE']
                                                                    '',                                                                                                                                
                                                                    '',                                                                                                                                
																	'edit')));
				}
				
    			$result = json_encode($response);
    			
	    		break;
	    		
    		case 'edit' :  
    			$rbNetworkYear = RB_NETWORK_YEAR::findOne($id);
    			$rbNetworkYear->YEAR = $year;
    			$rbNetworkYear->RB_NETWORK_ID = $networkId;
    			$rbNetworkYear->LAST_UPD_USER_ID = $user_id;
    			$rbNetworkYear->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    			
    			$response = new jqGridResponse();
    			
    			if($rbNetworkYear->save()){  // equivalent to $customer->insert();
    				$response->success(['id'=>$rbNetworkYear->RB_NETWORK_ID]);
    			}else{
    				$response->error($rbNetworkYear->getErrors());
    			}
    			
    			$result = $response->response_encode();
    			break;
		
		case 'add':	
			$rbNetworkYear = new RB_NETWORK_YEAR();
			$rbNetworkYear->RB_NETWORK_YEAR_ID = RB_NETWORK_YEAR::getNewID();
			$rbNetworkYear->YEAR = $year;
			$rbNetworkYear->RB_NETWORK_ID = $networkId;
			$rbNetworkYear->CREATE_USER_ID = $user_id;
			$rbNetworkYear->CREATE_TIME = new \yii\db\Expression('SYSDATE');
			$rbNetworkYear->LAST_UPD_USER_ID = $user_id;
			$rbNetworkYear->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
			
			$response = new jqGridResponse();
			
			if($rbNetworkYear->save()){  // equivalent to $customer->insert();
				$response->success(['id'=>$rbNetworkYear->RB_NETWORK_YEAR_ID]);
			}else{
				$response->error($rbNetworkYear->getErrors());
			}
			$result = $response->response_encode();
			break;
	
		case 'del':
            try {
                $arrId = array();
                $arrId = explode(',', $id);
                $response = new jqGridResponse();

                RB_NETWORK_YEAR::deleteAll(['in', 'RB_NETWORK_YEAR_ID', $arrId]);

                $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
            } catch (Exception $e) {
                $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
            }
            
            $result = $response->response_encode();
			break;
    	}
    	
    	echo $result;

    }
    
    public function actionProvinceList()
    {
    	$request = Yii::$app->request;
    	$listAmphoe = array();
    
    	$provinceCode = 10;
    	$provinceCode = (empty($provinceCode)? $request->get('province_code'): $provinceCode);
    	$findAmphoe = WA_AMPHOE::find()->where(['PROVINCE_CODE' => $provinceCode])->asArray()->all();
    	 
    	foreach ($findAmphoe as $key => $value)
    	{
    		array_push($listAmphoe, array('AMPHOE_CODE' => $value['AMPHOE_CODE'],
    		'AMPHOE_NAME_TH' => $value['AMPHOE_NAME_TH']));
    		//array_push($listAmphoe, array($value['AMPHOE_CODE'] => $value['AMPHOE_CODE']));
    	}
    	
    	return json_encode($listAmphoe);
    	
    }
    
    public function actionNetworkList()
    {
    	$request = Yii::$app->request;
    	$listNetwork = array();
    
     	$networkList = RB_NETWORK::find()->all();
    
    	foreach ($networkList as $key => $value)
    	{
    		array_push($listNetwork, 
                        array('RB_NETWORK_ID' => $value['RB_NETWORK_ID'],
    		                  'NETWORK_NAME' => $value['NETWORK_NAME'],
                              'CO_FIRST_NAME' => $value['CO_FIRST_NAME'],
                              'CO_LAST_NAME' => $value['CO_LAST_NAME'],
                              'CO_TEL' => $value['CO_TEL'])
                        );
    	}
    	 
    	return json_encode($listNetwork);
    	 
    }
  
}

