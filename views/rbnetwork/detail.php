<?php
    use app\assets\AppAsset;
    use himiklab\jqgrid\JqGridWidget;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\jui\DatePicker;
    use yii\helpers\ArrayHelper;
    use yii\web\Utils;
    use app\models\RB_NETWORK;
    use app\models\WA_PROVINCE;
    use app\models\WA_AMPHOE;
    AppAsset::register($this);



    /*$form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
    ]) ; */

    $form = ActiveForm::begin([
        'id' => 'rbNetworkForm',
        'layout' => 'horizontal',
        'action' => Url::to(['rbnetwork/save']),
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

    // $model = new \app\models\CONST_PROJ ;
    $model = $rbNetwork;
    $mode = (empty($id)?'add':'edit'); //add, edit.
    $isEditMode = !empty($rbNetwork);
    //print_r($rbNetwork) ;
    if($isEditMode){
    	$model->RB_ACT_1_FLAG = ($model->RB_ACT_1_FLAG=='Y'?1:0);
    	$model->RB_ACT_2_FLAG = ($model->RB_ACT_2_FLAG=='Y'?1:0);
    	$model->RB_ACT_3_FLAG = ($model->RB_ACT_3_FLAG=='Y'?1:0);
    	$model->RB_ACT_4_FLAG = ($model->RB_ACT_4_FLAG=='Y'?1:0);
    	$model->RB_ACT_5_FLAG = ($model->RB_ACT_5_FLAG=='Y'?1:0);
    	$model->RB_ACT_6_FLAG = ($model->RB_ACT_6_FLAG=='Y'?1:0);
    	$model->RB_ACT_7_FLAG = ($model->RB_ACT_7_FLAG=='Y'?1:0);
    	$model->RB_ACT_8_FLAG = ($model->RB_ACT_8_FLAG=='Y'?1:0);
    	$model->RB_ACT_9_FLAG = ($model->RB_ACT_9_FLAG=='Y'?1:0);
    	
    }
?>
<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> เครือข่ายนันทนาการ กรมพลศึกษา</h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('NETWORK_NAME'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'NETWORK_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AMOUNT'); ?></label> </div>
            <div class="col-md-1" >   <?php echo $form->field($model, 'AMOUNT')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('SLOGAN'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'SLOGAN')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('IMAGE_DESC'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'IMAGE_DESC')->textInput(['maxlength' => 100]) ; ?>  </div>                                       
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PURPOSE'); ?></label> </div>
            <div class="col-md-9" >   <?php echo $form->field($model, 'PURPOSE')->textInput(['maxlength' => 100]) ; ?>  </div>                                                             
        </div>          
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ACT_DESC'); ?></label> </div>
            <div class="col-md-9" >   <?php echo $form->field($model, 'ACT_DESC')->textInput(['maxlength' => 100]) ; ?>  </div>                                                             
        </div>    
    </div> 
</div>      

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> กิจกรรมนันทนาการหลัก (เลือกได้มากกว่า 1 ข้อ) </h3>
  </div>
    <div class="panel-body">
        <div class="form-group">
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_1_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_1_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_2_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_2_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_3_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_3_FLAG'); ?></label> </div>  
        </div>
        <div class="form-group">
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_4_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_4_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_5_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_5_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_6_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_6_FLAG'); ?></label> </div>  
        </div>
        <div class="form-group">
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_7_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_7_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_8_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-2"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_8_FLAG'); ?></label> </div>  
            
            <div class="col-md-1" style="text-align:right;" > <?php echo $form->field($model, 'RB_ACT_9_FLAG')->checkbox()->label(false) ; ?>   </div>
            <div class="col-md-1"> <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RB_ACT_9_FLAG'); ?></label> </div>  
            <div class="col-md-2"> <?php echo $form->field($model, 'RB_ACT_OTHER_DESC')->textInput(['maxlength' => 100]) ; ?>  </div>  
            
            
        </div>
    </div>           
</div>

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> ประธานเครือข่าย </h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRES_FIRST_NAME'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'PRES_FIRST_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRES_LAST_NAME'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'PRES_LAST_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRES_TEL'); ?></label> </div>
            <div class="col-md-2" >   <?php echo $form->field($model, 'PRES_TEL')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRES_FAX'); ?></label> </div>
            <div class="col-md-1" >   <?php echo $form->field($model, 'PRES_FAX')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PRES_EMAIL'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'PRES_EMAIL')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
  </div> 
</div>

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> ผู้ประสานงานหลัก </h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CO_FIRST_NAME'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'CO_FIRST_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CO_LAST_NAME'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'CO_LAST_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CO_TEL'); ?></label> </div>
            <div class="col-md-2" >   <?php echo $form->field($model, 'CO_TEL')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CO_FAX'); ?></label> </div>
            <div class="col-md-1" >   <?php echo $form->field($model, 'CO_FAX')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CO_EMAIL'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'CO_EMAIL')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
  </div> 
</div>

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> ที่ตั้งเครือข่าย </h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ADDRESS_NO'); ?></label> </div>
            <div class="col-md-1" >   <?php echo $form->field($model, 'ADDRESS_NO')->textInput(['maxlength' => 100]) ; ?>  </div>

            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MOO'); ?></label> </div>
            <div class="col-md-1" >   <?php echo $form->field($model, 'MOO')->textInput(['maxlength' => 100]) ; ?>  </div>

            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ADDRESS_NAME'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'ADDRESS_NAME')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ROAD'); ?></label> </div>
            <div class="col-md-4" >   <?php echo $form->field($model, 'ROAD')->textInput(['maxlength' => 100]) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TUMBOL'); ?></label> </div>
            <div class="col-md-3" >   <?php echo $form->field($model, 'TUMBOL')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this)']) ; ?>  </div>               
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3">  
                <?php 
                    $provinceCode = empty($model->PROVINCE_CODE)? '10': $model->PROVINCE_CODE;
                    echo $form->field($model, 'AMPHOE_CODE')
                            ->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE' => $provinceCode])->all(), 
                                                            'AMPHOE_CODE', 
                                                            'AMPHOE_NAME_TH')); 
                ?>  
            </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('POST_CODE'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'POST_CODE')->textInput() ; ?>  </div>              
        </div>     
        <div class="form-group">  
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('NEAR_PLACE'); ?></label> </div>
            <div class="col-md-9" >   <?php echo $form->field($model, 'NEAR_PLACE')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
      
    </div> 
