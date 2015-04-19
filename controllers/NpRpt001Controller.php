<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\NP_STAFF;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Utils;
use yii\web\Authentication;
use yii\helpers\BaseArrayHelper;

class NpRpt001Controller extends AppController
{
	public $title = 'รายงานบันทึกผล จพล. ประจำอำเภอ';
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
        $params = array();

        return $this->render('index', $params);
    }

}
