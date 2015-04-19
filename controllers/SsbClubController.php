<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Authentication;
use yii\web\AppController;
use yii\filters\VerbFilter;
use yii\web\Utils;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\SSB_CLUB;

class SsbClubController extends AppController
{
	public $title = 'SSB003 XXXX';
                
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
        $this->addBreadCrumb('SSB003 บันทึกข้อมูลชมรม');
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    public function actionDetail(){
    	
        $request = Yii::$app->request;
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);
        
        $this->addBreadCrumb('SSB003 บันทึกข้อมูลชมรม', Url::to(['ssbclub/', 'rtparams'=>$rtparams]));
        $this->addBreadCrumb('บันทึกข้อมูลชมรม');
    	
    	$params['ssbClub'] = array();
    	$request = Yii::$app->request;
    	
    	$params['id']=$id;
    	if(!empty($id)){
    		$params['ssbClub'] = SSB_CLUB::findOne($id);
    	}else{
    	    $params['ssbClub'] = new SSB_CLUB();
    	}
    	
        $params['rtparams']=$rtparams;
    	return $this->render('detail', $params);        
    	
    }    
    
    public function actionSave() 
    {
        try {    
        	$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

        	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
        	$input_ssbClub = $request->post('SSB_CLUB');
        	
        	if($mode=='edit'){
                    $id = $input_ssbClub['SSB_CLUB_ID'];
        		$ssbClub = SSB_CLUB::findOne($id);
        	}else if($mode=='add'){
        		$ssbClub = new SSB_CLUB();
    			$ssbClub->SSB_CLUB_ID = SSB_CLUB::getNewID();
        		$ssbClub->CREATE_USER_ID = $user_id;
        		$ssbClub->CREATE_TIME = new \yii\db\Expression('SYSDATE');
        	}
        	    	
        	foreach($input_ssbClub as $key=>$value){
                //Custom value before save to database.
        	    switch(strtoupper($key)){
        	        case 'SSB_CLUB_ID':
        	            continue 2;
        	            break;
        	        case 'STATUS':
        	            $value = ($value?'A':'C');
        	            break;
                    case 'ESTABLISH_DATE':
                        $value = Utils::toDate($value);
                        break;    	      
                    default :    	        
        	    }        	    
                $ssbClub->$key = $value;
        	}
           
            $ssbClub->LAST_UPD_USER_ID = $user_id;
            $ssbClub->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    		      
            if ($ssbClub->save()) {
                Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
                return $this->redirect(Url::to(['ssbclub/index']));
            } else {
                Utils::setErrorFlashMessage($ssbClub->getErrors());
                return $this->redirect(Url::to(['ssbclub/detail']));
            }
        } catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));
            return $this->redirect(Url::to(['ssbclub/detail']));
        }
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
		$clubnameth = $request->post('clubnameth');
		$leader = $request->post('leader');
		$tel = $request->post('tel');
		$amount = $request->post('amount');
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
    				    switch($conditions->field){
    				        case 'CLUB_NAME_TH':
    				            array_push($where_causes, $conditions->field." LIKE '%".$conditions->data."%'" );
    				            break;
    				        default:
            					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
            					break;
    				    }
    				}
    			}
    			
    			$count = SSB_CLUB::find()->where($where_causes)->count();
    			$result = SSB_CLUB::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['SSB_CLUB_ID'], 
													'cell'=>array($seq,
																	$result[$i]['CLUB_NAME_TH'],
																	$result[$i]['LEADER'],
																	$result[$i]['TEL'],
																	$result[$i]['AMOUNT'],
																	$result[$i]['STATUS']
																	)));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit':    			    			
    			break;
    		case 'add':			
    			break;
    		case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    SSB_CLUB::deleteAll(['SSB_CLUB_ID' => $arrId]);

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
