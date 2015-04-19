<?php
/**
 * Create by BOMBERMAN
 */

namespace himiklab\jqgrid;

use Yii;

class JqGridResponse
{
    public $page = 0;
    public $total = 0;
    public $records = 0;
    public $rows = array();
    public $success = false;
    public $message = '';
    public $params = array();
    
    public function response_encode(){
        $result = array('success'=>$this->success, 'message'=>$this->message);
        foreach($this->params as $key=>$value){
            $result[$key] = $value;
        }
        return json_encode($result);
    }
    
    public function success($params=array()){
        $this->success = true;
        $this->params = $params;
        // return response_encode();
    }
    
    public function error($message='Error, Please contact administrator'){
        $this->message = $message;
        Yii::error($message);
        // return response_encode();
    }
}
