<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RB_PROJ_LAUN_RES;
use app\models\RB_PROJ;
use app\models\RB_PROJ_LAUN;
use app\models\RB_SUB_PROJ;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\web\Utils;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use yii\helpers\Json;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\base\Exception;
use yii\web\Authentication;


class RbProjLaunResController extends AppController
{
	public $title = 'RB003';
	public $enableCsrfValidation = false;
    public $imagePath = '/rbprojlaunres';
    public $firstOptionDDL = array('' => 'กรุณาเลือก');
	
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
        $budgetYear = $request->get('budget_year');
        $rbProjId = $request->get('rb_proj_id');
        $rbProjLaunId = $request->get('rb_proj_laun_id');
        $rbSubProjId = $request->get('rb_sub_proj_id');

        $waProvinces = WA_PROVINCE::find()->all();
        $rbProjLauns = RB_PROJ_LAUN::find()
                            ->where('BUDGET_YEAR = :BUDGET_YEAR AND STATUS = :STATUS',
                                    ['BUDGET_YEAR' => $budgetYear,
                                    'STATUS' => 'A'])
                            ->all();
        $arrProjId = BaseArrayHelper::getColumn($rbProjLauns, 'rbProj.RB_PROJ_ID');
        $rbSubProjs = RB_SUB_PROJ::find()
                            ->where(['IN', 'RB_PROJ_ID', $arrProjId])
                            ->all();

        $this->addBreadCrumb('RD003 บันทึกผลการดำเนินการประจำเดือน สนก.',
                            Url::to(['rbwork/', 'budget_year' => $budgetYear]));
    	$this->addBreadCrumb('ผลการดำเนินการประจำเดือน สนก.');

    	$params = array('rtparams' => $rtparams,
                        'budgetYear' => $budgetYear,
                        'firstOptionDDL' => $this->firstOptionDDL,
                        'waProvinces' => $waProvinces,
                        'rbProjLauns' => $rbProjLauns,
                        'rbSubProjs' => $rbSubProjs,
                        'rbProjId' => $rbProjId,
                        'rbProjLaunId' => $rbProjLaunId,
                        'rbSubProjId' => $rbSubProjId);

