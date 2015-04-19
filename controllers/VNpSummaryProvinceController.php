<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\V_NP_SUMMARY_PROVINCE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\web\Utils;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\base\Exception;
use yii\helpers\Json;

class VNpSummaryProvinceController extends AppController
{
    public $title = 'NP007';
    public $arrPlaseSelect = array('' => 'กรุณาเลือก');

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

        $currentYear = intval(date('Y')) + 543;
        $currentMonth = date('n');
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
        
    	$params = array('currentYear' => $currentYear,
                        'currentMonth' => $currentMonth,
                        'rtparams' => $rtparams,
    					'arrPlaseSelect' => $this->arrPlaseSelect);

    	$this->addBreadCrumb('NP007 สรุปรายงานของสำนักงานส่วนภูมิภาค (จพล. ประจำจังหวัด)');

        return $this->render('index', $params);
    }

    public function actionGridview()
    {
    	//$user_id = Yii::$app->user->identity->WA_USER_ID;
    	$request = Yii::$app->request;
    	//$workId = $request->post('w_id');

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

                $count = V_NP_SUMMARY_PROVINCE::find()->where($where_causes)->count();
                $result = V_NP_SUMMARY_PROVINCE::find()
                                        ->where($where_causes)
                                        ->orderBy($sidx.' '.$sord)
                                        ->offset($offset)
                                        ->limit($rows)
                                        ->all();

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;

                $seq = $rows * ($page - 1);
                $months = Utils::getArrMonth();
                $status = '';
                $statusStr = '';

				for ($i=0; $i<count($result); $i++) {
					$seq++;
					$monthStr = $months[$result[$i]['MONTH']];

                    // status
                    if (isset($result[$i]->npSpOpenAmphoe)) {
                        $status = $result[$i]->npSpOpenProvince->STATUS;
                    } else {
                        $status = $result[$i]->STATUS;
                    }
                    $statusStr = Utils::getStatus('NP_'.$status);

					array_push($response->rows, array('id' => $seq,
														'cell' => array($seq,
																		$result[$i]['YEAR'],
                                                                        $result[$i]['MONTH'],
																		$monthStr,
																		$result[$i]['PROVINCE_CODE'],
																		$result[$i]['PROVINCE_NAME_TH'],
																		$result[$i]['COUNT_FORM1'],
																		$result[$i]['COUNT_FORM2_1'],
																		$result[$i]['COUNT_FORM2_2'],
																		$result[$i]['COUNT_SAT'],
																		$status,
                                                                        $statusStr,
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
				break;
        }

        echo $result;
    }

    public function actionAjaxUpdateStatus()
    {
        $request = Yii::$app->request;

        $result = array();

        $status = $request->post('status');
        $provinceCode = $request->post('province_code');
        $month = $request->post('month');
        $year = $request->post('year');

        if ($status == 'A') {
            $result = V_NP_SUMMARY_PROVINCE::callSpOpenRecProvince($provinceCode,
                                                                    $month,
                                                                    $year);
            $result['status'] = 'S';
        } elseif ($status == 'S') {
            $result = V_NP_SUMMARY_PROVINCE::callSpCloseRecProvince($provinceCode,
                                                                    $month,
                                                                    $year);
            $result['status'] = 'C';
        } elseif ($status == 'C') {
            $result = V_NP_SUMMARY_PROVINCE::callSpOpenRecProvince($provinceCode,
                                                                    $month,
                                                                    $year);
            $result['status'] = 'S';
        } else{
            $result = V_NP_SUMMARY_PROVINCE::callSpCloseRecProvince($provinceCode,
                                                                    $month,
                                                                    $year);
            $result['status'] = 'C';
        }
        $result['status_str'] = Utils::getStatus('NP_'.$result['status']);

        echo Json::encode($result);
    }
}
