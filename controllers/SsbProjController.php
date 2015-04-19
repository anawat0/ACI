<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\SSB_MAIN_PROJ;
use app\models\SSB_PROJ;

class SsbProjController extends AppController
{
	public $title = 'SSB001 รายชื่อโครงการ สวฬ.';
	
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

        $ssbMainProjId = $request->post('ssb_main_proj_id'); $ssbMainProjId = (empty($ssbMainProjId)? $request->get('ssb_main_proj_id'): $ssbMainProjId);
        $ssbMainProj = SSB_MAIN_PROJ::findOne($ssbMainProjId);

        $this->addBreadCrumb('SSB001 รายชื่อโครงการ สวฬ.', Url::to(['ssbmainproj/index']));
        $this->addBreadCrumb('โครงการ');

        $params = array('rtparams' => '', 'ssbMainProj' => $ssbMainProj);
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
        $ssbMainProjId = $request->post('ssb_main_proj_id'); $ssbMainProjId = (empty($ssbMainProjId)? $request->get('ssb_main_proj_id'): $ssbMainProjId);
        $projNameTh = $request->post('proj_name_th');
        $projNameEn = $request->post('proj_name_en');
        $budget = $request->post('budget');
        $status = $request->post('status');
                       
        //response parameter to jqGrid
        $result = '';
        
        switch ($oper) {
            case 'request':
                $offset = ($page-1)*$rows;
                
                $where_causes = array('SSB_MAIN_PROJ_ID' => $ssbMainProjId);
                $where = 'and';
                if($isSearch) {
                    $filters = json_decode($filters);
                    // print_r($filters);
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
                    }
                }
                
                $count = SSB_PROJ::find()->where($where_causes)->count();
                $result = SSB_PROJ::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
                $countResult = count($result);
                $seq = $rows * ($page - 1);

                $response = new jqGridResponse();
                $response->page = $page;
                $response->total = intval(ceil($count/$rows));
                $response->records = $count;

                for ($i=0; $i<$countResult; $i++) {
                    $seq++;

                    array_push($response->rows, array('id' => $result[$i]['SSB_PROJ_ID'], 
                                                      'cell' => array($seq,
                                                                        $result[$i]['SSB_MAIN_PROJ_ID'],
                                                                        $result[$i]['PROJ_NAME_TH'],
                                                                        $result[$i]['PROJ_NAME_EN'],
                                                                        $result[$i]['BUDGET'],
                                                                        $result[$i]['STATUS'],
                                                                        '',
                                                                        'edit')));
                }

                $result = json_encode($response);
                break;
            case 'edit':
                //To update an existing customer record
                $ssbProj = SSB_PROJ::findOne($id);
                $ssbProj->SSB_MAIN_PROJ_ID = $ssbMainProjId;
                $ssbProj->PROJ_NAME_TH = $projNameTh;
                $ssbProj->PROJ_NAME_EN = $projNameEn;
                $ssbProj->BUDGET = $budget; 
                $ssbProj->STATUS = Utils::getStatus($status);
                $ssbProj->LAST_UPD_USER_ID = $user_id;
                $ssbProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                
                $response = new jqGridResponse();
                if ($ssbProj->save()) {  
                    $response->success(['id'=>$ssbProj->SSB_PROJ_ID]);
                } else {
                    $response->error($ssbProj->getErrors());
                }

                $result = $response->response_encode();
                break;
            case 'add':
                $ssbProj = new SSB_PROJ();
                $ssbProj->SSB_PROJ_ID = SSB_PROJ::getNewID();
                $ssbProj->SSB_MAIN_PROJ_ID = $ssbMainProjId;
                $ssbProj->PROJ_NAME_TH = $projNameTh;
                $ssbProj->PROJ_NAME_EN = $projNameEn;
                $ssbProj->BUDGET = $budget;
                $ssbProj->STATUS = Utils::getStatus($status);
                $ssbProj->CREATE_USER_ID = $user_id;
                $ssbProj->CREATE_TIME = new \yii\db\Expression('SYSDATE');
                $ssbProj->LAST_UPD_USER_ID = $user_id;
                $ssbProj->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                
                $response = new jqGridResponse();
                if ($ssbProj->save()) {  
                    $response->success(['id'=>$ssbProj->SSB_PROJ_ID]);
                } else {
                    $response->error($ssbProj->getErrors());
                }

                $result = $response->response_encode();
                break;
            case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    SSB_PROJ::deleteAll(['in', 'SSB_PROJ_ID', $arrId]);

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
