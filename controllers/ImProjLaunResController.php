<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\AppController;
use yii\web\Authentication;
use yii\web\UploadedFile;
use yii\web\Utils;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\IM_PROJ;
use app\models\IM_PROJ_LAUN;
use app\models\IM_PROJ_LAUN_RES;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class ImProjLaunResController extends AppController
{
	public $title = 'IM003 บันทึกผลการดำเนินงานประจำเดือน';
	public $enableCsrfValidation = false;
	public $firstOptionDDL = ['' => 'กรุณาเลือก'];

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
    	$currentYear = intval(date('Y')) + 543;
    	$imProjLauns = $this->getImProjLaun($currentYear);

        $this->addBreadCrumb('IM003 บันทึกผลการดำเนินการประจำเดือน สอม.');

    	$params = ['firstOptionDDL' => $this->firstOptionDDL,
    				'currentYear' => $currentYear,
    				'imProjLauns' => $imProjLauns];

        return $this->render('index', $params);
    }

    public function actionList()
    {
    	$request = Yii::$app->request;

    	// Parameters
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
    	$budgetYear = $request->get('budget_year');
        $month = $request->get('month');
        $imProjLaunId = $request->get('im_proj_laun_id');

        $imProjLauns = $this->getImProjLaun($budgetYear);
        
        $this->addBreadCrumb('IM003 บันทึกผลการดำเนินการประจำเดือน สอม.', Url::to(['improjlaunres/']));
        $this->addBreadCrumb('ผลการดำเนินงาน');
        
        $params = ['rtparams' => $rtparams, 
                    'budgetYear' => $budgetYear,
                    'month' => $month,
                    'imProjLaunId' => $imProjLaunId,
                    'firstOptionDDL' => $this->firstOptionDDL,
                    'imProjLauns' => $imProjLauns];
    	
    	return $this->render('list', $params);
    }

    public function actionForm()
    {
    	$request = Yii::$app->request;

    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
    	$id = $request->get('id');
        $budgetYear = $request->get('budget_year');
        $month = $request->get('month');
        $imProjLaunId = $request->get('im_proj_laun_id');
    	
    	$imProjLauns = IM_PROJ_LAUN::find()->innerJoinWith('imProj')->orderBy('IM_PROJ_LAUN.IM_PROJ_LAUN_ID')->all();
    	$arrPlaseSelect = ['' => 'กรุณาเลือก'];
    	if (empty($id)) {
    		$model = new IM_PROJ_LAUN_RES();
    	} else {
    		$model = IM_PROJ_LAUN_RES::findOne($id);
    	}

        $this->addBreadCrumb('IM003 บันทึกผลการดำเนินการประจำเดือน สอม.', Url::to(['improjlaunres/']));
        $this->addBreadCrumb('ผลการดำเนินงาน', Url::to(['improjlaunres/list',
                                                                'rtparams' => $rtparams,
                                                                'im_proj_laun_id' => $imProjLaunId,
                                                                'budget_year' => $budgetYear,
                                                                'month' => $month]));
        $this->addBreadCrumb('บันทึกผลการดำเนินงาน');

    	$params = ['rtparams' => $rtparams,
                    'firstOptionDDL' => $this->firstOptionDDL,
                    'budgetYear' => $budgetYear,
                    'month' => $month,
                    'imProjLaunId' => $imProjLaunId,
                    'imProjLauns' => $imProjLauns,
                    'model' => $model];
    	
    	return $this->render('form', $params);
    }

    public function actionCreate()
    {
    	try {
    		$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

	    	$imProjLaunRes = new IM_PROJ_LAUN_RES();
	    	$imProjLaunRes->load($request->post());
	    	$imProjLaunRes->IM_PROJ_LAUN_RES_ID = IM_PROJ_LAUN_RES::getNewID();
	    	$imProjLaunRes->TARGET_FROM = Utils::toDate($imProjLaunRes->TARGET_FROM);
	    	$imProjLaunRes->TARGET_TO = Utils::toDate($imProjLaunRes->TARGET_TO);
	    	$imProjLaunRes->ACTUAL_FROM = Utils::toDate($imProjLaunRes->ACTUAL_FROM);
	    	$imProjLaunRes->ACTUAL_TO = Utils::toDate($imProjLaunRes->ACTUAL_TO);
	    	$imProjLaunRes->STATUS = 'A';
	    	$imProjLaunRes->CREATE_USER_ID = $user_id;
			$imProjLaunRes->CREATE_TIME = new \yii\db\Expression('SYSDATE');
			$imProjLaunRes->LAST_UPD_USER_ID = $user_id;
			$imProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            $url = ['improjlaunres/list',
                        'budget_year' => $imProjLaunRes->BUDGET_YEAR,
                        'month' => $imProjLaunRes->MONTH,
                        'im_proj_laun_id' => $imProjLaunRes->IM_PROJ_LAUN_ID];

			if ($imProjLaunRes->save()) {
				Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');

                return $this->redirect(Url::to($url));
			} else {
                Utils::setErrorFlashMessage($imProjLaunRes->getErrors());
                return $this->redirect(Url::to(['improjlaunres/form']));
			}
    	} catch (Exception $e) {
    		Yii::$app->getSession()->setFlash('error',  Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['improjlaunres/form']));
    	}
    }

    public function actionUpdate()
    {
    	try {
    		$request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

	    	$imProjLaunRes = IM_PROJ_LAUN_RES::findOne($request->post('IM_PROJ_LAUN_RES')['IM_PROJ_LAUN_RES_ID']);
	    	$imProjLaunRes->load($request->post());
	    	$imProjLaunRes->TARGET_FROM = Utils::toDate($imProjLaunRes->TARGET_FROM);
	    	$imProjLaunRes->TARGET_TO = Utils::toDate($imProjLaunRes->TARGET_TO);
	    	$imProjLaunRes->ACTUAL_FROM = Utils::toDate($imProjLaunRes->ACTUAL_FROM);
	    	$imProjLaunRes->ACTUAL_TO = Utils::toDate($imProjLaunRes->ACTUAL_TO);
	    	$imProjLaunRes->STATUS = 'A';
			$imProjLaunRes->LAST_UPD_USER_ID = $user_id;
			$imProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            $url = ['improjlaunres/list',
                        'budget_year' => $imProjLaunRes->BUDGET_YEAR,
                        'month' => $imProjLaunRes->MONTH,
                        'im_proj_laun_id' => $imProjLaunRes->IM_PROJ_LAUN_ID];

			if ($imProjLaunRes->save()) {
				Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');

                return $this->redirect(Url::to($url));
			} else {
                Utils::setErrorFlashMessage($imProjLaunRes->getErrors());
                return $this->redirect(Url::to(['improjlaunres/form']));
            }
        } catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error',  Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['improjlaunres/form']));
        }
    }

    public function actionUploadFile()
    {
        try {
            $resultMsg = '';
            $resultPathFile = '';
        	$request = Yii::$app->request;
            $connection = Yii::$app->db;

            $im_proj_laun_res_id = $request->post('im_proj_laun_res_id');
            $imProjLaunRes = IM_PROJ_LAUN_RES::findOne($im_proj_laun_res_id);

            if ($imProjLaunRes) {
                // file upload
                $image = UploadedFile::getInstanceByName('IMAGE_PATH');

                // file name
                $splitFileName = explode('.', $image->name);
                $fileExtension = '.'.$splitFileName[count($splitFileName) - 1];
                $fileName = $imProjLaunRes->IM_PROJ_LAUN_RES_ID.$fileExtension;

                // Ref. http://stackoverflow.com/questions/23155428/how-to-get-root-directory-in-yii2
                $physicalPath = Yii::getAlias('@webroot').'/images/'.$this->id.'/';
                $urlPath = Yii::getAlias('@web').'/images/'.$this->id.'/';

                if (Utils::checkDirectory($physicalPath)) {
                    $transaction = $connection->beginTransaction(); 
                    $imProjLaunRes->IMAGE_PATH = $urlPath.$fileName;
                    $imProjLaunRes->LAST_UPD_USER_ID = '1';
                    $imProjLaunRes->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

                    if ($image->saveAs($physicalPath.$fileName) && $imProjLaunRes->save()) {
                        $transaction->commit();
                        $resultMsg = 'บันทึกข้อมูลสำเร็จ';
                        $resultPathFile = $urlPath.$fileName;
                    } else {
                        $transaction->rollback();
                        BaseYii::error('Upload file or Save data fiald.', 'debuger');
                        $resultMsg = 'เกิดข้อผิดพลาด';
                    }
                } else {
                    throw new Exception('Error check directory.');
                }
            } else {
                throw new Exception('Not have ID.');
            }

            echo  '{"msg":"'.$resultMsg .'", "file_path":"'.$resultPathFile.'"}';
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'debuger');
            $resultMsg = 'เกิดข้อผิดพลาด';

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
		$imProjId = $request->post('IM_PROJ_id');
		$sbSubProjId = $request->post('sb_sub_proj_id');
		$budgetYear = $request->post('budget_year');
		$projectResponsible = $request->post('project_responsible');
		$budget = $request->post('budget');
		$status = $request->post('status');

		// Parameters
    	$budget_year = $request->post('budget_year'); $budget_year = (empty($budget_year)? $request->get('budget_year'): $budget_year);

		//response parameter to jqGrid
    	$result = '';
    	
    	switch ($oper) {
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array();
    			$where = 'and';
    			if ($isSearch) {
    				$filters = json_decode($filters);
    				//print_r($filters);
    				if (!empty($filters->rules)) {
    					array_push($where_causes, $filters->groupOp);
	    				foreach( $filters->rules as $conditions ){
	    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
	    				}
    				}
    				
    			}

    			$count = IM_PROJ_LAUN_RES::find()->where($where_causes)->count();
    			$result = IM_PROJ_LAUN_RES::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

                $months = Utils::getArrMonth();

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    $monthStr = $months[$result[$i]['MONTH']];
					$imProj = IM_PROJ::findOne($result[$i]->imProjLaun['IM_PROJ_ID']);

					array_push($response->rows, array('id'=>$result[$i]['IM_PROJ_LAUN_RES_ID'], 
													'cell'=>array($seq,
																	$result[$i]['MONTH'],
                                                                    $monthStr,
                                                                    $imProj['PROJ_NAME_TH'],
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

                    IM_PROJ_LAUN_RES::deleteAll(['IM_PROJ_LAUN_RES_ID' => $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;
    }

    public function actionAjaxGetImProjLaun() {
    	$request = Yii::$app->request;

    	$budgetYear = $request->get('budget_year');

    	$imProjLauns = $this->getImProjLaun($budgetYear);

    	echo Json::encode(ArrayHelper::map($imProjLauns, 
                                            'IM_PROJ_LAUN_ID', 
                                            'imProj.PROJ_NAME_TH'));
    }

    private function getImProjLaun($budgetYear) {
    	$imProjLauns = IM_PROJ_LAUN::find()
    					->where('BUDGET_YEAR = :budgetYear', [':budgetYear' => $budgetYear])
    					->all();

        return $imProjLauns;
    }
}
