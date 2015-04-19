<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RB_PROJ_LAUN;
use app\models\RB_PROJ;
use app\models\RB_SUB_PROJ;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\db\Query;

class RbWorkController extends AppController
{
    public $title = 'RD003 บันทึกผลการดาเนินการประจำเดือน สนก.';
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

        $currentYear = $request->get('budget_year')? $request->get('budget_year'): intval(date('Y')) + 543;
        $projList = $this->actionGetProjList($currentYear);

    	$params = array('currentYear' => $currentYear,
                        'projList' => json_encode($projList));

    	return $this->render('index', $params);
    }

    public function actionAjaxGetProjList()
    {
        $request = Yii::$app->request;

        $year = $request->get('year');

        $projList = $this->actionGetProjList($year);

        echo json_encode($projList);
    }

    public function actionGetProjList($year)
    {
        $result = array();

        // SELECT T2.RB_PROJ_ID, T2.PROJ_NAME_TH
        // FROM RB_PROJ_LAUN T1 ,RB_PROJ T2 
        // where T1.RB_PROJ_ID = T2.RB_PROJ_ID
        // AND T1.BUDGET_YEAR = '' AND T1.STATUS = ''
        $rbProjLauns = RB_PROJ_LAUN::find()
                                ->where(['BUDGET_YEAR' => $year,
                                        'STATUS' => 'A'])
                                ->all();

        foreach ($rbProjLauns as $key => $value) {
            $tmpArr = array();
            $tmpArr['RB_PROJ_LAUN_ID'] = $value['RB_PROJ_LAUN_ID'];
            $tmpArr['BUDGET_YEAR'] = $value['BUDGET_YEAR'];
            $tmpArr['RB_PROJ_ID'] = $value->rbProj['RB_PROJ_ID'];
            $tmpArr['RB_PROJ_NAME_TH'] = $value->rbProj['PROJ_NAME_TH'];
            // select * from RB_PROJ_LAUN T1, RB_SUB_PROJ T2 where  T1.RB_PROJ_ID = T2.RB_PROJ_ID
            // AND T1.RB_PROJ_ID = '5' AND T1.BUDGET_YEAR = ''
            // $query = new Query;
            // $query->select('RB_SUB_PROJ.*, RB_PROJ_LAUN.RB_PROJ_LAUN_ID, RB_PROJ_LAUN.BUDGET_YEAR')
            //         ->from('RB_PROJ_LAUN')
            //         ->innerJoin('RB_SUB_PROJ', 'RB_SUB_PROJ.RB_PROJ_ID = RB_PROJ_LAUN.RB_PROJ_ID')
            //         ->where(['RB_PROJ_LAUN.RB_PROJ_ID' => $value->rbProj['RB_PROJ_ID'],
            //                 'RB_PROJ_LAUN.BUDGET_YEAR' => $value['BUDGET_YEAR']]);   
            // $tmpArr['RB_SUB_PROJS'] = $query->all();   
            $tmpArr['RB_SUB_PROJS'] = RB_SUB_PROJ::find()
                                        ->where('RB_PROJ_ID = :RB_PROJ_ID',
                                                ['RB_PROJ_ID' => $value->rbProj['RB_PROJ_ID']])
                                        ->asArray()
                                        ->all();
                                        
            array_push($result, $tmpArr);
        }

        return $result;        
    }
}
