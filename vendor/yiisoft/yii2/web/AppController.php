<?php
/**
 * Created by Chinnawat B.
 */

namespace yii\web;

class AppController extends \yii\web\Controller
{
    public $title = 'DPE E-Warning System';
    public $breadcrumbs = array(['label'=>'Home']);
    
    public $filtersOperand = array('eq'=>'=',
                                'ne'=>'!=',
                                'lt'=>'<',
                                'le'=>'<=',
                                'gt'=>'>',
                                'ge'=>'>=',
                                'nu'=>' IS NULL ',
                                'nn'=>' IS NOT NULL ',
                                'in'=>'=',
                                'ni'=>'='
                                );
    
    public function init(){
    	if (!Authentication::isLoggedIn()) {
    		\Yii::$app->getResponse()->redirect(['site/login']);
    	}
    }
    
    public function addBreadCrumb($label, $link=''){
		array_push($this->breadcrumbs, ['label'=>$label, 'link'=>$link]);
    }
    
    public function ajaxResponse($obj){
        echo json_encode($obj);
    }
}