        return $this->render('index', $params);
    }
    
    public function actionAdd()
    {
    	$request = Yii::$app->request;

        $id = $request->post('id'); $id = (empty($id)?$request->get('id'):$id);

        // Parameters go back
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	$budget_year = $request->get('budget_year');    	
    	$month = $request->get('month');
    	$province_code = $request->get('province_code');
    	$amphoe_code = $request->get('amphoe_code');
        $rb_proj_id = $request->get('rb_proj_id');
        $rb_proj_laun_id = $request->get('rb_proj_laun_id');
        $rb_sub_proj_id = $request->get('rb_sub_proj_id');
        
        // Set back parameters 
    	$tmp_params['budget_year'] = $budget_year;    	
    	$tmp_params['month'] = $month;
    	$tmp_params['province_code'] = $province_code;
    	$tmp_params['amphoe_code'] = $amphoe_code;
        $tmp_params['rb_proj_id'] = $rb_proj_id;
    	$tmp_params['rb_proj_laun_id'] = $rb_proj_laun_id;
    	$tmp_params['rb_sub_proj_id'] = $rb_sub_proj_id;
        
    	$backAction = ['rbprojlaunres/', 'rtparams' => $rtparams];
    	$backAction = BaseArrayHelper::merge($backAction, $tmp_params);
    	
    	$this->addBreadCrumb('RD003 บันทึกผลการดำเนินการประจำเดือน สนก.',
                            Url::to(['rbwork/', 'budget_year' => $budget_year]));
        $this->addBreadCrumb('ผลการดำเนินกำรประจำเดือน สนก.', Url::to($backAction));
        $this->addBreadCrumb('บันทึก ผลการดำเนินการประจำเดือน สนก.');
    	
    	$params = $tmp_params;
    	$params['backAction'] = $backAction;
    	$params['rbprojlaunres'] = array();
		$params['id']=$id;
    	if(!empty($id)){
    		$params['rbprojlaunres'] = RB_PROJ_LAUN_RES::findOne($id);
    	}else{
    		$params['rbprojlaunres'] = new RB_PROJ_LAUN_RES();
    	}

    	$params['rtparams']=$rtparams;
        $params['firstOptionDDL'] = $this->firstOptionDDL;
        $rbProjLauns = RB_PROJ_LAUN::find()
                            ->where('BUDGET_YEAR = :BUDGET_YEAR AND STATUS = :STATUS',
                                    ['BUDGET_YEAR' => $budget_year,
                                    'STATUS' => 'A'])
                            ->all();
        //$arrProjId = BaseArrayHelper::getColumn($rbProjLauns, 'rbProj.RB_PROJ_ID');
        $rbSubProjs = $this->getSubProjList($rb_proj_laun_id);

    	$params['rbProjLauns'] = $rbProjLauns;
        $params['rbSubProjs'] = $rbSubProjs;

    	return $this->render('add', $params);
    }

    public function actionSummary()
    {
        $rtparams = '';
        $waProvinces = WA_PROVINCE::find()->all();
        
        $this->addBreadCrumb('RD004 สรุปรายงานผลการดาเนินการ สนก.');

        $params = array('rtparams' => $rtparams,
                        'firstOptionDDL' => $this->firstOptionDDL,
                        'waProvinces' => $waProvinces);
        
        return $this->render('summary', $params);
    }

    public function actionSave()
    {
    	try {
            $id = '';
            $user_info = Authentication::getAuthenInfo();
	    	$user_id = $user_info->EMAIL;//Yii::$app->user->identity->WA_USER_ID;
	    	
	    	$request = Yii::$app->request;
	    	$mode = $request->post('mode'); $mode = (empty($mode)?$request->get('mode'):$mode);
			$hidden_return = $request->post('return');

			foreach($hidden_return as $key=>$value){
				$backAction[$key]=$value;
			}
			
	    	$input_npactivity = $request->post('RB_PROJ_LAUN_RES');
	    	$npactivity;
	    	if ($mode=='edit') {
	    		$id = $input_npactivity['RB_PROJ_LAUN_RES_ID'];
	    		$npactivity = RB_PROJ_LAUN_RES::findOne($id);
	    	} elseif ($mode=='add') {
	    		$npactivity = new RB_PROJ_LAUN_RES();
	    		$year = $input_npactivity['BUDGET_YEAR'];
	    		$npactivity->RB_PROJ_LAUN_RES_ID = RB_PROJ_LAUN_RES::getNewID();
	    		$npactivity->IMAGE_PATH = Utils::uploadFile(['image_path' => $this->imagePath, 
                                                            'file_name' => 'IMAGE_FILE', 
                                                            'id' => $npactivity->RB_PROJ_LAUN_RES_ID,
                											'year' => $year]);//$this->uploadFile('IMAGE_FILE', $npactivity->RB_PROJ_LAUN_RES_ID);
	    		$npactivity->IMAGE_PATH_1 = Utils::uploadFile(['image_path' => $this->imagePath,
	    				'file_name' => 'IMAGE_PATH_1',
	    				'id' => $npactivity->RB_PROJ_LAUN_RES_ID.'-1',
	    				'year' => $year]);
	    		$npactivity->IMAGE_PATH_2 = Utils::uploadFile(['image_path' => $this->imagePath,
	    				'file_name' => 'IMAGE_PATH_2',
	    				'id' => $npactivity->RB_PROJ_LAUN_RES_ID.'-2',
	    				'year' => $year]);
	    		$npactivity->IMAGE_PATH_3 = Utils::uploadFile(['image_path' => $this->imagePath,
	    				'file_name' => 'IMAGE_PATH_3',
	    				'id' => $npactivity->RB_PROJ_LAUN_RES_ID.'-3',
	    				'year' => $year]);
	    		$npactivity->IMAGE_PATH_4 = Utils::uploadFile(['image_path' => $this->imagePath,
	    				'file_name' => 'IMAGE_PATH_4',
	    				'id' => $npactivity->RB_PROJ_LAUN_RES_ID.'-4',
	    				'year' => $year]);
	    		$npactivity->STATUS = 'A';
	    		$npactivity->CREATE_USER_ID = $user_id;
	    		$npactivity->CREATE_TIME = new \yii\db\Expression('SYSDATE');
	    	}else{
	    
	    	}
	
	    	foreach ($input_npactivity as $key=>$value) {
	    		switch(strtoupper($key)){
	    			case 'RB_PROJ_LAUN_RES_ID':
	    				continue 2;
	    				break;
	    			case 'FROM_DATE':
	                    $value = Utils::toDate($value);
	                    break;
	    	        case 'TO_DATE':
	    	         	$value = Utils::toDate($value);
	        	        break;
	        	        default :
	    		}
	    		 
	    		$npactivity->$key = $value;
	    	}
	    	$npactivity->LAST_UPD_USER_ID = $user_id;
	    	$npactivity->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
	    
	    	if($npactivity->save()){  // equivalent to $customer->insert();
	    		$backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
	    		$this->redirect(Url::to($backAction));
	    	}else{
	    		
	    		throw new Exception('failed : '.print_r($npactivity->getErrors(),true));
	
	    	}
    	}catch (Exception $e){
    		$backAction[0] = 'rbprojlaunres/add';
    		$backAction['id'] = $id;
    		$backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');//base64_encode($e);
    		
            Yii::trace(print_r($e, true), 'debug');
            $this->redirect(Url::to($backAction));
    	}
    
    }
    
    private function uploadFile($files_name, $id){
    	$directory = $this->imagePath;
    	
    	$fileName = $_FILES[$files_name]["name"];
    	
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.date('-Ymd-His').$extensionFile;
    	
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    	
    	$response = new jqGridResponse();
    	
    	if ( Utils::checkDirectory($directory) ) {
    	
    		if ( move_uploaded_file($_FILES[$files_name]["tmp_name"]	// temp_file
    				, $directory."/".$fileName) ) {	// path file
    				
    			//Successssssssssss.
    			
    		} else {
    			throw new Exception('Can\'t save image');
    		}
    			
    	} else {
    		throw new Exception('Can\'t create directory for save image');
    	}
    	return $fileName;
    }
    
    public function actionUploadfile() { //Edit mode.
    	$request = Yii::$app->request;
    
    	$fileName = $_FILES["fileToUpload"]["name"];
    	$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
        $image_field = $request->post('image_field');
        $year = $request->post('year');
    	 
    	$directory = 'images'.$this->imagePath.'/'.$year;
    
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.date('-Ymd-His').$extensionFile;
    
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    
    	$response = new jqGridResponse();
    
    	if ( Utils::checkDirectory($directory) ) {
    	//print_r($_FILES["fileToUpload"]);
    		if ( move_uploaded_file($_FILES["fileToUpload"]["tmp_name"]	// temp_file
    				, $directory."/".$fileName) ) {	// path file
    				
    			$npactivity = RB_PROJ_LAUN_RES::findOne($id);
    			$npactivity->$image_field = $this->imagePath.'/'.$year.'/'.$fileName;
    			if($npactivity->save()){
    				$response->success(['imagePath'=>$directory."/".$fileName]);
    			}else{
    				$response->error('DB Save failed. '+print_r($npactivity->getErrors(), true)); //3
    			}
    		} else {
    			$response->error('Can\'t save image'); //1
    		}
    			
    	} else {
    		$response->error('Can\'t Create folder'); //2
    	}
    	echo $response->response_encode();
    }
    
 	public function actionGridview()
    {
    	$user_info = Authentication::getAuthenInfo();
	    $user_id = $user_info->EMAIL;
    	$request = Yii::$app->request;
    	$workId = $request->post('w_id');

		//Parameter from jqGrid
		$oper = $request->post('oper', ''); $oper = (empty($oper)?$request->get('oper', ''):$oper);
		$page = $request->post('page', ''); $page = (empty($page)?$request->get('page', ''):$page);
		$rows = $request->post('rows', ''); $rows = (empty($rows)?$request->get('rows', ''):$rows);
		$sidx = $request->post('sidx', ''); $sidx = (empty($sidx)?$request->get('sidx', ''):$sidx);
		$w_id = $request->post('w_id', ''); $w_id = (empty($sord)?$request->get('w_id', ''):$w_id);
		$sord = $request->post('sord', ''); $sord = (empty($sord)?$request->get('sord', ''):$sord);
		$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
		$isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
		$isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
		$filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);

		//Input parameter from jqGrid Form.
		//

		//Parameter from another pages.
		//

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
    				    switch($conditions->field){
    				        case 'START_DATE':
    				            array_push($where_causes, $conditions->field." BETWEEN '01-JAN-".$conditions->data."' AND '31-DEC-".$conditions->data."'" );
    				            break;
    				        default:
    				            array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				            break;
    				    }
    				}
    			}
    			//print_r($where_causes);
    			
    			$count = RB_PROJ_LAUN_RES::find()->where($where_causes)->count();
                $result = RB_PROJ_LAUN_RES::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<count($result); $i++) {
                    $seq++;

                    $sumPeople = intval($result[$i]['PEOPLE_MALE']) 
                                + intval($result[$i]['PEOPLE_FEMALE'])
                                + intval($result[$i]['YOUTH_MALE']) 
                                + intval($result[$i]['YOUTH_FEMALE']);
                    $opFlag = RB_PROJ_LAUN_RES::getOpFlagText($result[$i]['OP_FLAG']);

					array_push($response->rows, array('id'=>$result[$i]['RB_PROJ_LAUN_RES_ID'], 
													'cell'=>array($seq,
																	$result[$i]['ACT_NAME_TH'],
																	$result[$i]->waProvince['PROVINCE_NAME_TH'],
																	$result[$i]->waAmphoe['AMPHOE_NAME_TH'],
																	$result[$i]['PLACE'],
																	$result[$i]['ORG_RESPON_NAME'],
																	$result[$i]['BUDGET'],
																	$sumPeople,
																	$opFlag,
                                                                    $result[$i]['BUDGET_YEAR'],
                                                                    $result[$i]['MONTH'],
                                                                    $result[$i]['STATUS'],
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

                    RB_PROJ_LAUN_RES::deleteAll(['in', 'RB_PROJ_LAUN_RES_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;
    }

    public function actionGridViewForSummary()
    {
        $user_info = Authentication::getAuthenInfo();
	    $user_id = $user_info->EMAIL;
        $request = Yii::$app->request;
        $workId = $request->post('w_id');

        //Parameter from jqGrid
        $oper = $request->post('oper', ''); $oper = (empty($oper)?$request->get('oper', ''):$oper);
        $page = $request->post('page', ''); $page = (empty($page)?$request->get('page', ''):$page);
        $rows = $request->post('rows', ''); $rows = (empty($rows)?$request->get('rows', ''):$rows);
        $sidx = $request->post('sidx', ''); $sidx = (empty($sidx)?$request->get('sidx', ''):$sidx);
        $w_id = $request->post('w_id', ''); $w_id = (empty($sord)?$request->get('w_id', ''):$w_id);
        $sord = $request->post('sord', ''); $sord = (empty($sord)?$request->get('sord', ''):$sord);
        $id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
        $isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
        $isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
        $filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);

        //Input parameter from jqGrid Form.
        //

        //Parameter from another pages.
        //

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
                        switch($conditions->field){
                            case 'START_DATE':
                                array_push($where_causes, $conditions->field." BETWEEN '01-JAN-".$conditions->data."' AND '31-DEC-".$conditions->data."'" );
                                break;
                            default:
                                array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
                                break;
                        }
                    }
                }
                //print_r($where_causes);
                
                $count = RB_PROJ_LAUN_RES::find()->where($where_causes)->count();
                $result = RB_PROJ_LAUN_RES::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
                $countResult = count($result);
                $seq = $rows * ($page - 1);

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;

                $months = Utils::getArrMonth();

                for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    $month = $months[$result[$i]['MONTH']];
                    $rbProj = RB_PROJ::findOne($result[$i]['RB_PROJ_LAUN_ID']);

                    array_push($response->rows, array('id'=>$result[$i]['RB_PROJ_LAUN_RES_ID'], 
                                                    'cell'=>array($seq,
                                                                    $month,
                                                                    $result[$i]->waProvince['PROVINCE_NAME_TH'],
                                                                    $result[$i]->waAmphoe['AMPHOE_NAME_TH'],
                                                                    $rbProj['PROJ_NAME_TH'],
                                                                    $result[$i]->rbSubProj['SUB_PROJ_NAME_TH'],
                                                                    $result[$i]['STATUS'],
                                                                    $result[$i]['BUDGET_YEAR'],
                                                                    $result[$i]['MONTH'],
                                                                   )));
                }
                $result = json_encode($response);
                break;
            case 'edit':
                break;
            case 'add':
                break;
            case 'del':
                break;
        }
        
        echo $result;
    }

    public function actionAjaxGetSubProjList()
    {
        $request = Yii::$app->request;

        $result = array();

        $rbProjLaunId = $request->get('rb_proj_laun_id');

        $result = $this->getSubProjList($rbProjLaunId);

        echo Json::encode($result);
    }

    public function actionAjaxUpdateStatus()
    {
        try {
            $result = array();

            $request = Yii::$app->request;
            
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $rbProjLaunResId = $request->post('rb_proj_laun_res_id');
            $status = $request->post('status');
            if ($status == 'A') {
                $status = 'S';
            } else if ($status == 'S') {
                $status = 'C';
            } else {
                $status = 'S';
            }

            $rbProjLaunRes = RB_PROJ_LAUN_RES::findOne($rbProjLaunResId);
            $rbProjLaunRes->STATUS = $status;
            $rbProjLaunRes->LAST_UPD_USER_ID = $user_id;
            $rbProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($rbProjLaunRes->save()) {
                $result['msg'] = 'อัพเดทสถานะสำเร็จ';
                $result['status_update'] = $rbProjLaunRes->STATUS;
            } else {
                throw new Exception(print_r($rbProjLaunRes->getErrors(), true));
            }
        } catch (Exception $e) {
            Yii::trace($e->getMessage(), 'debug');

            $result['msg'] = 'อัพเดทสถานะล้มเหลว';
            $result['status_update'] = '';
        }

        echo Json::encode($result);
    }
    
    private function getSubProjList($rbProjLaunId) 
    {
        $subProjList = [];

        $rbProjLaun = RB_PROJ_LAUN::findOne($rbProjLaunId);
        if ($rbProjLaun) {
            $arrProjId = array($rbProjLaun->rbProj['RB_PROJ_ID']);

            $subProjList = RB_SUB_PROJ::find()
                                        ->where(['IN', 'RB_PROJ_ID', $arrProjId])
                                        ->asArray()
                                        ->all();
        }

        return $subProjList;
    }
}
