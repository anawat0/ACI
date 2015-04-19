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
use app\models\NP_SAT_SURVEY_PROVINCE;
use app\models\NP_SP_OPEN_PROVINCE;

class NpSatSurveyProvinceController extends AppController
{
    public $titleCode = 'NP004';
	public $title = 'บันทึกผลสำรวจความพึงพอใจ (ศูนย์การเรียนรู้ประจำจังหวัด)';

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

//     	$this->addBreadCrumb('แสดงรายการบันทึกผลสำรวจความพึงพอใจ (ศูนย์การเรียนรู้ประจำจังหวัด)');
    	$this->addBreadCrumb($this->titleCode.' '.$this->title);

        $plaseSelect = array('' => 'กรุณาเลือก');
    	$waProvinces = WA_PROVINCE::find()->all();
    	$waAmphoes = array();
        $currentYear = intval(date('Y')) + 543;
        $currentMonth = date('n');

    	$params = array('gridTitle' => $this->title,
                        'currentYear' => $currentYear,
                        'currentMonth' => $currentMonth,
                        'rtparams' => $rtparams,
                        'pleaseSelect' => $plaseSelect,
                        'waProvinces' => $waProvinces,
                        'waAmphoes' => $waAmphoes);

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
        
        $tmp_params['year'] = $year;
        $tmp_params['month'] = $month;
        $tmp_params['province'] = $province;
        
        $backAction = ['npsatsurveyprovince/', 'rtparams'=>$rtparams];
        $backAction = BaseArrayHelper::merge($backAction, $tmp_params);
        
        $this->addBreadCrumb('​NP004 บันทึกผลสำรวจความพึงพอใจ (ศูนย์การเรียนรู้ประจำจังหวัด)', Url::to($backAction));
        $this->addBreadCrumb('บันทึก ผลสำรวจความพึงพอใจ');

        $plaseSelect = array('' => 'กรุณาเลือก');
//         $months = Utils::getMonths();
//         $waProvinces = WA_PROVINCE::find()->all();
        $isCurrentMonth = 'false';
        if (empty($id)) {
            $model = new NP_SAT_SURVEY_PROVINCE();
        } else {
            $model = NP_SAT_SURVEY_PROVINCE::findOne($id);
             $isCurrentMonth = (Utils::isCurrentMonth($model->YEAR,
                                                     $model->MONTH,
                                                     true))? 'true': 'false';
        }

        $params = BaseArrayHelper::merge($tmp_params, array('rtparams' => $rtparams,
                        'model' => $model,
                        'plaseSelect' => $plaseSelect,
                        'backAction' => $backAction,
                        'isCurrentMonth' => $isCurrentMonth
        ));

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
		//

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

                $count = NP_SAT_SURVEY_PROVINCE::find()->where($where_causes)->count();
                $result = NP_SAT_SURVEY_PROVINCE::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->all();
                $countResult = count($result);
                $seq = $rows * ($page - 1);

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;
                $addStatus = 'C';
                if (count($valueWhereAddStatus) == 3) {
                    $npSpOpenProvince = NP_SP_OPEN_PROVINCE::find()
                                        ->where('YEAR=:YEAR
                                                    AND MONTH=:MONTH
                                                    AND PROVINCE_CODE=:PROVINCE_CODE'
                                                , $valueWhereAddStatus)
                                        ->one();
                    if (!empty($npSpOpenProvince)) {
                        $addStatus = $npSpOpenProvince->STATUS;
                    }
                }
                $response->add_status = $addStatus;

				for ($i=0; $i<$countResult; $i++) {
                    $seq++;
                    $monthStr = $months[$result[$i]['MONTH']];
                    $sum =  intval($result[$i]['NUM_MALE']) + intval($result[$i]['NUM_FEMALE']);

					array_push($response->rows, array('id' => $result[$i]['NP_SAT_SURVEY_PROVINCE_ID'], 
                                                      'cell' => array($seq,
                                                                        $result[$i]['YEAR'],
                                                                        $result[$i]['MONTH'],
                                                                        $monthStr,
                                                                        $result[$i]->waProvince['PROVINCE_NAME_TH'],
                                                                        $result[$i]['CREATE_TIME'],
                                                                        $result[$i]['NUM_MALE'],
                                                                        $result[$i]['NUM_FEMALE'],
                                                                        $result[$i]['AGE_1'],
                                                                        $result[$i]['AGE_2'],
                                                                        $result[$i]['AGE_3'],
                                                                        $result[$i]['AGE_4'],
                                                                        $result[$i]['AGE_5'],
                                                                        $sum,
                                                                        empty($result[$i]->npSpOpenProvince)? $result[$i]->STATUS: $result[$i]->npSpOpenProvince->STATUS
                                                                        )));
				}

                $result = json_encode($response);
                break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    NP_SAT_SURVEY_PROVINCE::deleteAll(['in', 'NP_SAT_SURVEY_PROVINCE_ID', $arrId]);

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