</div>      

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"> ภาพกิจกรรมเครือข่าย </h3>
  </div>
  <div class="panel-body">
        <div class="form-group" style="margin-bottom: 15px">
			<?= Html::activeLabel($model, 'ACT_IMAGE_PATH', ['class'=>'col-sm-3 control-label']); ?>
			<div class="col-sm-4">
		        
				<?php
					if($mode=='edit'){
						echo Html::img(Utils::adjustImagePath($model->ACT_IMAGE_PATH), ['id'=>'img_viewer', 'class'=>'cursor-hand', 'width'=>'100', 'height'=>'100', 
							'onclick'=>'Common.bootstrap.modal.displayImg(this, '.$model->RB_NETWORK_ID.')',
							'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
					}else{
						echo '<input type="file" class="form-control" name="IMAGE_FILE" />';
					}
				?>
		    </div>
        </div>
      
    </div> 
</div>                  
<div class="footcontentbutton">
    <?=$form->field($model, 'RB_NETWORK_ID')->hiddenInput()?>
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
								<?= Html::img( $this->context->imagePath.'/no.jpg' ,['id' => 'reviewImage'] ); ?>
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

jQuery('#fileToUpload').change(function() {
	reviewImage(this, 'reviewImage');
});

jQuery('#btnUploadFile').click(function() {
	var url2 = "<?= Url::to(['rbnetwork/uploadfile']); ?>";
	
	Common.ajax.ajaxFileUpload(url2, '#fileToUpload', jQuery('#fileuploadid').val(), function(data){

		jQuery('#img_viewer').attr('src', data.imagePath);
	    jQuery('#modalUploadFile').modal('hide');
	});

});

function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            jQuery('#rb_network-amphoe_code').find('option').remove();
            jQuery.each(data, function(i, row){
                jQuery('#rb_network-amphoe_code').append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
            });
        }, 'json');
    }
}

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


function onClickSave(){
    var nameth = jQuery('#nameth').val();
    
    var data = {nameth: nameth};
    
    jQuery.post('<?=Url::to(['rbnetwork/save']);?>', data, function(data){
        //on ajax success.
        alert('Success');
        
    }, 'json');
    
}

    $(document).on('submit', '#rbNetworkForm',
    function(event) {
        var $this = $(this);
        var $imageFile = $this.find('input[name="IMAGE_FILE"]');
        var $imageViewer = $this.find('#img_viewer');

        if (!$imageFile.val() && !$imageViewer.attr('src')) {
            BootstrapDialog.alert('กรุณาเลือกไฟล์');
            event.preventDefault();
        }
    });
</script>