<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use app\models\NP_ACTIVITY_FORM2_PROVINCE;
use app\models\WA_PROVINCE;
use app\models\NP_WORK_GROUP_ACTIVITY;
use app\models\WA_AMPHOE;
use yii\web\Authentication;

AppAsset::register($this);

$user_info = Authentication::getAuthenInfo();

$where_work = "NP_WORK_GROUP_ID="."'".$work_id."'";

$form = ActiveForm::begin([
    'id' => 'npactivityForm2ProvinceForm',
    'layout' => 'horizontal',
    'action' => Url::to(['npactivityform2province/save']),
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

$model = $npactivity;
$model->STATUS = empty($model->npSpOpenProvince)?
                    $model->STATUS:
                    $model->npSpOpenProvince->STATUS;
$mode = (empty($id)?'add':'edit');
$isEditMode = !empty($npactivity);

if($mode=='add'){
	$model->YEAR = $year;
	$model->MONTH = $month;
	$model->PROVINCE_CODE = $province;
	$model->NP_WORK_GROUP_ACTIVITY_ID = $act_id;

    $optionsDDL = [];
} else if ($mode=='edit') {
    $province = $model->PROVINCE_CODE;

    $optionsDDL = ['disabled' => 'disabled'];
}
?>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('npactivity'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('YEAR'); ?></label> </div>
            <div class="col-sm-2"><?= $form->field($model, 'YEAR', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrYears(),
													$optionsDDL); ?>
			</div> 
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('MONTH'); ?></label> </div>
 			<div class="col-sm-2"><?= $form->field($model, 'MONTH', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrMonth(),
													$optionsDDL); ?>
			</div> 

            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo "จังหวัด" ?></label> </div>
            <div class="col-md-2">  
                <?php 
                echo Utils::getDDLProvince_AutoComplete($form, $model, 'PROVINCE_CODE',
                		'PROVINCE_CODE',
                		'PROVINCE_NAME_TH',
                		$province,
                		$user_info,
                		$optionsDDL
                );

                ?>  
            </div>       
        </div>
        <div class="form-group" style="padding-bottom: 15px;">
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('START_DATE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'START_DATE')
                                                    ->widget(Datepicker::classname()); ?></div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('END_DATE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'END_DATE')
                                                    ->widget(Datepicker::classname()); ?></div>                              
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('SUBJECT'); ?></label> </div>
            <div class="col-md-4">  <?php echo $form->field($model, 'SUBJECT')->textInput() ; ?>  </div>
        </div>    
        <div class="form-group">
        
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('SUBJECT_LOCATION'); ?></label> </div>
            <div class="col-md-4">  <?php echo $form->field($model, 'SUBJECT_LOCATION')->textInput() ; ?>  </div>
   
        </div> 
        
       
                <div class="form-group">
        
            <div class="col-md-2" style="text-align:right;" >  <label class="control-label"><?php echo $model->getAttributeLabel('NP_WORK_GROUP_ACTIVITY_ID'); ?></label> </div>
               <div class="col-md-8">  
                    <?php 
                        $npWorkGroupActivitys = ArrayHelper::merge($arrPlaseSelect,
                                                                    ArrayHelper::map(NP_WORK_GROUP_ACTIVITY::find()
                                                                                        ->where($where_work)
                                                                                        ->all(), 
                                                                                    'NP_WORK_GROUP_ACTIVITY_ID', 
                                                                                    'ACTIVITY_SUBJECT'));

                        echo $form->field($model, 'NP_WORK_GROUP_ACTIVITY_ID')
                            ->dropDownList($npWorkGroupActivitys) ; 
                    ?>  
                </div>   
            </div>  
        <div class="form-group" style="margin-bottom: 15px">
			<?= Html::activeLabel($model, 'IMAGE_PATH', ['class'=>'col-sm-2 control-label']); ?>
			<div class="col-sm-4">
				<?php
					if($mode=='edit'){
						echo Html::img(Utils::adjustImagePath($model->IMAGE_PATH), 
                            ['id'=>'img_viewer', 'class'=>'cursor-hand img-viewer', 'width'=>'100', 'height'=>'100', 
							'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->NP_ACTIVITY_FORM2_PROVINCE_ID.'@@IMAGE_PATH")',
							'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
					}else{
						echo '<input type="file" class="form-control image-file" name="IMAGE_PATH" />';
					}
				?>
		    </div>
		</div>
        <div class="form-group" style="margin-bottom: 15px">
            <?= Html::activeLabel($model, 'IMAGE_PATH_1', ['class'=>'col-sm-2 control-label']); ?>
            <div class="col-sm-4">
                <?php
                    $src1 = '';

                    if ($model->IMAGE_PATH_1) {
                        $src1 = Utils::adjustImagePath($model->IMAGE_PATH_1);
                    } else {
                        $src1 = Url::to('@web/images/no_image_available.jpg');
                    }

                    if ($mode=='edit') {
                        echo Html::img($src1, 
                            ['class'=>'cursor-hand img-viewer-1', 'width'=>'100', 'height'=>'100', 
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->NP_ACTIVITY_FORM2_PROVINCE_ID.'@1@IMAGE_PATH_1")',
                            'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
                    } else {
                        echo '<input type="file" class="form-control image-file" name="IMAGE_PATH_1" />';
                    }
                ?>
            </div>
        </div>     
        <div class="form-group" style="margin-bottom: 15px">
            <?= Html::activeLabel($model, 'IMAGE_PATH_2', ['class'=>'col-sm-2 control-label']); ?>
            <div class="col-sm-4">
                <?php
                    $src2 = '';

                    if ($model->IMAGE_PATH_2) {
                        $src2 = Utils::adjustImagePath($model->IMAGE_PATH_2);
                    } else {
                        $src2 = Url::to('@web/images/no_image_available.jpg');
                    }

                    if ($mode=='edit') {
                        echo Html::img($src2, 
                            ['class'=>'cursor-hand img-viewer-2', 'width'=>'100', 'height'=>'100', 
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->NP_ACTIVITY_FORM2_PROVINCE_ID.'@2@IMAGE_PATH_2")',
                            'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
                    } else {
                        echo '<input type="file" class="form-control image-file" name="IMAGE_PATH_2" />';
                    }
                ?>
            </div>
        </div>     
        <div class="form-group" style="margin-bottom: 15px">
            <?= Html::activeLabel($model, 'IMAGE_PATH_3', ['class'=>'col-sm-2 control-label']); ?>
            <div class="col-sm-4">
                <?php
                    $src3 = '';

                    if ($model->IMAGE_PATH_3) {
                        $src3 = Utils::adjustImagePath($model->IMAGE_PATH_3);
                    } else {
                        $src3 = Url::to('@web/images/no_image_available.jpg');
                    }

                    if ($mode=='edit') {
                        echo Html::img($src3, 
                            ['class'=>'cursor-hand img-viewer-3', 'width'=>'100', 'height'=>'100', 
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->NP_ACTIVITY_FORM2_PROVINCE_ID.'@3@IMAGE_PATH_3")',
                            'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
                    } else {
                        echo '<input type="file" class="form-control image-file" name="IMAGE_PATH_3" />';
                    }
                ?>
            </div>
        </div>     
        <div class="form-group" style="margin-bottom: 15px">
            <?= Html::activeLabel($model, 'IMAGE_PATH_4', ['class'=>'col-sm-2 control-label']); ?>
            <div class="col-sm-4">
                <?php
                    if ($mode=='edit') {
                        $src4 = '';

                        if ($model->IMAGE_PATH_4) {
                            $src4 = Utils::adjustImagePath($model->IMAGE_PATH_4);
                        } else {
                            $src4 = Url::to('@web/images/no_image_available.jpg');
                        }

                        echo Html::img($src4, 
                            ['class'=>'cursor-hand img-viewer-4', 'width'=>'100', 'height'=>'100', 
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->NP_ACTIVITY_FORM2_PROVINCE_ID.'@4@IMAGE_PATH_4");',
                            'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
                    } else {
                        echo '<input type="file" class="form-control image-file" name="IMAGE_PATH_4" />';
                    }
                ?>
            </div>
        </div>               
    </div> 
</div>
<div class="footcontentbutton">
    <?=$form->field($model, 'NP_ACTIVITY_FORM2_PROVINCE_ID')->hiddenInput()?>

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
	  					<div class="col-md-offset-2 col-md-8">
	  						<a href="#" class="thumbnail">
								<?= Html::img(Url::to('@web/images/no_image_available.jpg'), ['id' => 'reviewImage']); ?>
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
<!-- ################################################################################## -->
<script type="text/javascript">
    function onClickSave(){
        var nameth = jQuery('#nameth').val();
        
        var data = {nameth: nameth};
        
        jQuery.post('<?=Url::to(['npactivityform1amphoe/save']);?>', data, function(data){
            //on ajax success.
            alert('Success');
            
        }, 'json');
    }

    function setToViewMode() {
        $form = $('#npactivityForm2ProvinceForm');
        $form.find('input').prop('disabled', true);
        $form.find('select').prop('disabled', true);
        $form.find('img[class*="img-viewer"]')
        .removeAttr('onclick')
        .removeAttr('data-toggle')
        .removeAttr('data-target')
        .removeClass('cursor-hand');

        $form.find('.footcontentbutton').hide();
    }

    jQuery('#btnUploadFile').click(function() {
    	var url = "<?= Url::to(['npactivityform2province/uploadfile']); ?>";
    	// format => 24@1@IMAGE_PATH_1v
        var fileuploadData = $('#fileuploadid').val().split('@'); 
        var id = fileuploadData[0];
        var imageSeq = fileuploadData[1];
        var imageField = fileuploadData[2];
        var year = jQuery('[name="NP_ACTIVITY_FORM2_PROVINCE[YEAR]"]').val();
        var selectorImageViewer = '.img-viewer';
        if (imageSeq) {
            selectorImageViewer += '-' + imageSeq;
        }
        
        Common.ajax.ajaxFileUploadCustomParams(
        {
            url: url, 
            fileSelector: '#fileToUpload',
            params: {
                id: id,
                image_field: imageField,
                year: year
            },
            onSuccess: function(data, staus) {
                $(selectorImageViewer).attr('src', data.imagePath);
                $('#modalUploadFile').modal('hide');
            }
        });
    });

    $(document).on('submit', '#npactivityForm2ProvinceForm', function(event) {
        var $imgViewer = $('#img_viewer');
        var $imageFile = $('input[name="IMAGE_PATH"]');

        if (!$imgViewer.attr('src') && !$imageFile.val()) { // expected false && false
            BootstrapDialog.alert('กรุณาใส่ภาพกิจกรรมอย่างน้อย 1 ภาพ');
            event.preventDefault();
        }
    });

    $(function() {
        // TODO: disable field when data has not current month or status has equal 'C'
        if (('<?= $isCurrentMonth ?>' == 'true')
            && ('<?= $mode; ?>' == 'edit' && '<?= $model->STATUS; ?>' == 'C')) {
            setToViewMode();
        } else if (('<?= $isCurrentMonth ?>' == 'false')
            && ('<?= $mode; ?>' == 'edit' && '<?= $model->STATUS; ?>' != 'S')) {
            setToViewMode();
        }
    });
</script>