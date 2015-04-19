<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use yii\web\Authentication;
use app\models\NP_STAFF;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
AppAsset::register($this);

$form = ActiveForm::begin([
    'id' => 'npStaffForm',
    'layout' => 'horizontal',
    'action' => Url::to(['npstaff/save']),
    'options' => ['enctype'=>'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => '',
            'offset' => '',
            'wrapper' => '',
            'error' => '',
            'hint' => '',
        ],
    ],
]);

$model = $npStaff;
$mode = (empty($id)?'add':'edit'); //add, edit.
$isEditMode = !empty($npStaff);

$user_info = Authentication::getAuthenInfo();

$province = "";
$amphoe = "";

if($mode=='add'){
	$optionsDDL = ['class' => 'form-control'];
}else if ($mode=='edit') {
  $province = $model->ORG_PROVINCE_CODE;
  $amphoe = $model->ORG_AMPHOE_CODE;
	$optionsDDL = ['class' => 'form-control',
			'disabled' => 'disabled'];
}else{
	$optionsDDL = [];
}

?>

<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER1'); ?></h3>
  </div>
  <div class="panel-body">
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('FIRST_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'FIRST_NAME')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('LAST_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'LAST_NAME')->textInput() ; ?>  </div>  
            
            <div class="col-md-2 right" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('YEAR'); ?></label></div>
            <div class="col-md-2" >
              <?= $form->field($model, 'YEAR', ['options' => ['class' => '']])
                        ->dropDownList(Utils::getArrYears(false),
                          ['class' => 'form-control']); ?>
            </div>  
      </div>
      
      <div class="form-group">
            <label class="col-md-2 right" style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_TYPE'); ?></label>
            <div class="col-md-10">
              <?php 
                    $orgTypeList = array('1' => 'สำนักการท่องเที่ยว', '2' => 'ที่ว่าการอำเภอ');
                    
                    echo $form->field($model, 'ORG_TYPE')
                                ->radioList($orgTypeList, 
                                            [
                                                'class' => 'radio clearfix',
                                                'item' => function ($index, $label, $name, $checked, $value) use($mode){
                                                                return Html::radio($name, $checked, [
                                                                   'value' => $value,
                                                                   'label' => $label,
                                                                   'labelOptions' => ['class' => 'col-md-3'],
                                                                   'class' => 'form',
                                                                   (($mode == 'edit')? 'disabled': 'undisabled') => '',
                                                                   'onchange' => 'orgTypeChange(this)',
                                                                ]);
                                                            },
                                            ]);
                ?>
                <!-- <label><input name="orgType" type="radio" value="1"/>&nbsp;สำนักการท่องเที่ยว&nbsp;
			           <input name="orgType" type="radio" value="2"/>&nbsp;ที่ว่าการอำเภอ : 
                </label> -->     
            </div>
       </div>
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php 
            	//echo $form->field($model, 'ORG_PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this,\'ORG\')']) ; 
            	
            echo Utils::getDDLProvince($form, $model, 'ORG_PROVINCE_CODE',
            		'PROVINCE_CODE',
            		'PROVINCE_NAME_TH',
            		$province,
            		$user_info,
            		ArrayHelper::merge(['onchange' => 'Common.ajax.onChangeProvince(this, \'NP_STAFF[ORG_AMPHOE_CODE]\', \''.$amphoe.'\')'], $optionsDDL)
            );
            
            	?>  </div>
            
            <div class="col-md-1 col-org-amphoe-code" style="text-align:right;">  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3 col-org-amphoe-code">  <?php 
            //echo $form->field($model, 'ORG_AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; 
            
            echo Utils::getDDLAmphoe($form, $model, 'ORG_AMPHOE_CODE',
            		'AMPHOE_CODE',
            		'AMPHOE_NAME_TH',
            		$amphoe,
            		$user_info,
            		$province,
            		$optionsDDL
            );
            
            ?>  </div>
       </div>   
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_TEL_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'ORG_TEL_NO')->textInput() ; ?>  </div>
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_TEL_NO_EXT'); ?></label> </div>
            <div class="col-md-1"><?php echo $form->field($model, 'ORG_TEL_NO_EXT')->textInput() ; ?>  </div>    
       </div>
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_FAX_NO'); ?></label> </div>
            <div class="col-md-2"> <?php echo $form->field($model, 'ORG_FAX_NO')->textInput() ; ?>  </div>  
       </div>
       <br>
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><u>ที่อยู่ปัจจุบัน</u></label> </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_HOUSE_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PRESENT_HOUSE_NO')->textInput() ; ?>  </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_VILLAGE_NO'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PRESENT_VILLAGE_NO')->textInput() ; ?>  </div> 
       </div>  
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_VILLAGE_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PRESENT_VILLAGE_NAME')->textInput() ; ?>  </div>    
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_ROAD'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PRESENT_ROAD')->textInput() ; ?>  </div>  
       </div>
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PRESENT_PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this,\'PRESENT\')']) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PRESENT_AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; ?>  </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_TAMBOL_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PRESENT_TAMBOL_CODE')->textInput() ; ?>  </div>  
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRESENT_POST_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PRESENT_POST_CODE')->textInput() ; ?>  </div>  
       </div>
       
       
       <br>
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><u>ที่อยู่ตามทะเบียนบ้าน</u></label> </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_HOUSE_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PERM_HOUSE_NO')->textInput() ; ?>  </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_VILLAGE_NO'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PERM_VILLAGE_NO')->textInput() ; ?>  </div> 
       </div>  
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_VILLAGE_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PERM_VILLAGE_NAME')->textInput() ; ?>  </div>    
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_ROAD'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PERM_ROAD')->textInput() ; ?>  </div>  
       </div>
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PERM_PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this,\'PERM\')']) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PERM_AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; ?>  </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_TAMBOL_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PERM_TAMBOL_CODE')->textInput() ; ?>  </div>  
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PERM_POST_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'PERM_POST_CODE')->textInput() ; ?>  </div>  
       </div>
       
       <br>
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TEL_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'TEL_NO')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MOBILE_NO'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'MOBILE_NO')->textInput() ; ?>  </div>    
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" ><label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BIRTH_DATE'); ?></label> </div>
            <div class="col-md-2" style="text-align:left;">
              <?php 
                echo $form->field($model, 'BIRTH_DATE')
                          ->widget(Datepicker::classname(),
                                  [
                                  'clientOptions' => [
                                                      'changeYear' => 'true',
                                                      'changeMonth' => 'true',
                                                      'yearRange' => '-50:+50',
                                                      ]
                                  ]);
              ?>
            </div>   
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AGE'); ?></label> </div>
            <div class="col-md-2" style="text-align:left;"> <label style="padding-top:8px;"><a id='showage' style="text-align:ceneter;">-</a><a>&nbspปี</a></label></div>   
      </div>
      
      </br>
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ID_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'ID_NO')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ID_NO_PLACE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'ID_NO_PLACE')->textInput() ; ?>  </div>    
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ID_NO_EXPIRE_DATE'); ?></label> </div>
            <div class="col-md-2" style="text-align:left;">
              <?php 
                echo $form->field($model, 'ID_NO_EXPIRE_DATE')
                          ->widget(Datepicker::classname()); 
              ?>
            </div>   
            
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('NATIONLITY'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'NATIONLITY')->textInput() ; ?>  </div>  
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RACE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'RACE')->textInput() ; ?>  </div>  
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RELIGIOUS'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'RELIGIOUS')->textInput() ; ?>  </div>  
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('WEIGHT'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'WEIGHT')->textInput() ; ?></div>  
            <div class="col-md-1" style="text-align:left;">  <label style="padding-top:8px;"> กิโลกรัม  </label></div>  
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('HEIGHT'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'HEIGHT')->textInput() ; ?></div>  
            <div class="col-md-1" style="text-align:left;">  <label style="padding-top:8px;"> เซนติเมตร  </label></div>  
      </div>

      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BLOOD_GROUP'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BLOOD_GROUP')->textInput() ; ?></div>  
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MARITAL_STATUS'); ?></label> </div>
            <div class="col-md-5" >
                <?php
                  $maritalList = ['1' => 'โสด', '2' => 'สมรส', '3' => 'หย่า', '4' => 'หม้าย'];
                  echo $form->field($model, 'MARITAL_STATUS')
                            ->radiolist(
                                            $maritalList,
                                            [
                                            'class' => 'checkbox',
                                            'item' => function ($index, $label, $name, $checked, $value){
                                                      return Html::radio($name, $checked, [
                                                         'value' => $value,
                                                         'label' => $label,
                                                         'labelOptions' => ['class' => 'col-md-3'],
                                                         'class' => '',
                                                      ]);
                                                  }
                                            ]
                                          );
                ?>
                 <!-- <label><input name="MARITAL_STATUS" type="radio" value="1"/>&nbsp;โสด
			           <input name="MARITAL_STATUS" type="radio" value="2"/>&nbsp;สมรส
			           <input name="MARITAL_STATUS" type="radio" value="3"/>&nbsp;หย่า</label> 
			           <label><input name="MARITAL_STATUS" type="radio" value="4"/>&nbsp;หม้าย</label>  -->
            </div>
      </div>
      
  </div>
  
