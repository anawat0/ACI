<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\AppController;
use yii\web\Utils;
use yii\web\Authentication;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\BaseJson;
use yii\helpers\BaseArrayHelper;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use app\models\NP_SAT_SURVEY_AMPHOE;
use app\models\NP_SP_OPEN_AMPHOE;

class NpSatSurveyAmphoeController extends AppController
{
    public $titleCode = 'NP002';
	public $title = 'บันทึกผลสำรวจความพึงพอใจ (ศูนย์การเรียนรู้ประจำอำเภอ)';

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

        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);

    	$this->addBreadCrumb($this->titleCode.' '.$this->title);

        $currentYear = intval(date('Y')) + 543;
        $currentMonth = date('n');
        $plaseSelect = array('' => 'กรุณาเลือก');
//     	$waProvinces = WA_PROVINCE::find()->all();
    	$waAmphoes = array();

    	$params = array('gridTitle' => $this->title,
                        'currentYear' => $currentYear,
                        'currentMonth' => $currentMonth,
                        'rtparams' => $rtparams,
                        'pleaseSelect' => $plaseSelect
//                         'waProvinces' => $waProvinces,
//                         'waAmphoes' => $waAmphoes
        );

        return $this->render('index', $params);
    }

    public function actionForm()
    {
        $request = Yii::$app->request;

        $id = $request->post('id'); $id = (empty($id)? $request->get('id'): $id);
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);

        $year = $request->get('year');
        $month = $request->get('month');
        $province = $request->get('province');
        $amphoe = $request->get('amphoe');
        
        $tmp_params['year'] = $year;
        $tmp_params['month'] = $month;
        $tmp_params['province'] = $province;
        $tmp_params['amphoe'] = $amphoe;
        
        $backAction = ['npsatsurveyamphoe/', 'rtparams'=>$rtparams];
        $backAction = BaseArrayHelper::merge($backAction, $tmp_params);
        
        $params = $tmp_params;
        $params['backAction'] = $backAction;
        
        $params['npStaff'] = array();
        $params['id']=$id;
        
        $this->addBreadCrumb('​NP002 บันทึกผลสำรวจความพึงพอใจ (ศูนย์การเรียนรู้ประจำอำเภอ)', Url::to($backAction));
        $this->addBreadCrumb('บันทึก ผลสำรวจความพึงพอใจ');

        $plaseSelect = array('' => 'กรุณาเลือก');