            $hidden_return = $request->post('return');
            foreach($hidden_return as $key=>$value){
                $backAction[$key]=$value;
            }

            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $npSatSurveyProvince = new NP_SAT_SURVEY_PROVINCE();
            $npSatSurveyProvince->load($request->post());
            $npSatSurveyProvince->NP_SAT_SURVEY_PROVINCE_ID = NP_SAT_SURVEY_PROVINCE::getNewID();
            $npSatSurveyProvince->KPI_1 = BaseJson::encode($npSatSurveyProvince->KPI_1);
            $npSatSurveyProvince->KPI_2 = BaseJson::encode($npSatSurveyProvince->KPI_2);
            $npSatSurveyProvince->KPI_3 = BaseJson::encode($npSatSurveyProvince->KPI_3);
            $npSatSurveyProvince->KPI_4 = BaseJson::encode($npSatSurveyProvince->KPI_4);
            $npSatSurveyProvince->KPI_5 = BaseJson::encode($npSatSurveyProvince->KPI_5);
            $npSatSurveyProvince->KPI_6 = BaseJson::encode($npSatSurveyProvince->KPI_6);
            $npSatSurveyProvince->KPI_7 = BaseJson::encode($npSatSurveyProvince->KPI_7);
            $npSatSurveyProvince->KPI_8 = BaseJson::encode($npSatSurveyProvince->KPI_8);
            $npSatSurveyProvince->KPI_9 = BaseJson::encode($npSatSurveyProvince->KPI_9);
            $npSatSurveyProvince->KPI_10 = BaseJson::encode($npSatSurveyProvince->KPI_10);
            $npSatSurveyProvince->STATUS = 'A';
            $npSatSurveyProvince->CREATE_USER_ID = $user_id;
            $npSatSurveyProvince->CREATE_TIME = new \yii\db\Expression('SYSDATE');
            $npSatSurveyProvince->LAST_UPD_USER_ID = $user_id;
            $npSatSurveyProvince->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($npSatSurveyProvince->save()) {
                $backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
                return $this->redirect(Url::to($backAction));
                // Yii::$app->getSession()->setFlash('success', 'Save data completed.');
            } else {
                throw new Exception('failed : '.print_r($npSatSurveyProvince->getErrors(), true));
                // Yii::$app->getSession()->setFlash('error', 'Save data failed.');
            }

            // return $this->redirect(Url::to(['npsatsurveyprovince/index']));
        } catch (Exception $e) {
            $backAction[0] = 'npsatsurveyprovince/form';
            $backAction['id'] = $id;
            $backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');//base64_encode($e);
            Yii::trace($e->getMessage(), 'debug');
            return $this->redirect(Url::to($backAction));
            // Yii::$app->getSession()->setFlash('error', 'Save data failed.');
            // return $this->redirect(Url::to(['npsatsurveyprovince/index']));
        }
    }

    public function actionUpdate()
    {
        try {
            $request = Yii::$app->request;

            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;

            $hidden_return = $request->post('return');
            foreach($hidden_return as $key=>$value){
                $backAction[$key]=$value;
            }
            
            $id = $request->post('NP_SAT_SURVEY_PROVINCE')['NP_SAT_SURVEY_PROVINCE_ID'];

            $npSatSurveyProvince = NP_SAT_SURVEY_PROVINCE::findOne($id);
            // print_r($npSatSurveyProvince);
            $npSatSurveyProvince->load($request->post());
            $npSatSurveyProvince->KPI_1 = BaseJson::encode($npSatSurveyProvince->KPI_1);
            $npSatSurveyProvince->KPI_2 = BaseJson::encode($npSatSurveyProvince->KPI_2);
            $npSatSurveyProvince->KPI_3 = BaseJson::encode($npSatSurveyProvince->KPI_3);
            $npSatSurveyProvince->KPI_4 = BaseJson::encode($npSatSurveyProvince->KPI_4);
            $npSatSurveyProvince->KPI_5 = BaseJson::encode($npSatSurveyProvince->KPI_5);
            $npSatSurveyProvince->KPI_6 = BaseJson::encode($npSatSurveyProvince->KPI_6);
            $npSatSurveyProvince->KPI_7 = BaseJson::encode($npSatSurveyProvince->KPI_7);
            $npSatSurveyProvince->KPI_8 = BaseJson::encode($npSatSurveyProvince->KPI_8);
            $npSatSurveyProvince->KPI_9 = BaseJson::encode($npSatSurveyProvince->KPI_9);
            $npSatSurveyProvince->KPI_10 = BaseJson::encode($npSatSurveyProvince->KPI_10);
            //$npSatSurveyProvince->STATUS = ($npSatSurveyProvince->STATUS == '1')? 'A': 'C';
            $npSatSurveyProvince->LAST_UPD_USER_ID = $user_id;
            $npSatSurveyProvince->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');

            if ($npSatSurveyProvince->save()) {
                $backAction['alertmessage'] = base64_encode('บันทึกสำเร็จ');
                return $this->redirect(Url::to($backAction));
                // Yii::$app->getSession()->setFlash('success', 'Update data completed.');
            } else {
                 throw new Exception('failed : '.print_r($npSatSurveyProvince->getErrors(),true));
                // Yii::$app->getSession()->setFlash('error', 'Update data failed.');
            }

            // return $this->redirect(Url::to(['npsatsurveyprovince/index']));
        } catch (Exception $e) {
            $backAction[0] = 'npsatsurveyprovince/form';
            $backAction['id'] = $id;
            $backAction['alertmessage'] = base64_encode('บันทึกไม่สำเร็จ กรุณาติดต่อผู้ดูแลระบบ');//base64_encode($e);
            Yii::trace($e->getMessage(), 'debug');
            return $this->redirect(Url::to($backAction));
            // Yii::$app->getSession()->setFlash('error', 'Update data failed.');
            // return $this->redirect(Url::to(['npsatsurveyprovince/index']));
        }
    }
}