</div>

<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER2'); ?></h3>
  </div>
  <div class="panel-body">
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><u>ปริญญาตรี</u></label> </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BECH_INSTITUTE_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BECH_INSTITUTE_NAME')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BECH_MAJOR'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'BECH_MAJOR')->textInput() ; ?>  </div>    
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BECH_YEAR_BEGIN'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BECH_YEAR_BEGIN')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BECH_YEAR_FINISH'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BECH_YEAR_FINISH')->textInput() ; ?>  </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BECH_GRADE'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'BECH_GRADE')->textInput() ; ?>  </div>  
      </div>
      
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><u>ปริญญาโท</u></label> </div>
       </div>    
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MAST_INSTITUTE_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'MAST_INSTITUTE_NAME')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MAST_MAJOR'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'MAST_MAJOR')->textInput() ; ?>  </div>    
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MAST_YEAR_BEGIN'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'MAST_YEAR_BEGIN')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MAST_YEAR_FINISH'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'MAST_YEAR_FINISH')->textInput() ; ?>  </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MAST_GRADE'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'MAST_GRADE')->textInput() ; ?>  </div>  
      </div>
  </div>
  
</div>

<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER3'); ?></h3>
  </div>
  <div class="panel-body">
     <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_FIRST_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_FIRST_NAME')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_LAST_NAME'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_LAST_NAME')->textInput() ; ?>  </div>    
      </div>
      
      <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_PHONE_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_PHONE_NO')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_RELATE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_RELATE')->textInput() ; ?>  </div>    
      </div>
      
      <br>
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><u>ที่อยู่ตามทะเบียนบ้าน</u></label> </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_HOUSE_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_HOUSE_NO')->textInput() ; ?>  </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_VILLAGE_NO'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_VILLAGE_NO')->textInput() ; ?>  </div> 
       </div>  
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_VILLAGE_NAME'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_VILLAGE_NAME')->textInput() ; ?>  </div>    
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_ROAD'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_ROAD')->textInput() ; ?>  </div>  
       </div>
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this,\'CONT\')']) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; ?>  </div>
       </div>   
       
       <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_TAMBOL_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONT_TAMBOL_CODE')->textInput() ; ?>  </div>  
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONT_POST_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'CONT_POST_CODE')->textInput() ; ?>  </div>  
       </div>
  </div>
