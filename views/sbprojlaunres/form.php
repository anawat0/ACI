<style>
	.control-label {
		padding-left: 0;
		padding-right: 0;
	}
</style>
<!-- ################################################################################### -->
<?php
	use app\assets\AppAsset;
	use app\models\SB_PROJ_LAUN_TARGET;
	use app\models\SB_PROJ_LAUN_RES;
	use yii\base\Controller;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\BaseArrayHelper;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	use yii\jui\DatePicker;
	use yii\web\Utils;
	use himiklab\jqgrid\JqGridWidget;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");
?>

<?php $form = ActiveForm::begin([
    'id' => 'submitForm',
    'action' => empty($model['SB_PROJ_LAUN_RES_ID'])? Url::to(['sbprojlaunres/save-new']): Url::to(['sbprojlaunres/edit']),
    'layout' => 'horizontal',
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
]); ?>
	<div class="panel panel-primary">        
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER1'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<?= $form->field($model, 'SB_PROJ_LAUN_RES_ID')->hiddenInput(); ?>
				<?= Html::activeLabel($model, 'BUDGET_YEAR', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_YEAR', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrYears(),
													['class' => 'form-control']); ?>
			    </div>
			    <?= Html::activeLabel($model, 'MONTH', ['class'=>'col-sm-1 control-label']); ?>
				<div class="col-sm-2">
			        <?= $form->field($model, 'MONTH', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrMonth(),
													['class' => 'form-control']); ?>
			    </div>
			    <?= Html::activeLabel($model, 'SB_PROJECT_LAUNCH_ID', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	$sbProjLaunsList = BaseArrayHelper::merge($arrPlaseSelect, BaseArrayHelper::map($sbProjLauns, 'SB_PROJ_LAUN_ID', 'sbProj.PROJ_NAME_TH'));

			        	echo $form->field($model, 'SB_PROJECT_LAUNCH_ID', ['options' => ['class' => '']])
			        				->dropDownList($sbProjLaunsList,
													['class' => 'form-control']); 
					?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'PROVINCE_CODE', 
										['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	$provinceList = BaseArrayHelper::merge($arrPlaseSelect, BaseArrayHelper::map($waProvinces, 'PROVINCE_CODE', 'PROVINCE_NAME_TH'));
			        	
			        	echo $form->field($model, 'PROVINCE_CODE', ['options' => ['class' => '']])
			        				->dropDownList($provinceList,
													['class' => 'form-control',
													'onchange' => 'getDDLAmphoeCode()']); 
					?>
			    </div>
			    <?= Html::activeLabel($model, 'AMPHOE_CODE', ['class'=>'col-sm-1 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	$amphoeList = BaseArrayHelper::merge($arrPlaseSelect,
			        										$waAmphoes? BaseArrayHelper::map($waAmphoes, 'AMPHOE_CODE', 'AMPHOE_NAME_TH'): array());
			        	
			        	echo $form->field($model, 'AMPHOE_CODE', ['options' => ['class' => '']])
			        				->dropDownList($amphoeList,
													['class' => 'form-control']); 
					?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'TARGET_FROM', ['class'=>'col-sm-3 control-label']); ?>
			    <?= $form->field($model, 'TARGET_FROM', ['options' => ['class' => 'col-sm-3']])->widget(DatePicker::classname()) ; ?>

				<?= Html::activeLabel($model, 'TARGET_TO', ['label'=>'ถึง', 'class'=>'col-sm-2 control-label']); ?>
			    <?= $form->field($model, 'TARGET_TO', ['options' => ['class' => 'col-sm-3']])->widget(DatePicker::classname()) ; ?>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'ACTUAL_FROM', ['class'=>'col-sm-3 control-label']); ?>
			    <?= $form->field($model, 'ACTUAL_FROM', ['options' => ['class' => 'col-sm-3']])->widget(DatePicker::classname()) ; ?>
  
				<?= Html::activeLabel($model, 'ACTUAL_TO', ['label' => 'ถึง', 'class'=>'col-sm-2 control-label']); ?>
			    <?= $form->field($model, 'ACTUAL_TO', ['options' => ['class' => 'col-sm-3']])->widget(DatePicker::classname()) ; ?>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'TARGET_BUDGET', ['class'=>'col-sm-3 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'TARGET_BUDGET', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>
				<?= Html::activeLabel($model, 'ACTUAL_BUDGET', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'ACTUAL_BUDGET', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'TARGET_SATISFACTION', ['class'=>'col-sm-3 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'TARGET_SATISFACTION', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>
				<?= Html::activeLabel($model, 'ACTUAL_SATISFACTION', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'ACTUAL_SATISFACTION', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'TARGET_PLACE', ['class'=>'col-sm-3 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'TARGET_PLACE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>
				<?= Html::activeLabel($model, 'ACTUAL_PLACE', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'ACTUAL_PLACE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control']); ?>
			    </div>			
			</div>
			<?php if ( !empty($model['SB_PROJ_LAUN_RES_ID']) ) : ?>
				<div class="form-group" style="margin-bottom: 15px">
					<?= Html::activeLabel($model, 'IMAGE_PATH', ['class'=>'col-sm-3 control-label']); ?>
					<div class="col-sm-4">
				        <?= Html::button('Upload File', ['class' => 'btn btn-success', 
				        									'data-toggle' => 'modal',
				        									'data-target' => '#modalUploadFile']); 
						?>
				    </div>
				</div>
			<?php endif ?>
			<div class="form-group">
				<?= Html::activeLabel($model, 'SUM_RESULT', ['class'=>'col-sm-2 control-label']); ?>
		        <?= $form->field($model, 'SUM_RESULT', ['options' => ['class' => 'col-sm-8']])
        				->textarea(['class' => 'form-control', 'rows' => '5']); ?>
        	</div>
        	<div class="form-group">
        		<?= Html::activeLabel($model, 'RECOMM', ['class'=>'col-sm-2 control-label']); ?>
		        <?= $form->field($model, 'RECOMM', ['options' => ['class' => 'col-sm-8']])
		        		->textarea(['class' => 'form-control', 'rows' => '5']); ?>
			</div>
		</div>
	</div>
	<?php if ( !empty($model['SB_PROJ_LAUN_RES_ID']) ) : ?>
		<div class="panel panel-primary">        
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER2'); ?></h3>
			</div>
			<div  class="panel-body">
				<div class="form-group">
					<div class="col-md-12 text-center">
						<?php 
							$sbProjLaunRes = SB_PROJ_LAUN_RES::findOne($model['SB_PROJ_LAUN_RES_ID']);
		        			$sbProjLaunTargets = SB_PROJ_LAUN_TARGET::findAll(['SB_PRJECT_LAUN_ID' => $sbProjLaunRes->sbProjLaun['SB_PROJ_LAUN_ID']]);

							$params = array('sb_proj_laun_res_id' => $model['SB_PROJ_LAUN_RES_ID'], 'sbProjLaunTargets' => $sbProjLaunTargets);
							
							// Ref. http://www.yiiframework.com/doc-2.0/yii-base-controller.html#render()-detail
							echo $this->render('//sbprojlaunrestarget/index', $params);  
						?>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div class="panel panel-primary">
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-12 text-center">
					<?php
					 	echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Save',
					 							 ['class' => 'btn btn-success btn-lg', 'name' => 'submit-button']);
					?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>
<!-- Modal -->
<div class="modal fade" id="modalUploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin: 30px auto;">
		<?php $formUploadFile = ActiveForm::begin([
													    'id' => 'formUploadImage',
													    'layout' => 'horizontal',
													    'options' => ['enctype'=>'multipart/form-data']
													]); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Upload file</h4>
				</div>
				<div class="modal-body">
					<div class="row">
	  					<div class="col-md-offset-2 col-md-8">
	  						<a href="#" class="thumbnail">
								<?= Html::img( empty($model['IMAGE_PATH'])? Url::to('@web/images/no_image_available.jpg'): $model['IMAGE_PATH'],
												['id' => 'reviewImage'] ); ?>
							</a>
						</div>
					</div>
					<div class="row">
	  					<div class="col-md-offset-2 col-md-8">
	  						<?php
	  							echo Html::fileInput('IMAGE_PATH', null, ['id' => 'IMAGE_PATH','class' => '']); 
	  						?>
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
<!-- ######################################################################################## -->
<script type="text/javascript">
	jQuery(function() {
		// jQuery('#IMAGE_PATH').on('change',function() {
		// 	reviewImage(this, 'reviewImage');
		// });

		jQuery('#btnUploadFile').click(function() {
			var url2 = "<?= Url::to(['sbprojlaunres/upload-file']); ?>";
			var sb_proj_laun_res_id = jQuery('#sb_proj_laun_res-sb_proj_laun_res_id').val();

			jQuery.ajaxFileUpload({
							url:url2,
							secureuri:false,
							fileElementId:'IMAGE_PATH',
							dataType: 'json',
							data: {sb_proj_laun_res_id: sb_proj_laun_res_id},
							success: function (data, status){
								// if(typeof(data.error) != 'undefined'){
								// 	if(data.error != ''){
								// 		alert(data.error);
								// 	}else{
								// 		alert(data.msg);
								// 	}
								// }
								BootstrapDialog.alert(data.msg);
								$('#reviewImage').attr('src', data.file_path + '?' + (new Date().getTime()));
							},
							error: function (data, status, e){
								BootstrapDialog.alert(e);
							}
							});
		});
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

	function getDDLAmphoeCode(){
		var url = "<?= Url::to(['common/getddlampore']); ?>";
		var provinceCode = jQuery('#sb_proj_laun_res-province_code').val();

		jQuery.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: { province:provinceCode }
		})
		.done(function(data) {
			var html = '<option value="">กรุณาเลือก</option>';

			jQuery.each(data, function(key, value) {
				html += '<option value="' + value['AMPHOE_CODE'] + '">' + value['AMPHOE_NAME_TH'] + '</option>';
			});

			jQuery('#sb_proj_laun_res-amphoe_code').html(html);
		});
	}

</script>