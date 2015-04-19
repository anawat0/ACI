<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Utils;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_MENU_MAIN;
use app\models\WA_MENU_SUB;

class ManmenusubController extends AppController
{
    public $title = 'WA001 จัดการเมนูย่อย';
    
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
    
    	//Parameter from another pages.
		$menu_main_id = $request->post('menumain'); $menu_main_id = (empty($menu_main_id)?$request->get('menumain'):$menu_main_id);
		$waMenuMain = WA_MENU_MAIN::find()->where(['WA_MENU_MAIN_ID' => $menu_main_id])->limit(1)->asArray()->one();
		
		//Make prepair breadcrumb.
		$breadcrumbs = array();
		$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
		$this->addBreadCrumb('WA001 จัดการเมนู', Url::to(['manmenumain/', 'rtparams'=>$rtparams]));
		$this->addBreadCrumb('จัดการเมนูย่อย');
		
		$params = array('title'=>$this->title, 'menumain'=>$menu_main_id, 'waMenuMain'=>$waMenuMain, 'rtparams'=>$rtparams);
		
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
		$isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
		$isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
		$filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);

		//Input parameter from jqGrid Form.
		$id = $request->post('id');
		$nameth = $request->post('nameth');
		$menulink = $request->post('menulink');
		$seq = $request->post('seq');
		$status = $request->post('status');
		
		//Parameter from another pages.
		$menu_main_id = $request->post('menumain'); $menu_main_id = (empty($menu_main_id)?$request->get('menumain'):$menu_main_id);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			
    			$offset = ($page-1)*$rows;
    			
    			$count = WA_MENU_SUB::find()->where('WA_MENU_MAIN='.$menu_main_id)->count();
    			$result = WA_MENU_SUB::find()->where('WA_MENU_MAIN='.$menu_main_id)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<count($result); $i++){
					$seq++;

					array_push($response->rows, array('id'=>$result[$i]['WA_MENU_SUB_ID'], 
													'cell'=>array($seq,
																	$result[$i]['MENU_SUB_NAME_TH'],
																	$result[$i]['MENU_LINK'],
																	$result[$i]['SEQ'],
																	$result[$i]['STATUS'],
																	'edit'
																	)));
				}
				
    			$result = json_encode($response);
    			
	    		break;
	    		
    		case 'edit' :
    			echo "Status 1 >>> ".$status."\n";
    			//To update an existing customer record
				$menuSub = WA_MENU_SUB::findOne($id);
				$menuSub->MENU_SUB_NAME_TH = $nameth;
				$menuSub->MENU_LINK = $menulink;
				$menuSub->STATUS = Utils::getStatus($status);
				$menuSub->SEQ = $seq;
				$menuSub->LAST_UPD_USER_ID = '1';
				$menuSub->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				echo "Status 2 >>> ".$status."\n";
				
				$response = new jqGridResponse();
				if ($menuSub->save()) {  
					$response->success(['id'=>$menuSub->WA_MENU_SUB_ID]);
				} else {
					$response->error($menuSub->getErrors());
				}

                $result = $response->response_encode();
    			break;
			case 'add':
				$menuSub = new WA_MENU_SUB();
				$menuSub->WA_MENU_SUB_ID = WA_MENU_SUB::getNewID();
				$menuSub->WA_MENU_MAIN = $menu_main_id;
				$menuSub->MENU_SUB_NAME_TH = $nameth;
				$menuSub->MENU_LINK = $menulink;
				$menuSub->STATUS = Utils::getStatus($status);
				$menuSub->SEQ = $seq;
				$menuSub->CREATE_USER_ID = '1';
				$menuSub->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$menuSub->LAST_UPD_USER_ID = '1';
				$menuSub->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
				
				$response = new jqGridResponse();
				if ($menuSub->save()) {  
					$response->success(['id'=>$menuSub->WA_MENU_SUB_ID]);
				} else {
					$response->error($menuSub->getErrors());
				}

                $result = $response->response_encode();
    			break;
			case 'del':
				try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    WA_MENU_SUB::deleteAll(['in', 'WA_MENU_SUB_ID', $arrId]);

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