</div>

<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER4'); ?></h3>
  </div>
  <div class="form-group" style="margin-bottom: 15px">
			<?= Html::activeLabel($model, 'IMAGE_PATH', ['class'=>'col-sm-3 control-label']); ?>
			<div class="col-sm-4">
		        
				<?php
					if($mode=='edit'){
						echo Html::img(Utils::adjustImagePath($model->IMAGE_PATH), ['id'=>'img_viewer', 'class'=>'cursor-hand', 'width'=>'100', 'height'=>'100', 
							'onclick'=>'Common.bootstrap.modal.displayImg(this, '.$model->STAFF_ID.')',
								
							'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
					}else{
						//echo '<input type="file" class="form-control" name="IMAGE_FILE" />';
            echo $form->field($model, "IMAGE_PATH")->fileInput(["name" => "IMAGE_PATH", "class" => "form-control"]);
					}
				?>
		    </div>
    </div>    
</div>

<div class="footcontentbutton">
    <?=$form->field($model, 'STAFF_ID')->hiddenInput()?>

    <input type="hidden" name="mode" value="<?=$mode?>" />
    
    <?php 
    	foreach($backAction as $key=>$value){
			echo '<input type="hidden" name="return['.$key.']" value="'.$value.'" />';
		}
    ?>
    <a onclick="jQuery(this).closest('form').submit()" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
</div>  

<?php ActiveForm::end() ?>

<!-- Modal -->
<div class="modal fade" id="modalUploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin: 30px auto;">
		<?php $formUploadFile = ActiveForm::begin([
													    'id' => 'formUploadImage',
													    'layout' => 'horizontal',
													    'action' => ''
													]); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Upload file</h4>
				</div>
				<div class="modal-body">
					<div class="row">
					    <div class="col-md-offset-2 col-md-8"></div>
	  					<div class="col-md-offset-2 col-md-8">
	  						<a href="#" class="thumbnail">
								<?= Html::img( 'images'.$this->context->imagePath.'/no.jpg' ,['id' => 'reviewImage'] ); ?>
							</a>
						</div>
					</div>
					<div class="row">
	  					<div class="col-md-offset-2 col-md-8">
	  						<?php
	  							echo Html::fileInput('fileToUpload', null, ['id' => 'fileToUpload','class' => '']); 
	  						?>
	  						<input type="hidden" id="fileuploadid" name="id" value="" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?php 
						echo Html::button('<span class="glyphicon glyphicon-remove"></span> Close', 
						 					['class' => 'btn btn-danger',
							 					'data-dismiss' => 'modal']);
						echo Html::button('<span class="glyphicon glyphicon-floppy-disk"></span> Upload', 
						 					['id' => 'btnUploadFile',
							 					'class' => 'btn btn-success']);
					?>
				</div>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

<script type="text/javascript">
  // $(document).on('submit', 
  // '#npStaffForm', 
  // function(event){
  //   var $this = $(this);
  //   var $reviewImage = $this.find('#img_viewer');
  //   var $imageFile = $this.find('input[name="IMAGE_FILE"]');

  //   if (!$imageFile.val() && !$reviewImage.attr('src')) {
  //     BootstrapDialog.alert('กรุณาเลือกไฟล์ภาพ');
  //     // event.preventDefault();
  //     // return false;
  //   }
  // });

  $(document).ready(function() {
    displayAmphoeList("<?= $model->ORG_TYPE; ?>");
    getAge($("#np_staff-birth_date").val(), true)

    $( "#np_staff-birth_date" ).change(function(event) {
      var dateString = $(this).val();

      if (dateString) {
        getAge(dateString, true);
      }
    });
  });

  function onChangeProvince(t,e){
      var province_id=t.value;
      var data = {province : province_id};
      
      if(province_id!=''){
          jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
              //on ajax success. 
              //console.log(data);
              var jElm = '';
              
              switch(e){
          	case 'ORG':
          	    jElm = jQuery('#np_staff-org_amphoe_code');
          		break;
              case 'PRESENT':
          	    jElm = jQuery('#np_staff-present_amphoe_code');
          		break;
          	case 'PERM':
          	    jElm = jQuery('#np_staff-perm_amphoe_code');
          		break;
          	case 'CONT':
          	    jElm = jQuery('#np_staff-cont_amphoe_code');
          		break;
          	default:
          		break;
              }
              
              jElm.find('option').remove();
  //             console.log(jElm);
              jQuery.each(data, function(i, row){
                  jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
              });
              jElm.change();
          }, 'json');
      }
  }

  function onClickSave(){
      var nameth = jQuery('#nameth').val();
      
      var data = {nameth: nameth};
      
      jQuery.post('<?=Url::to(['npstaff/save']);?>', data, function(data){
          //on ajax success.
          alert('Success');
          
      }, 'json');
      
  }

  jQuery('#fileToUpload').change(function() {
  	reviewImage(this, 'reviewImage');
  });

  jQuery('#btnUploadFile').click(function() {
  	var url2 = "<?= Url::to(['npstaff/uploadfile']); ?>";
	var year = jQuery('[name="NP_STAFF[YEAR]"]').val();
  	Common.ajax.ajaxFileUploadCustomParams(
  	        {
  	            url: url2, 
  	            fileSelector: '#fileToUpload',
  	            params: {
  	                id: jQuery('#fileuploadid').val(),
  	                year: year
  	            },
  	            onSuccess: function(data, staus) {
	 	     		jQuery('#img_viewer').attr('src', data.imagePath);
	 	     	    jQuery('#modalUploadFile').modal('hide');
  	            }
  	        });
  	
//   	Common.ajax.ajaxFileUpload(url2, '#fileToUpload', jQuery('#fileuploadid').val(), function(data){

//   		jQuery('#img_viewer').attr('src', data.imagePath);
//   	    jQuery('#modalUploadFile').modal('hide');
//   	});

  });

  function reviewImage(input, container) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function (e) {
              jQuery('#' + container).attr('src', e.target.result);
          }
          
          reader.readAsDataURL(input.files[0]);
      } else {
      	 jQuery('#' + container).attr('src', '<?= Url::to('@web/images/no_image_available.jpg'); ?>');
      }
  }

  function orgTypeChange(obj) {
    var orgType = $(obj).val();

    displayAmphoeList(orgType);
  }

  function displayAmphoeList(orgType) {
    if (orgType == "1") {
      $(".col-org-amphoe-code").hide();
    } else if (orgType == "2") {
      $(".col-org-amphoe-code").show();
    }
  }

  function getAge(dateString, isBuddhist) {
    var splitDate = dateString.split('/'); // format => dd/mm/yyyy
    var today = new Date();	
    var age = 0;
    if (isBuddhist) {
      age = (today.getFullYear() + 543) - splitDate[2];
    } else {
      age = today.getFullYear() - splitDate[2];
    }


    // getMonth() start at 0
    var m = (today.getMonth() + 1) - splitDate[1];

    if (m < 0 || (m === 0 && today.getDate() < splitDate[0])) {
        age--;
    }
    
    if(isNaN(age)) {
      $("#showage").text(0);
    } else {
      $("#showage").text(age);
    }
  }
</script>