//         $months = Utils::getMonths();
//         $waProvinces = WA_PROVINCE::find()->all();
        $isCurrentMonth = 'false';
        if (empty($id)) {
            $model = new NP_SAT_SURVEY_AMPHOE();
//             $waAmphoes = array();
        } else {
            $model = NP_SAT_SURVEY_AMPHOE::findOne($id);
//             $waAmphoes = WA_AMPHOE::findAll(['PROVINCE_CODE' => $model['PROVINCE_CODE']]);
            $isCurrentMonth = (Utils::isCurrentMonth($model->YEAR,
                                                     $model->MONTH,
                                                     true))? 'true': 'false';
        }

        $params = BaseArrayHelper::merge($tmp_params, array('rtparams' => $rtparams,
                        'model' => $model,
                        'plaseSelect' => $plaseSelect,
//                         'months' => $months,
                        'backAction' => $backAction,
                        'isCurrentMonth' => $isCurrentMonth));

        return $this->render('form', $params);
    }

    public function actionGridview()
    {
    	$request = Yii::$app->request;

        $months = Utils::getMonths();

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
		$mainProjNameTh = $request->post('main_proj_name_th');
		$mainProjNameEn = $request->post('main_proj_name_en');
		$budgetYear = $request->post('budget_year');
		$status = $request->post('status');

		//response parameter to jqGrid
    	$result = '';

    	switch ($oper) {
    		case 'request':
    			$offset = ($page-1)*$rows;
    			$where_causes = array();
                $valueWhereAddStatus = [];
    			$where = 'and';
    			if($isSearch) {
    				$filters = json_decode($filters);
    				//print_r($filters);
    				switch ($filters->groupOp) {
    					case 'AND':
    						$where = 'and';
    						break;
    					case 'OR':
    						$where = 'or';
    						break;
    				}
    				array_push($where_causes, $where);
    				foreach ($filters->rules as $conditions) {
                        array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
                        $valueWhereAddStatus[":".$conditions->field] = $conditions->data;
                    }
                }
                //print_r($where_causes);

                $count = NP_SAT_SURVEY_AMPHOE::find()->where($where_causes)->count();
                $result = NP_SAT_SURVEY_AMPHOE::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
                $countResult = count($result);
                $seq = $rows * ($page - 1);

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;
                $addStatus = 'C';
                if (count($valueWhereAddStatus) == 4) {
                    $npSpOpenAmphoe = NP_SP_OPEN_AMPHOE::find()
                                        ->where('YEAR=:YEAR
                                                    AND MONTH=:MONTH
                                                    AND PROVINCE_CODE=:PROVINCE_CODE
                                                    AND AMPHOE_CODE=:AMPHOE_CODE'
                                                , $valueWhereAddStatus)
                                        ->one();
                    if (!empty($npSpOpenAmphoe)) {
                        $addStatus = $npSpOpenAmphoe->STATUS;
                    }
                }
                $response->add_status = $addStatus;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    $monthStr = $months[$result[$i]['MONTH']];
                    $sum =  intval($result[$i]['NUM_MALE']) + intval($result[$i]['NUM_FEMALE']);

					array_push($response->rows, array('id' => $result[$i]['NP_SAT_SURVEY_AMPHOE_ID'], 
                                                      'cell' => array($seq,
                                                                        $result[$i]['YEAR'],
                                                                        $result[$i]['MONTH'],
                                                                        $monthStr,
                                                                        $result[$i]->waProvince['PROVINCE_NAME_TH'],
                                                                        $result[$i]->waAmphoe['AMPHOE_NAME_TH'],
                                                                        $result[$i]['CREATE_TIME'],
                                                                        $result[$i]['NUM_MALE'],
                                                                        $result[$i]['NUM_FEMALE'],
                                                                        $result[$i]['AGE_1'],
                                                                        $result[$i]['AGE_2'],
                                                                        $result[$i]['AGE_3'],
                                                                        $result[$i]['AGE_4'],
                                                                        $result[$i]['AGE_5'],
                                                                        $sum,
                                                                        empty($result[$i]->npSpOpenAmphoe)? $result[$i]->STATUS: $result[$i]->npSpOpenAmphoe->STATUS
                                                                        )));
				}

                $result = json_encode($response);
                break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    NP_SAT_SURVEY_AMPHOE::deleteAll(['in', 'NP_SAT_SURVEY_AMPHOE_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }

                $result = $response->response_encode();
				break;
        }

        echo $result;
    }

    public function actionCreate()
    {
        try {
            $request = Yii::$app->request;
            //print_r($request->post());

            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;
            
            $hidden_return = $request->post('return');
            foreach($hidden_return as $key=>$value){
            	$backAction[$key]=$value;
            }
            
            
            $npSatSurveyAmphoe = new NP_SAT_SURVEY_AMPHOE();
            $npSatSurveyAmphoe->load($request->post());
            $npSatSurveyAmphoe->NP_SAT_SURVEY_AMPHOE_ID = NP_SAT_SURVEY_AMPHOE::getNewID();
            $npSatSurveyAmphoe->KPI_1 = BaseJson::encode($npSatSurveyAmphoe->KPI_1);
            $npSatSurveyAmphoe->KPI_2 = BaseJson::encode($npSatSurveyAmphoe->KPI_2);
            $npSatSurveyAmphoe->KPI_3 = BaseJson::encode($npSatSurveyAmphoe->KPI_3);
            $npSatSurveyAmphoe->KPI_4 = BaseJson::encode($npSatSurveyAmphoe->KPI_4);
            $npSatSurveyAmphoe->KPI_5 = BaseJson::encode($npSatSurveyAmphoe->KPI_5);
            $npSatSurveyAmphoe->KPI_6 = BaseJson::encode($npSatSurveyAmphoe->KPI_6);
            $npSatSurveyAmphoe->KPI_7 = BaseJson::encode($npSatSurveyAmphoe->KPI_7);
            $npSatSurveyAmphoe->KPI_8 = BaseJson::encode($npSatSurveyAmphoe->KPI_8);
            $npSatSurveyAmphoe->KPI_9 = BaseJson::encode($npSatSurveyAmphoe->KPI_9);
            $npSatSurveyAmphoe->KPI_10 = BaseJson::encode($npSatSurveyAmphoe->KPI_10);
            $npSatSurveyAmphoe->STATUS = 'A';
            $npSatSurveyAmphoe->CREATE_USER_ID = $user_id;
            $npSatSurveyAmphoe->CREATE_TIME = new \yii\db\Expression('SYSDATE');
            $npSatSurveyAmphoe->LAST_UPD_USER_ID = $user_id;
            $npSatSurveyAmphoe->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($npSatSurveyAmphoe->save()) {
                $backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
                return $this->redirect(Url::to($backAction));
            } else {
            	throw new Exception('failed : '.print_r($npSatSurveyAmphoe->getErrors(), true));
//                 Yii::$app->getSession()->setFlash('error', 'Save data failed.');
            }

            // print_r($npSatSurveyAmphoe);
            // print_r($npSatSurveyAmphoe->getErrors());
//             return $this->redirect(Url::to(['npsatsurveyamphoe/index']));
        } catch (Exception $e) {
            Yii::trace($e->getMessage(), 'debug');
            $backAction[0] = 'npsatsurveyamphoe/form';
            $backAction['id'] = $id;
            $backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');

            return $this->redirect(Url::to($backAction));
//             return $this->redirect(Url::to(['npsatsurveyamphoe/index']));
        }
    }

    public function actionUpdate()
    {
        try {
        	
        	$user_info = Authentication::getAuthenInfo();
        	$user_id = $user_info->EMAIL;
        	
            $request = Yii::$app->request;
            //print_r($request->post());
            $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
            $year = $request->get('year');
            $month = $request->get('month');
            $province = $request->get('province');
            $amphoe = $request->get('amphoe');
            
            $tmp_params['year'] = $year;
            $tmp_params['month'] = $month;
            $tmp_params['province'] = $province;
            $tmp_params['amphoe'] = $amphoe;
            
            $backAction = ['npsatsurveyamphoe/', 'rtparams'=>$rtparams];
            $backAction = BaseArrayHelper::merge($backAction, $tmp_params);
            
            $id = $request->post('NP_SAT_SURVEY_AMPHOE')['NP_SAT_SURVEY_AMPHOE_ID'];

            $npSatSurveyAmphoe = NP_SAT_SURVEY_AMPHOE::findOne($id);
            // print_r($npSatSurveyAmphoe);
            $npSatSurveyAmphoe->load($request->post());
            $npSatSurveyAmphoe->KPI_1 = BaseJson::encode($npSatSurveyAmphoe->KPI_1);
            $npSatSurveyAmphoe->KPI_2 = BaseJson::encode($npSatSurveyAmphoe->KPI_2);
            $npSatSurveyAmphoe->KPI_3 = BaseJson::encode($npSatSurveyAmphoe->KPI_3);
            $npSatSurveyAmphoe->KPI_4 = BaseJson::encode($npSatSurveyAmphoe->KPI_4);
            $npSatSurveyAmphoe->KPI_5 = BaseJson::encode($npSatSurveyAmphoe->KPI_5);
            $npSatSurveyAmphoe->KPI_6 = BaseJson::encode($npSatSurveyAmphoe->KPI_6);
            $npSatSurveyAmphoe->KPI_7 = BaseJson::encode($npSatSurveyAmphoe->KPI_7);
            $npSatSurveyAmphoe->KPI_8 = BaseJson::encode($npSatSurveyAmphoe->KPI_8);
            $npSatSurveyAmphoe->KPI_9 = BaseJson::encode($npSatSurveyAmphoe->KPI_9);
            $npSatSurveyAmphoe->KPI_10 = BaseJson::encode($npSatSurveyAmphoe->KPI_10);
            //$npSatSurveyAmphoe->STATUS = ($npSatSurveyAmphoe->STATUS == '1')? 'A': 'C';
            $npSatSurveyAmphoe->LAST_UPD_USER_ID = $user_id;
            $npSatSurveyAmphoe->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($npSatSurveyAmphoe->save()) {
                //Yii::$app->getSession()->setFlash('success', 'Update data completed.');
                $backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
                $this->redirect(Url::to($backAction));
            } else {
                throw new Exception('failed : '.print_r($npSatSurveyAmphoe->getErrors(), true));
                // Yii::$app->getSession()->setFlash('error', 'Update data failed.');
            }

//             return $this->redirect(Url::to(['npsatsurveyamphoe/']));
        } catch (Exception $e) {
            Yii::trace($e->getMessage(), 'debug');
            $backAction[0] = 'npsatsurveyamphoe/form';
            $backAction['id'] = $id;
            $backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');
        
            return $this->redirect(Url::to($backAction));
            // return $this->redirect(Url::to($backAction));
        }
    }
}
