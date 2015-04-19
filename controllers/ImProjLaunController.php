<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\web\Authentication;
use yii\web\Utils;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\IM_PROJ;
use app\models\IM_SUB_PROJ;
use app\models\IM_PROJ_LAUN;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class ImProjLaunController extends AppController
{
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
    	$request = Yii::$app->request;

    	// Parameters.
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);

    	$params = array('rtparams' => $rtparams,
    					'firstOptionDDL' => $this->firstOptionDDL);

    	$this->addBreadCrumb('IM002 โครงการที่กำลังดำเนินการ สอม.');

        return $this->render('index', $params);
    }

    public function actionForm() 
    {
    	$request = Yii::$app->request;

    	// Parameters.
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $id = $request->get('id');

    	$imProjs = IM_PROJ::find()->all();
        if ($id) {
            $model = IM_PROJ_LAUN::findOne($id);
        } else {
            $model = new IM_PROJ_LAUN();
        }
    	
    	$params = array('rtparams' => $rtparams,
    					'firstOptionDDL' => $this->firstOptionDDL,
    					'imProjs' => $imProjs,
    					'model' => $model);

    	$this->addBreadCrumb('IM002 โครงการที่กำลังดำเนินการ สอม.', Url::to(['improjlaun/']));
    	$this->addBreadCrumb('บันทึกโครงการที่กำลังดำเนินการ');

        return $this->render('form', $params);
    }

    public function actionCreate()
    {
    	try {
            $request = Yii::$app->request;
            
            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $improjlaun = new IM_PROJ_LAUN();
            $improjlaun->load($request->post());
            $improjlaun->IM_PROJ_LAUN_ID = IM_PROJ_LAUN::getNewID();
            $improjlaun->FROM_DATE = Utils::toDate($improjlaun->FROM_DATE);
	    	$improjlaun->TO_DATE = Utils::toDate($improjlaun->TO_DATE);
            //$improjlaun->STATUS = ($improjlaun->STATUS == '1')? 'A': 'C';
            $improjlaun->CREATE_USER_ID = $user_id;
            $improjlaun->CREATE_TIME = new \yii\db\Expression('SYSDATE');
            $improjlaun->LAST_UPD_USER_ID = $user_id;
            $improjlaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($improjlaun->save()) {
                Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
                
                return $this->redirect(Url::to(['improjlaun/index']));
            } else {
                Utils::setErrorFlashMessage($improjlaun->getErrors());
                
                return $this->redirect(Url::to(['improjlaun/form']));
            }
        } catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['improjlaun/form']));
        }
    }

    public function actionUpdate()
    {
        try {
            $request = Yii::$app->request;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $model = $request->post();
            // Yii::trace($model, 'debugger');
            $improjlaun = IM_PROJ_LAUN::findOne($model['IM_PROJ_LAUN']['IM_PROJ_LAUN_ID']);
            $improjlaun->load($model);
            $improjlaun->FROM_DATE = Utils::toDate($improjlaun->FROM_DATE);
	    	$improjlaun->TO_DATE = Utils::toDate($improjlaun->TO_DATE);
            //$improjlaun->STATUS = ($improjlaun->STATUS == '1')? 'A': 'C';
            $improjlaun->LAST_UPD_USER_ID = $user_id;
            $improjlaun->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($improjlaun->save()) {
                Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');

                return $this->redirect(Url::to(['improjlaun/index']));
            } else {
                Utils::setErrorFlashMessage($improjlaun->getErrors());

                return $this->redirect(Url::to(['improjlaun/form']));
            }
        } catch (Exception $e) {
            Yii::$app->getSession()->setFlash('error', Utils::getOracleErrorMsg($e->errorInfo[1]));

            return $this->redirect(Url::to(['improjlaun/form']));
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
		//

		//response parameter to jqGrid
    	$result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$count = IM_PROJ_LAUN::find()->count();
    			$where_causes = array();
    			// if($isSearch){
    			// 	//['type' => 1, 'status' => 2]
    			// }
    			
    			$result = IM_PROJ_LAUN::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;

				for ($i = 0; $i < $countResult; $i++) {
					$seq++;
					
					array_push($response->rows, array('id'=>$result[$i]['IM_PROJ_LAUN_ID'], 
														'cell'=>array($seq,
																	$result[$i]->imProj['PROJ_NAME_TH'],
																	$result[$i]['BUDGET_YEAR'],
																	$result[$i]['PROJ_RESP'],
																	$result[$i]['BUDGET'],
																	$result[$i]['STATUS'])));
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

                    IM_PROJ_LAUN::deleteAll(['IM_PROJ_LAUN_ID' => $arrId]);

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
