<?php

namespace app\controllers;

use Yii;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\UploadedFile;
use yii\web\Utils;
use yii\Base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SB_PROJ;
use app\models\SB_PROJ_LAUN;
use app\models\SB_PROJ_LAUN_RES;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbProjLaunResController extends AppController
{
	public $title = 'SB003 บันทึกผลการดำเนินงานประจำเดือน';
	public $enableCsrfValidation = false;

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
    	$this->addBreadCrumb($this->title);

        $request = Yii::$app->request;

        $budgetYear = $request->get('budget_year');
        $budgetYear = empty($budgetYear)? intval(date('Y')) + 543: $budgetYear;
        $sbProjLauns = $this->getSbProjLaun($budgetYear);

        $params = ['budgetYear' => $budgetYear,
                    'sbProjLauns' => $sbProjLauns];
        return $this->render('index', $params);
    }

    public function actionList() 
    {
    	$request = Yii::$app->request;

    	// Parameters
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
        $budget_year = $request->get('budget_year');
        $sbProjLaunId = $request->get('sb_proj_laun_id');

        $sbProjLauns = $this->getSbProjLaun($budget_year);
        $tmp_params['budget_year'] = $budget_year;
        $backAction = ['sbprojlaunres/'];
        $backAction = ArrayHelper::merge($backAction, $tmp_params);
        
        $this->addBreadCrumb($this->title, Url::to($backAction));
        $this->addBreadCrumb('รายการผลการดำเนินการประจำเดือน');
        
        $params = array('budget_year'=> $budget_year,
                        'sbProjLaunId' => $sbProjLaunId,
                        'sbProjLauns' => $sbProjLauns,
                        'rtparams' => $rtparams);
    	
    	return $this->render('list', $params);
    }

    public function actionForm()
    {
    	$request = Yii::$app->request;

    	$budget_year = $request->post('budget_year'); $budget_year = (empty($budget_year)?$request->get('budget_year'):$budget_year);
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
    	$id = $request->post('id'); $id = (empty($id)? $request->get('id'): $id);
    	
    	$sbProjLauns = SB_PROJ_LAUN::find()->innerJoinWith('sbProj')->orderBy('SB_PROJ_LAUN.SB_PROJ_LAUN_ID')->all();
    	$waProvinces = WA_PROVINCE::find()->all();
    	$arrPlaseSelect = ['' => 'กรุณาเลือก'];
    	
    	$month = $request->get('month');
    	$province = $request->get('province');
    	$launch_id = $request->get('launch_id');

    	$tmp_params['budget_year'] = $budget_year;
    	
    	$tmp_params['month'] = $month;
    	$tmp_params['province'] = $province;
    	$tmp_params['launch_id'] = $launch_id;
    	
    	$backAction = ['sbprojlaunres/', 'budget_year'=>$budget_year];
    	
    	$backAction2 = ['sbprojlaunres/list', 'rtparams'=>$rtparams];
    	$backAction2 = ArrayHelper::merge($backAction2, $tmp_params);
    	
    	$this->addBreadCrumb('SB003 บันทึกผลการดำเนินการประจำเดือน', Url::to($backAction));
    	$this->addBreadCrumb('รายการผลการดำเนินการประจำเดือน', Url::to($backAction2));
    	$this->addBreadCrumb('บันทึกผลการดำเนินการ');
    	
    	if ( empty($id) )
    	{
    		$model = new SB_PROJ_LAUN_RES();
    		$waAmphoes = array();
    	}
    	else
    	{
    		$model = SB_PROJ_LAUN_RES::findOne($id);
    		$waAmphoes = WA_AMPHOE::findAll(['PROVINCE_CODE' => $model['PROVINCE_CODE']]);
    	}

    	//$params = array('model' => $model, 'sbProjLauns' => $sbProjLauns, 'arrPlaseSelect' => $arrPlaseSelect, 'waProvinces' => $waProvinces, 'waAmphoes' => $waAmphoes);
    	$params = $tmp_params;
    	$params['backAction'] = $backAction2;
    	$params['model'] = $model;
    	$params['sbProjLauns'] = $sbProjLauns;
    	$params['arrPlaseSelect'] = $arrPlaseSelect;
    	$params['waProvinces'] = $waProvinces;
    	$params['waAmphoes'] = $waAmphoes;
    	
    	return $this->render('form', $params);
    }

    public function actionSaveNew()
    {
    	try {
    		$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

	    	$sbProjLaunRes = new SB_PROJ_LAUN_RES();
	    	$sbProjLaunRes->load($request->post());
	    	$sbProjLaunRes->SB_PROJ_LAUN_RES_ID = SB_PROJ_LAUN_RES::getNewID();
	    	$sbProjLaunRes->TARGET_FROM = Utils::toDate($sbProjLaunRes->TARGET_FROM);
	    	$sbProjLaunRes->TARGET_TO = Utils::toDate($sbProjLaunRes->TARGET_TO);
	    	$sbProjLaunRes->ACTUAL_FROM = Utils::toDate($sbProjLaunRes->ACTUAL_FROM);
	    	$sbProjLaunRes->ACTUAL_TO = Utils::toDate($sbProjLaunRes->ACTUAL_TO);
	    	$sbProjLaunRes->STATUS = 'A';
	    	$sbProjLaunRes->CREATE_USER_ID = $user_id;
			$sbProjLaunRes->CREATE_TIME = new \yii\db\Expression('SYSDATE');
			$sbProjLaunRes->LAST_UPD_USER_ID = $user_id;
			$sbProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

			if ($sbProjLaunRes->save()) {
    			Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
                
                return $this->redirect(Url::to(['sbprojlaunres/list', 
                                                'budget_year' => $sbProjLaunRes->BUDGET_YEAR,
                                                'sb_proj_laun_id' => $sbProjLaunRes->SB_PROJECT_LAUNCH_ID]));
            } else {
                Utils::setErrorFlashMessage($sbProjLaunRes->getErrors());
                return $this->redirect(Url::to(['sbprojlaunres/form']));
            }
    	} catch (Exception $e) {
            Yii::trace(print_r($e->getMessage(), true), 'debug');
    		Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['sbprojlaunres/form']));
    	}
    }

    public function actionEdit() {
    	try {
    		$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

	    	$sbProjLaunRes = SB_PROJ_LAUN_RES::findOne($request->post('SB_PROJ_LAUN_RES')['SB_PROJ_LAUN_RES_ID']);
	    	$sbProjLaunRes->load($request->post());
	    	$sbProjLaunRes->TARGET_FROM = Utils::toDate($sbProjLaunRes->TARGET_FROM);
	    	$sbProjLaunRes->TARGET_TO = Utils::toDate($sbProjLaunRes->TARGET_TO);
	    	$sbProjLaunRes->ACTUAL_FROM = Utils::toDate($sbProjLaunRes->ACTUAL_FROM);
	    	$sbProjLaunRes->ACTUAL_TO = Utils::toDate($sbProjLaunRes->ACTUAL_TO);
	    	$sbProjLaunRes->STATUS = 'A';
			$sbProjLaunRes->LAST_UPD_USER_ID = $user_id;
			$sbProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

			if ($sbProjLaunRes->save()) {
				Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
                
                return $this->redirect(Url::to(['sbprojlaunres/list', 
                                                'budget_year' => $sbProjLaunRes->BUDGET_YEAR,
                                                'sb_proj_laun_id' => $sbProjLaunRes->SB_PROJECT_LAUNCH_ID]));
			} else {
				Utils::setErrorFlashMessage($sbProjLaunRes->getErrors());
                return $this->redirect(Url::to(['sbprojlaunres/form']));
			}
    	} catch (Exception $e) {
    		Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['sbprojlaunres/form']));
    	}
    }

    public function actionUploadFile() {
        try {
            $resultMsg = '';
            $resultPathFile = '';

        	$request = Yii::$app->request;
            $connection = Yii::$app->db;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $sb_proj_laun_res_id = $request->post('sb_proj_laun_res_id');
            $sbProjLaunRes = SB_PROJ_LAUN_RES::findOne($sb_proj_laun_res_id);

            if ( $sbProjLaunRes ) {
                // file upload
                $image = UploadedFile::getInstanceByName('IMAGE_PATH');

                // file name
                $splitFileName = explode('.', $image->name);
                $fileExtension = '.'.$splitFileName[count($splitFileName) - 1];
                $fileName = $sbProjLaunRes->SB_PROJ_LAUN_RES_ID.$fileExtension;

                // Ref. http://stackoverflow.com/questions/23155428/how-to-get-root-directory-in-yii2
                $physicalPath = Yii::getAlias('@webroot').'/'.$this->id.'/';
                $urlPath = Yii::getAlias('@web').'/'.$this->id.'/';

                if ( Utils::checkDirectory($physicalPath) )  {
                    $transaction = $connection->beginTransaction(); 
                    $sbProjLaunRes->IMAGE_PATH = $urlPath.$fileName;
                    $sbProjLaunRes->LAST_UPD_USER_ID = $user_id;
                    $sbProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

                    if ( $image->saveAs($physicalPath.$fileName) && $sbProjLaunRes->save() ) {
                        $transaction->commit();
                        $resultMsg = 'บันทึกข้อมูลสำเร็จ';
                        $resultPathFile = $urlPath.$fileName;
                    } else {
                        $transaction->rollback();
                        Yii::error('Upload file or Save data fiald.', 'debuger');
                        $resultMsg = 'เกิดข้อผิดพลาด';
                    }
                } else {
                    $resultMsg = 'เกิดข้อผิดพลาด';
                }

                //BaseYii::trace($physicalPath.$fileName\, 'debuger');
            } else {
                Yii::error('sb_proj_laun_res_id invalid.', 'debuger');
                $resultMsg = 'เกิดข้อผิดพลาด';
            }
        } catch (Exception $e) {
            Yii::error($e->getErrors(), 'debuger');
            $resultMsg = 'เกิดข้อผิดพลาด';
           
        } finally {
            echo  '{"msg":"'.$resultMsg .'", "file_path":"'.$resultPathFile.'"}';
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
		$sbProjId = $request->post('sb_proj_id');
		$sbSubProjId = $request->post('sb_sub_proj_id');
		$budgetYear = $request->post('budget_year');
		$projectResponsible = $request->post('project_responsible');
		$budget = $request->post('budget');
		$status = $request->post('status');

		// Parameters
    	$budget_year = $request->post('budget_year'); $budget_year = (empty($budget_year)? $request->get('budget_year'): $budget_year);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			
    			
    			$where_causes = array();
    			$where = 'and';
    			if($isSearch)
    			{
    				$filters = json_decode($filters);
    				//print_r($filters);
    				if ( !empty($filters->rules) )
    				{
    					array_push($where_causes, $filters->groupOp);
	    				foreach($filters->rules as $conditions)
	    				{
	    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
	    				}
    				}
    				
    			}

    			$count = SB_PROJ_LAUN_RES::find()->where($where_causes)->count();
    			$result = SB_PROJ_LAUN_RES::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);
                $months = Utils::getArrMonth(false); 

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;

					$sbProj = SB_PROJ::findOne($result[$i]->sbProjLaun['SB_PROJ_ID']);
                    $monthStr = $months[$result[$i]['MONTH']];

					array_push($response->rows, array('id'=>$result[$i]['SB_PROJ_LAUN_RES_ID'], 
													'cell'=>array($seq,
																	$sbProj['PROJ_NAME_TH'],
																	$monthStr,
																	$result[$i]->waProvince['PROVINCE_NAME_TH'],
																	$result[$i]->waAmphoe['AMPHOE_NAME_TH'],
																	$result[$i]['TARGET_BUDGET'],
																	$result[$i]['TARGET_SATISFACTION'],
																	$result[$i]['CREATE_USER_ID'],
																	$result[$i]['CREATE_TIME'],
																	$result[$i]['LAST_UPD_USER_ID'],
																	$result[$i]['LAST_UPD_TIME']
																	)));
				}

    			$result = json_encode($response);
	    		break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    SB_PROJ_LAUN_RES::deleteAll(['SB_PROJ_LAUN_RES_ID' => $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;
    }

    public function actionAjaxGetSbProjLaun() 
    {
        $request = Yii::$app->request;

        $budgetYear = $request->get('budget_year');

        $sbProjLauns = $this->getSbProjLaun($budgetYear);

        echo Json::encode(ArrayHelper::map($sbProjLauns, 
                                            'SB_PROJ_LAUN_ID', 
                                            'sbProj.PROJ_NAME_TH'));
    }

    private function getSbProjLaun($budgetYear) 
    {
        $sbProjLauns = SB_PROJ_LAUN::find()
                        ->where('BUDGET_YEAR = :budgetYear', [':budgetYear' => $budgetYear])
                        ->all();

        return $sbProjLauns;
    }

}
