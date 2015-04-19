<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use yii\helpers\Url;
use yii\web\Utils;
use yii\web\Authentication;
use yii\helpers\BaseArrayHelper;

class NpRpt002Controller extends AppController
{
	public $title = 'รายงานบันทึกผล จพล. ประจำจังหวัด';
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
