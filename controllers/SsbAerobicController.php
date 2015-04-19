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
use app\models\SSB_AEROBIC;

class SsbAerobicController extends AppController
{
	public $title = 'SSB004 XXXX';
	public $enableCsrfValidation = false;
    public $imagePath = 'images/ssbclublaun';
                
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
        $this->addBreadCrumb('SSB004 บันทึกรายงานประจำเดือน รวมพลคนรักแอโรบิก');
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
		$actnameth = $request->post('actnameth');
		$provincecode = $request->post('provincecode');             
        $year = $request->post('year');
        $month = $request->post('month');
        $agemale0 = $request->post('agemale0');
		$agefemale0 = $request->post('agefemale0');
		$agemale1 = $request->post('agemale1');
		$agefemale1 = $request->post('agefemale1');
		$agemale2 = $request->post('agemale2');
		$agefemale2 = $request->post('agefemale2');
		$agemale3 = $request->post('agemale3');
		$agefemale3 = $request->post('agefemale3');
        $imagepath = $request->post('imagepath');      
                
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
    			
    			$count = SSB_AEROBIC::find()->where($where_causes)->count();
    			$result = SSB_AEROBIC::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);
                $monts = Utils::getArrMonth();

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['SSB_AEROBIC_ID'], 
													'cell'=>array($seq,
																	$result[$i]['ACT_NAME_TH'],
																	$result[$i]->waProvince['PROVINCE_NAME_TH'],																	
																	$result[$i]['YEAR'],
                                                                    $monts[$result[$i]['MONTH']],
                                                                    $result[$i]['AGE_MALE_0'],
																	$result[$i]['AGE_FEMALE_0'],
																	$result[$i]['AGE_MALE_1'],
																	$result[$i]['AGE_FEMALE_1'],
																	$result[$i]['AGE_MALE_2'],
																	$result[$i]['AGE_FEMALE_2'],
																	$result[$i]['AGE_MALE_3'],
																	$result[$i]['AGE_FEMALE_3'],
																	$this->imagePath.'/'.$result[$i]['IMAGE_PATH'],
                                                                    $result[$i]['MONTH'],
                                                                    $result[$i]['YEAR'],
                                                                    $result[$i]['PROVINCE_CODE'],
                                                                    'edit'
																	)));
				}
				
    			$result = json_encode($response);
	    		break;
    		case 'edit' :
                try {
                    $ssbAerobic = SSB_AEROBIC::findOne($id);
                    $ssbAerobic->ACT_NAME_TH = $actnameth;
                    $ssbAerobic->PROVINCE_CODE = $provincecode;
                    $ssbAerobic->YEAR = $year;
                    $ssbAerobic->MONTH = $month;
                    $ssbAerobic->AGE_MALE_0 = $agemale0;
                    $ssbAerobic->AGE_FEMALE_0 = $agefemale0;
                    $ssbAerobic->AGE_MALE_1 = $agemale1;
                    $ssbAerobic->AGE_FEMALE_1 = $agefemale1;
                    $ssbAerobic->AGE_MALE_2 = $agemale2;
                    $ssbAerobic->AGE_FEMALE_2 = $agefemale2;
                    $ssbAerobic->AGE_MALE_3 = $agemale3;
                    $ssbAerobic->AGE_FEMALE_3 = $agefemale3;
                    $ssbAerobic->LAST_UPD_USER_ID = $user_id;
                    $ssbAerobic->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

                    $response = new jqGridResponse();

                    if($ssbAerobic->save()){  // equivalent to $customer->insert();
                        $response->success(['id'=>$ssbAerobic->SSB_AEROBIC_ID]);
                    }else{
                        $response->error($ssbAerobic->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
			case 'add':
                try {
    				$ssbAerobic = new SSB_AEROBIC();
    				$ssbAerobic->SSB_AEROBIC_ID = SSB_AEROBIC::getNewID();		
    				$ssbAerobic->ACT_NAME_TH = $actnameth;
    				$ssbAerobic->PROVINCE_CODE = $provincecode;				
    				$ssbAerobic->YEAR = $year;
                    $ssbAerobic->MONTH = $month;
                    $ssbAerobic->AGE_MALE_0 = $agemale0;
    				$ssbAerobic->AGE_FEMALE_0 = $agefemale0;
    				$ssbAerobic->AGE_MALE_1 = $agemale1;
    				$ssbAerobic->AGE_FEMALE_1 = $agefemale1;
    				$ssbAerobic->AGE_MALE_2 = $agemale2;
    				$ssbAerobic->AGE_FEMALE_2 = $agefemale2;
    				$ssbAerobic->AGE_MALE_3 = $agemale3;
    				$ssbAerobic->AGE_FEMALE_3 = $agefemale3;
    				$ssbAerobic->IMAGE_PATH = 'no.jpg';
    				$ssbAerobic->STATUS = 'A';
    				$ssbAerobic->CREATE_USER_ID = $user_id;
    				$ssbAerobic->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$ssbAerobic->LAST_UPD_USER_ID = $user_id;
    				$ssbAerobic->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
    				
    				$response = new jqGridResponse();
                    
    				if($ssbAerobic->save()){  // equivalent to $customer->insert();
    					$response->success(['id'=>$ssbAerobic->SSB_AEROBIC_ID]);
    				}else{
    					$response->error($ssbAerobic->getErrors());
    				}
                } catch (Exception $e) {
                    Yii::trace(print_r($e, true), 'debug');
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

				$result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    SSB_AEROBIC::deleteAll(['in', 'SSB_AEROBIC_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;
    }
    
    public function actionUploadfile(){
    	 
    	$request = Yii::$app->request;
    	 
    	$directory = $this->imagePath;
    
    	$fileName = $_FILES["fileToUpload"]["name"];
    	$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
    
    	$splitFileName = explode(".", $fileName);
    	$extensionFile = ".".$splitFileName[count($splitFileName)-1];
    	$fileName = $id.date('-Ymd-His').$extensionFile;
    
    	Yii::trace('directory = '.$directory.'  fileName = '.$fileName);
    
    	$response = new jqGridResponse();
    
    	if ( Utils::checkDirectory($directory) ) {
    
    		if ( move_uploaded_file($_FILES["fileToUpload"]["tmp_name"]	// temp_file
    				, $directory."/".$fileName) ) {	// path file
    				
    			$ssbAerobic = SSB_AEROBIC::findOne($id);
    			$ssbAerobic->IMAGE_PATH = $fileName;
    			if($ssbAerobic->save()){
    				$response->success();
    			}else{
    				$response->error($ssbAerobic->getErrors()); //3
    			}
    		} else {
    			$response->error(); //1
    		}
    			
    	} else {
    		$response->error(); //2
    	}
    	echo $response->response_encode();
    }
}
