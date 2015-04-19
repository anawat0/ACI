<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use app\models\SSB_CLUB;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
AppAsset::register($this);



/*$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ; */

$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'action' => Url::to(['ssbclub/save']),
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

// $model = new \app\models\CONST_PROJ ;
$model = $ssbClub;
$mode = (empty($id)?'add':'edit'); //add, edit.
$isEditMode = !empty($ssbClub);
if($isEditMode){
	$model->STATUS = ($model->STATUS=='A'?1:0);
}

?>

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER1'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('LEADER'); ?></label> </div>
            <div class="col-md-10" >   <?php echo $form->field($model, 'LEADER')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CLUB_NAME_TH'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'CLUB_NAME_TH')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CLUB_NAME_EN'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'CLUB_NAME_EN')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ESTABLISH_DATE'); ?></label> </div>
            <div class="col-md-2">  
                <?php 
                    echo $form->field($model, 'ESTABLISH_DATE')
                                ->widget(Datepicker::classname()); 
                ?>  
            </div>                            
        </div>

        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TEL'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'TEL')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MOBILE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'MOBILE')->textInput() ; ?>  </div>                              
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('FAX'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'FAX')->textInput() ; ?>  </div>       
      
        </div>    
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('EMAIL'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'EMAIL')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PWD'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PWD')->textInput() ; ?>  </div>
           
        </div>            
        <div class="form-group">            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BUDGET'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BUDGET')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AMOUNT'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'AMOUNT')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('STATUS'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'STATUS')->checkbox()->label(false) ; ?>  </div>   
        </div>     
      
    </div> 
</div>      
    

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER2'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ADDRESS_NAME'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'ADDRESS_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            
            
            
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ADDRESS_NO'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'ADDRESS_NO')->textInput() ; ?>  </div>   
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('SOI'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'SOI')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ROAD'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'ROAD')->textInput() ; ?>  </div>     
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TUMBOL'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'TUMBOL')->textInput() ; ?>  </div>
            
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this)']) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('POST_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'POST_CODE')->textInput() ; ?>  </div>    
        </div>                    
    </div> 
</div>      


<div class="footcontentbutton">
    <?=$form->field($model, 'SSB_CLUB_ID')->hiddenInput()?>
    <input type="hidden" name="mode" value="<?=$mode?>" />
    <!-- <a onclick="window.location='<?=Url::to(['ssbclub/', 'rtparams'=>$rtparams])?>'" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Back</a> -->
    <a onclick="jQuery(this).closest('form').submit()" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
</div>    


<?php ActiveForm::end() ?>
<script type="text/javascript">

function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            jQuery('#ssb_club-amphoe_code').find('option').remove();
            jQuery.each(data, function(i, row){
                jQuery('#ssb_club-amphoe_code').append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
            });
        }, 'json');
    }
}

function onClickSave(){
    var nameth = jQuery('#nameth').val();
    
    var data = {nameth: nameth};
    
    jQuery.post('<?=Url::to(['ssbclub/save']);?>', data, function(data){
        //on ajax success.
        alert('Success');
        
    }, 'json');
    
}
</script>