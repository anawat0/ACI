<?php
/**
 * Created by Chinnawat B.
 */

namespace yii\web;

class AjaxController extends \yii\web\Controller
{
    
    public function ajaxResponse($obj){
        echo json_encode($obj);
    }
    
}
