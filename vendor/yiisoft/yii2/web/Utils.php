<?php

namespace yii\web;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

Class Utils{

    static protected $months = [''=>'กรุณาเลือก',
								'1' => 'มกราคม',
								'2' => 'กุมภาพันธ์',
								'3' => 'มีนาคม',
								'4' => 'เมษายน',
								'5' => 'พฤษภาคม',
								'6' => 'มิถุนายน',
								'7' => 'กรกฎาคม',
								'8' => 'สิงหาคม',
								'9' => 'กันยายน',
								'10' => 'ตุลาคม',
    							'11' => 'พฤศจิกายน',
    							'12' => 'ธันวาคม'];
    
    static protected $status_mapping = ['A'=>'ใช้งาน',
    							'C'=>'ไม่ใช้งาน',
    							'Y'=>'ใช่',
    							'N'=>'ไม่ใช่',
    							'NP_A' => 'ปกติ',
    							'NP_S' => 'เปิดบันทึกย้อนหลัง',
    							'NP_C' => ' ปิดบันทึกย้อนหลัง',
    							'ใช้งาน'=>'A',
    							'ไม่ใช้งาน'=>'C',
    							'ใช่'=>'Y',
    							'ไม่ใช่'=>'N'];
    
    static protected $option_initial = [''=>'กรุณาเลือก'];
    
    static public function dtmFormatter($input, $format){
        return date_format(date_create($input), $format);
    }
    
    static public function getOptionsMonth($already_selected_value='', $initOption = true){
        $already_selected_value = (empty($already_selected_value)?date('m'):$already_selected_value);
        $monthArr = self::$months;
        if (!$initOption) {
        	$monthArr =  \yii\helpers\ArrayHelper::remove(self::$months, '');
        }
        foreach (self::$months as $key=>$value) {
        	echo '<option value="'.$key.'"'.($key == $already_selected_value ? ' selected="selected"' : '').'>'.$value.'</option>';
        }
    }
    static public function getOptionsYears($already_selected_value='', $initOption = true){
        $already_selected_value = (empty($already_selected_value)?(intval(date('Y'))+543):$already_selected_value);
        $earliest_year = 2010;
        if($initOption) echo '<option value="">กรุณาเลือก</option>';
        foreach (range(date('Y'), $earliest_year) as $x) {
            $x = intval($x)+543;
            echo '<option value="'.$x.'"'.($x == $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
        }
    }
    /*static public function getArrYears(){
    	$years = array();
    	$earliest_year = 2010;
    	array_push($years, ['' => 'กรุณาเลือก']);
    	foreach (range(date('Y'), $earliest_year) as $x) {
    		$x = intval($x)+543;
    		array_push($years, [$x=>$x]);
    	}
    	return $years;
    }*/
    static public function getArrYears($initOption = true){
    	$years = array();
    	$earliest_year = 2010;
    	if($initOption) $years[''] = 'กรุณาเลือก';
    	foreach (range(date('Y'), $earliest_year) as $x) {
    		$x = intval($x)+543;
    		$years[$x] = $x;
    	}
    	return $years;
    }
    static public function getArrMonth(){
    	return self::$months;
    }
    static public function getOptionsQuaters($already_selected_value=''){
        $already_selected_value = (empty($already_selected_value)?'1':$already_selected_value);
        foreach (array(1,2,3,4) as $x) {
            echo '<option value="'.$x.'"'.($x == $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
        }
    }
    static public function checkDirectory($directory) {
		$flag = false;
	
		// check directory existing
		if ( is_dir($directory) ) {
			$flag = true;
		} else {
			Yii::trace("not have directory");
	
			if ( mkdir($directory) ) {
				Yii::trace("make directory complete");
	
				#Ref : http://php.net/manual/en/function.chmod.php
				// Changes file mode
				// Read and write for owner, read for everybody else
				if ( chmod($directory, 0744) ) {
				Yii::trace("set permission complete");
							
				$flag = true;
				} else {
				Yii::trace("set permission fail");
				}// if else
			} else {
				Yii::trace("make directory fail");
			}// if else
				}// if else
	
				return $flag;
	}// checkDirectory

	static public function getMonths()
	{
		return Utils::$months;
	} 
	
	static public function app_die($msg){
		throw new Exception($msg);
	}
	
	static public function getStatus($key){
		return self::$status_mapping[$key];
	}
	
	static public function getStatuses($keys){
		$objs = [];
		for($i=0; $i<=count($keys); $i++){
			$objs[$key[$i]] = self::$status_mapping[$key[$i]];
		}
		return $objs;
	}
	
	static public function isGuest(){
	    try{
	        //echo '<script type="text/javascript">alert("'.Yii::$app->user->identity->IS_LDAP_AUTHEN.'");</script>'; exit();
	        //if(!isset(Yii::$app->user->identity->IS_LDAP_AUTHEN)) throw new Exception('Non authen');
	        
	        //return !Yii::$app->user->identity->IS_LDAP_AUTHEN || Yii::$app->user->isGuest;
	        //return !false && Yii::$app->user->isGuest;
	        
	        /*if(Yii::$app->user->identity->IS_LDAP_AUTHEN){
	            return false;
	       }else */if(!Yii::$app->user->isGuest){
	           return false;
	        }else{
	            return true;
	        }
	        
	    }catch (Exception $e){
	        return true;
	    }
	}
	
	static public function getDropDownList($model_name, $name, $val_field, $txt_field, $where_cause, $defaul_val, $options){
		$model = '\\app\\models\\'.$model_name;
		$opt_elms = \yii\helpers\ArrayHelper::merge(self::$option_initial,
				\yii\helpers\ArrayHelper::map($model::find()->where($where_cause)->all(),
						$val_field,
						$txt_field));
		
		$elm_options = [
				'id' => $name,
				'class' => 'form-control'
		];
		$elm_options = \yii\helpers\ArrayHelper::merge($elm_options, $options);
		
		return \yii\helpers\Html::dropDownList($name,
				$defaul_val,
				$opt_elms,
				$elm_options);
	}
	
	static public function getDDLProvince($form, $model, $name, $val_field, $txt_field, &$id_fromparam, $user_info, $options){
		$province_where = [];
		$p_arrPleaseSelect = self::$option_initial;
		if(!empty($user_info->PROVINCE_CODE)){
			$province_where['PROVINCE_CODE'] = $user_info->PROVINCE_CODE;
			$id_fromparam = (empty($province)?$user_info->PROVINCE_CODE:$id_fromparam);
			$p_arrPleaseSelect=[];
		}
		if(!empty($form)){
			$p_arrPleaseSelect=[];
		}
		$arr_provinces = \yii\helpers\ArrayHelper::merge($p_arrPleaseSelect,
				\yii\helpers\ArrayHelper::map(\app\models\WA_PROVINCE::find()->where($province_where)->all(),
						$val_field,
						$txt_field));
		
		$elm_options = [
						'id' => $name,
						'class' => 'form-control'
				];
		$elm_options = \yii\helpers\ArrayHelper::merge($elm_options, $options);
		
		if(empty($form)){
			return \yii\helpers\Html::dropDownList($name,
					$id_fromparam,
					$arr_provinces,
					$elm_options);
		}else{
			return $form->field($model, $name)->dropDownList($arr_provinces, $options) ;
		}
	}
	
	static public function getDDLProvince_AutoComplete($form, $model, $name, $val_field, $txt_field, &$id_fromparam, $user_info, $options){
		$province_where = [];
		$p_arrPleaseSelect = self::$option_initial;
		if(!empty($user_info->PROVINCE_CODE)){
			$province_where['PROVINCE_CODE'] = $user_info->PROVINCE_CODE;
			$id_fromparam = (empty($province)?$user_info->PROVINCE_CODE:$id_fromparam);
			$p_arrPleaseSelect=[];
		}
		if(!empty($form)){
			$p_arrPleaseSelect=[];
		}
		$arr_provinces = \yii\helpers\ArrayHelper::merge($p_arrPleaseSelect,
				\yii\helpers\ArrayHelper::map(\app\models\WA_PROVINCE::find()->where($province_where)->all(),
						$val_field,
						$txt_field));
		
		$elm_options = [
						'id' => self::prepairElmID($name)
				];
		$elm_options = \yii\helpers\ArrayHelper::merge($elm_options, $options);
		
		\Yii::$app->view->registerJs('jQuery("#'.self::prepairElmID($name).'").removeClass(\'form-control\').select2();');
		
		if(empty($form)){
			return \yii\helpers\Html::dropDownList($name,
					$id_fromparam,
					$arr_provinces,
					$elm_options);
		}else{
			if(empty($model->$val_field)){
				$model->$val_field = $id_fromparam;
			}
			return $form->field($model, $name)->dropDownList($arr_provinces, $elm_options);
		}
	}
	
	static public function getDDLAmphoe($form, $model, $name, $val_field, $txt_field, &$id_fromparam, $user_info, $province, $options){
		$province = ($province)? $province: '10'; // default to Bangkok province
		$amphoe_where = ['PROVINCE_CODE'=> $province];
		$a_arrPleaseSelect = self::$option_initial;
		if(!empty($user_info->AMPHOE_CODE)){
			$amphoe_where['AMPHOE_CODE'] = $user_info->AMPHOE_CODE;
			$id_fromparam = (empty($id_fromparam)?$user_info->AMPHOE_CODE:$id_fromparam);
			$a_arrPleaseSelect = [];
		}
		
		if(!empty($form)){
			$a_arrPleaseSelect=[];
		}
		
		if(empty($province)){
			$arr_amphoe = $a_arrPleaseSelect;
		}else{
			$arr_amphoe = \yii\helpers\ArrayHelper::merge($a_arrPleaseSelect,
					\yii\helpers\ArrayHelper::map(\app\models\WA_AMPHOE::find()->where($amphoe_where)->all(),
							$val_field,
							$txt_field));
		}
		$elm_options = [
				'id' => $name,
				'class' => 'form-control'
		];
		$elm_options = \yii\helpers\ArrayHelper::merge($elm_options, $options);
		
		if(empty($form)){
			return \yii\helpers\Html::dropDownList($name,
					$id_fromparam,
					$arr_amphoe,
					$elm_options);
		}else{
			return $form->field($model, $name)->dropDownList($arr_amphoe, $options) ;
		}
	}

	static public function getDDLAmphoe_AutoComplete($form, $model, $name, $val_field, $txt_field, &$id_fromparam, $user_info, $province, $options){
		$province = ($province)? $province: '10'; // default to Bangkok province
		$amphoe_where = ['PROVINCE_CODE'=> $province];
		$a_arrPleaseSelect = self::$option_initial;
		if(!empty($user_info->AMPHOE_CODE)){
			$amphoe_where['AMPHOE_CODE'] = $user_info->AMPHOE_CODE;
			$id_fromparam = (empty($id_fromparam)?$user_info->AMPHOE_CODE:$id_fromparam);
			$a_arrPleaseSelect = [];
		}
	
		if(!empty($form)){
			$a_arrPleaseSelect=[];
		}
	
		if(empty($province)){
			$arr_amphoe = $a_arrPleaseSelect;
		}else{
			$arr_amphoe = \yii\helpers\ArrayHelper::merge($a_arrPleaseSelect,
					\yii\helpers\ArrayHelper::map(\app\models\WA_AMPHOE::find()->where($amphoe_where)->all(),
							$val_field,
							$txt_field));
		}
		$elm_options = [
				'id' => self::prepairElmID($name)
		];
		$elm_options = \yii\helpers\ArrayHelper::merge($elm_options, $options);
		
		\Yii::$app->view->registerJs('jQuery("#'.self::prepairElmID($name).'").removeClass(\'form-control\').select2();');
	
		if(empty($form)){
			return \yii\helpers\Html::dropDownList($name,
					$id_fromparam,
					$arr_amphoe,
					$elm_options);
		}else{
			if(empty($model->$val_field)){
				$model->$val_field = $id_fromparam;
			}
			return $form->field($model, $name)->dropDownList($arr_amphoe, $elm_options) ;
		}
	}
	
	static public function toDate($date){
		return new \yii\db\Expression('TO_DATE(\''.$date.'\',\'DD/MM/YYYY\')');
	}
	
	static public function getOracleErrorMsg($erroCode) 
	{
		$msg = '';

		switch ($erroCode) {
			case '1':
				// http://www.dba-oracle.com/sf_ora_00001_unique_constraint_violated.htm
				$msg = 'ไม่สามารถบันทึกข้อมูลที่ซ้ำกันได้';
				break;
			case '2292': 
				// http://www.dba-oracle.com/t_ora_02292_constraint_violation_child_record_found.htm
				$msg = 'กรุณาลบข้อมูลที่เกี่ยวข้อง กับข้อมูลนี้ก่อน';	
				break;
				default:
				$msg = 'เกิดข้อผิดพลาด กรุณาติดต่อผู้ดูแลระบบ';
		}

		return $msg;
	}
	
	static public function prepairElmID($name){
		return str_replace('.', '-', $name);
	}

	static public function setErrorFlashMessage($errors) 
	{
		$errMsg = '';
        foreach ($errors as $key => $value) {
            if ($errMsg) {
                $errMsg .=  ', '.$key.' '.$value[0];
            } else {
                $errMsg .=  $key.' '.$value[0];
            }
        }

        Yii::$app->getSession()->setFlash('error', $errMsg);
	}

	static public function uploadFile($options)
    {
    	$file = $_FILES[$options['file_name']];
    	$fileName = '';
    	
    	$year = empty($options['year'])?'':'/'.$options['year'];
    	$image_path = 'images'.$options['image_path'].$year;
    	
    	switch ($file['error']) {
	        case '0':
	        	$fileName = $file["name"];

	            $splitFileName = explode(".", $fileName);
	            $extensionFile = ".".$splitFileName[count($splitFileName)-1];
	            $fileName = $options['id'].date('-Ymd-His').$extensionFile;
	            Yii::trace('image_path = '.$image_path.'  fileName = '.$fileName, 'debug');
	            
	            if ( self::checkDirectory($image_path) ) {
	                if ( move_uploaded_file($_FILES[$options['file_name']]["tmp_name"]    // temp_file
	                        , $image_path."/".$fileName) ) { // path file
	                    //Successssssssssss.
	                } else {
	                    throw new Exception('Can\'t save image');
	                }
	            } else {
	                throw new Exception('Can\'t create directory for save image');
	            }
	        	break;
	        // http://php.net/manual/en/features.file-upload.errors.php
	        case '1':
	        	throw new Exception('The uploaded file exceeds the upload_max_filesize.');
	        	break;
	        case '2':
	        	throw new Exception('The uploaded file exceeds the MAX_FILE_SIZE.');
	        	break;
	        case '3':
	        	throw new Exception('The uploaded file was only partially uploaded.');
	        	break;
	        case '4':
	        	break;
	        case '6':
	        	throw new Exception('Missing a temporary folder.');
	        	break;
	        case '7':
	        	throw new Exception('Failed to write file to disk.');
	        	break;
	        case '8':
	        	throw new Exception('A PHP extension stopped the file upload.');
	        	break;
	        default:
	        	throw new Exception('Error code has not equals 0.');
	    }
    	
    	return empty($fileName)?$fileName:$options['image_path'].$year."/".$fileName;
    }
    static public function adjustImagePath($imagepath){
    	return str_replace('//','/','images/'.$imagepath);
    }

    static public function isCurrentMonth($year, $month, $buddhist)
    {
		$result = false;
		$currentYear = date('Y');
		$currentMonth = date('m');
		if ($buddhist) {
			$currentYear += 543;
		}

		if ($currentYear == $year && $currentMonth == $month) {
			$result = true;
		}

		return $result;
    }
}