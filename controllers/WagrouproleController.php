<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_GROUP;
use app\models\WA_GROUP_ROLE;
use app\models\WA_MENU_SUB;

class WagrouproleController extends AppController
{
    public $title = 'WA002 สิทธิ์การใช้งาน ของกลุ่มผู้ใช้งาน';
    
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
        $backAction = ['wagroup/', 'rtparams'=>$rtparams];
        $this->addBreadCrumb ( 'WA002 จัดการกลุ่มผู้ใช้งาน', Url::to($backAction) );
		$this->addBreadCrumb('สิทธิ์การใช้งาน');
        
        //Parameter from another pages.
		$wa_group_id = $request->post('wagroup'); $wa_group_id = (empty($wa_group_id)?$request->get('wagroup'):$wa_group_id);
		$waGroup = WA_GROUP::find()->where(['WA_GROUP_ID' => $wa_group_id])->limit(1)->asArray()->one();
		
        return $this->render('index', array('wagroup'=>$wa_group_id, 'waGroup'=>$waGroup));
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
		$accessflag = $request->post('accessflag');
		$addflag = $request->post('addflag');
		$editflag = $request->post('editflag');
		$deleteflag = $request->post('deleteflag');
		
		//Parameter from another pages.
		$wa_group_id = $request->post('wagroup'); $wa_group_id = (empty($wa_group_id)?$request->get('wagroup'):$wa_group_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array();
    			$where_causes['WA_MENU_MAIN.STATUS'] = 'A';
    			if($isSearch){
    				//['type' => 1, 'status' => 2]
    				//TODO: add condition to $where_causes.
    				
    			}
    			
    			$query = new Query;
    			
    			$query->select('WA_MENU_SUB.*')
        		    ->from('WA_MENU_MAIN')
        		    ->innerJoin('WA_MENU_SUB', 'WA_MENU_MAIN.WA_MENU_MAIN_ID=WA_MENU_SUB.WA_MENU_MAIN')
        		    ->leftJoin('WA_GROUP_ROLE', 'WA_MENU_SUB.WA_MENU_SUB_ID=WA_GROUP_ROLE.WA_MENU_SUB_ID AND WA_GROUP_ROLE.WA_GROUP_ID='.$wa_group_id)
        		    ->where($where_causes);
        		$count = $query->count();
    			
    			$query = new Query;
        		$query->select('WA_MENU_SUB.WA_MENU_SUB_ID, WA_MENU_MAIN.MENU_MAIN_NAME_TH, WA_MENU_SUB.MENU_SUB_NAME_TH, WA_GROUP_ROLE.ACCESS_FLAG, WA_GROUP_ROLE.ADD_FLAG, WA_GROUP_ROLE.EDIT_FLAG, WA_GROUP_ROLE.DELETE_FLAG')
        		    ->from('WA_MENU_MAIN')
        		    ->innerJoin('WA_MENU_SUB', 'WA_MENU_MAIN.WA_MENU_MAIN_ID=WA_MENU_SUB.WA_MENU_MAIN')
        		    ->leftJoin('WA_GROUP_ROLE', 'WA_MENU_SUB.WA_MENU_SUB_ID=WA_GROUP_ROLE.WA_MENU_SUB_ID AND WA_GROUP_ROLE.WA_GROUP_ID='.$wa_group_id)
        		    ->where($where_causes)
        		    ->orderBy($sidx.' '.$sord)
        		    ->offset($offset)
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

					$access_flag = (empty($result[$i]['ADD_FLAG'])?'N':$result[$i]['ACCESS_FLAG']);
					$add_flag = (empty($result[$i]['EDIT_FLAG'])?'N':$result[$i]['ADD_FLAG']);
					$edit_flag = (empty($result[$i]['DELETE_FLAG'])?'N':$result[$i]['EDIT_FLAG']);
					$delete_flag = (empty($result[$i]['ACCESS_FLAG'])?'N':$result[$i]['DELETE_FLAG']);
					array_push($response->rows, array('id'=>$result[$i]['WA_MENU_SUB_ID'], 
													'cell'=>array($seq,
																	$result[$i]['MENU_MAIN_NAME_TH'],
																	$result[$i]['MENU_SUB_NAME_TH'],
																	$access_flag,
																	$add_flag,
																	$edit_flag,
																	$delete_flag,
                                                                    'edit'
																	)));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit':
    			$rs1 = WA_GROUP_ROLE::find()->where(['WA_GROUP_ID'=>$wa_group_id, 'WA_MENU_SUB_ID'=>$id])->asArray()->all();
    			
                $response = new jqGridResponse();
                // echo $accessflag;
    			if($accessflag=='N' && $addflag=='N' && $editflag=='N' && $deleteflag=='N'){
    				//TODO: Remove record.
    				if(count($rs1)>0){
    					$waGroupRole = WA_GROUP_ROLE::findOne($rs1[0]['WA_GROUP_ROLE_ID']);
    					if($waGroupRole->delete()){
							$response->success(['id'=>'']);
						}else{
							$response->error($waGroupRole->getErrors());
						}
    				}
    			}else{
    				//TODO: Update record.
				    if(count($rs1)<1){
				    	//Insert
				        $waGroupRole = new WA_GROUP_ROLE();
				        $waGroupRole->WA_GROUP_ROLE_ID = WA_GROUP_ROLE::getNewID();
				        $waGroupRole->WA_GROUP_ID = $wa_group_id;
				        $waGroupRole->WA_MENU_SUB_ID = $id;
				    	$waGroupRole->ACCESS_FLAG = $accessflag;
				    	$waGroupRole->ADD_FLAG = $addflag;
				    	$waGroupRole->EDIT_FLAG = $editflag;
				    	$waGroupRole->DELETE_FLAG = $deleteflag;
        				$waGroupRole->CREATE_USER_ID = '1';
        				$waGroupRole->CREATE_TIME = new \yii\db\Expression('SYSDATE'); 
        				$waGroupRole->LAST_UPD_USER_ID = '1';
        				$waGroupRole->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE'); 
				    }else{
				    	//Update
				    	$waGroupRole = WA_GROUP_ROLE::findOne($rs1[0]['WA_GROUP_ROLE_ID']);
				    	print_r($waGroupRole);
				    	$waGroupRole->ACCESS_FLAG = $accessflag;
				    	$waGroupRole->ADD_FLAG = $addflag;
				    	$waGroupRole->EDIT_FLAG = $editflag;
				    	$waGroupRole->DELETE_FLAG = $deleteflag;
        				$waGroupRole->LAST_UPD_USER_ID = '1';
        				$waGroupRole->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE'); 
				    }

                    if ($waGroupRole->save()) {  
                        $response->success(['id'=>$waGroupRole->WA_GROUP_ROLE_ID]);
                    } else {
                        $response->error($waGroupRole->getErrors());
                    }
    			}

                $result = $response->response_encode();
    			break;
			case 'add':
				$result = 'success';
				break;
			case 'del':
				$rs1 = WA_GROUP_ROLE::find()->where(['WA_GROUP_ID'=>$wa_group_id, 'WA_MENU_SUB_ID'=>$id])->asArray()->all();
				// to delete an existing customer record
				if(count($rs1)>0){
					$waGroupRole = WA_GROUP_ROLE::findOne($rs1[0]['WA_GROUP_ROLE_ID']);
					if($waGroupRole->delete()){
						$result = 'success';
					}else{
						$result = 'failed : '.print_r($waGroupRole->getErrors(),true);
					}
				}
				break;
    	}
    	
    	echo $result;

    }
}
