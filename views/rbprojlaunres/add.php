<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\web\Authentication;
use yii\web\Utils;
use app\models\RB_PROJ_LAUN_RES;
use app\models\WA_PROVINCE;
use app\models\NP_WORK_GROUP_ACTIVITY;
use app\models\WA_AMPHOE;
AppAsset::register($this);


//$where_work = "NP_WORK_GROUP_ID="."'".$work_id."'";


/*$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ; */

$form = ActiveForm::begin([
    'id' => 'rbProjLaunResForm',
    'layout' => 'horizontal',
    'action' => Url::to(['rbprojlaunres/save']),
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

    $model = $rbprojlaunres;
    $mode = (empty($id)?'add':'edit'); //add, edit.
    $isEditMode = !empty($rbprojlaunres);

    $user_info = Authentication::getAuthenInfo();

    if($mode=='add'){	
    	$model->BUDGET_YEAR = $budget_year;
    	$model->MONTH = $month;
    	$model->PROVINCE_CODE = $province_code;
    	$model->AMPHOE_CODE = $amphoe_code;
        $model->RB_PROJ_LAUN_ID = $rb_proj_laun_id ;
    	$model->RB_SUB_PROJ_ID = $rb_sub_proj_id ;
    } else {
        $province_code = $model->PROVINCE_CODE;
        $amphoe_code = $model->AMPHOE_CODE;
    }
?>

<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER_SECTION_1'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BUDGET_YEAR'); ?></label> </div>
            <div class="col-sm-3"><?php echo $form->field($model, 'BUDGET_YEAR')->dropDownList(Utils::getArrYears()); ?> </div> 
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MONTH'); ?></label> </div>
            <div class="col-sm-3"><?php echo  $form->field($model, 'MONTH' )->dropDownList(Utils::getArrMonth()); ?> </div>            
        </div>
        
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo "จังหวัด" ?></label> </div>
            <div class="col-md-3">
                <?php
                    echo Utils::getDDLProvince_AutoComplete($form, $model, 'PROVINCE_CODE',
                            'PROVINCE_CODE',
                            'PROVINCE_NAME_TH',
                            $province_code,
                            $user_info,
                            ['onchange' => 'Common.ajax.onChangeProvince(this, \'RB_PROJ_LAUN_RES[AMPHOE_CODE]\', \''.$amphoe_code.'\')']);
                ?>
            </div>
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo "อำเภอ" ?></label> </div>
            <div class="col-md-3">  
                <?php 
                    echo Utils::getDDLAmphoe_AutoComplete($form, $model, 'AMPHOE_CODE',
                                                            'AMPHOE_CODE',
                                                            'AMPHOE_NAME_TH',
                                                            $amphoe_code,
                                                            $user_info,
                                                            $province_code,
                                                            []);
                ?>
            </div>          
        </div>

        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  
                <label style="padding-top:8px;"><?php echo "โครงการ" ?></label> 
            </div>
            <div class="col-md-8">  
                <?php
                    echo $form->field($model, 'RB_PROJ_LAUN_ID', ['options' => ['class' => 'form-group']])
                                                ->dropDownList(BaseArrayHelper::map($rbProjLauns, 
                                                                                'RB_PROJ_LAUN_ID', 
                                                                                'rbProj.PROJ_NAME_TH'),
                                                                ['id' => 'rbProjLaunList',
                                                                'class' => 'form-control',
                                                                'prompt' => 'กรุณาเลือก',
                                                                'onchange' => 'onProjLaunChange(this)']);
                ?>
                <span class="loading">กำลังโหลดข้อมูล...</span> 
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  
                <label style="padding-top:8px;"><?php echo "โครงการ" ?></label> 
            </div>
            <div class="col-md-8">  
                <?php
                    echo $form->field($model, 'RB_SUB_PROJ_ID', ['options' => ['class' => 'form-group']])
                                                ->dropDownList(BaseArrayHelper::map($rbSubProjs, 
                                                                                    'RB_SUB_PROJ_ID', 
                                                                                    'SUB_PROJ_NAME_TH'),
                                                                ['id' => 'rbSubProjList',
                                                                'class' => 'form-control',
                                                                'prompt' => 'กรุณาเลือก']); 
                ?>
            </div>
        </div>
        
	    <div class="form-group">            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ACT_NAME_TH'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'ACT_NAME_TH')->textInput() ; ?>  </div>            
        </div>  
      
        <div class="form-group">                 
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PLACE'); ?></label> </div>
            <div class="col-md-4">  <?php echo $form->field($model, 'PLACE')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ORG_RESPON_NAME'); ?></label> </div>
            <div class="col-md-4">  <?php echo $form->field($model, 'ORG_RESPON_NAME' , ['options' => ['class' => '']] )->textInput(['class' => 'form-control edu']) ; ?>  </div>                 
        </div>
      
        <div class="form-group">     
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BUDGET'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BUDGET')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('FROM_DATE'); ?></label> </div>
            <div class="col-md-2">
                <?php 
                    echo $form->field($model, 'FROM_DATE')->widget(Datepicker::classname()); 
                ?>
            </div>
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TO_DATE'); ?></label> </div>
            <div class="col-md-2">  
                <?php 
                    echo $form->field($model, 'TO_DATE')->widget(Datepicker::classname()); 
                ?>  
            </div>   
        </div>
      
        <div class="form-group">     
            <div class="col-md-2" style="text-align:right;" > <u> <label style="padding-top:8px;">ผู้ร่วมงาน</label> </u></div>
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;">ประชาชน</label> </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;">ชาย</label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'PEOPLE_MALE')->textInput(['class' => 'form-control citizen']) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;">หญิง</label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'PEOPLE_FEMALE')->textInput(['class' => 'form-control citizen']) ; ?>  </div>            
            
            <div class="col-md-1" style="text-align:right;" >  
                <label style="padding-top:8px;">รวม</label> 
            </div>
            <div class="col-md-2">
                <?= Html::textInput('SUM_CITIZEN', 
                                    null, 
                                    ['id' => 'SUM_CITIZEN', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
            </div>
        </div>
        
        <div class="form-group">                
            <div class="col-md-3" style="text-align:right;" >  <label style="padding-top:8px;">เยาวชน</label> </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;">ชาย</label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'YOUTH_MALE')->textInput(['class' => 'form-control junvenile']) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;">หญิง</label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'YOUTH_FEMALE')->textInput(['class' => 'form-control junvenile']) ; ?>  </div>            
            
            <div class="col-md-1" style="text-align:right;" >  
                <label style="padding-top:8px;">รวม</label> 
            </div>
            <div class="col-md-2">
                <?= Html::textInput('SUM_JUNVENILE', 
                                    null, 
                                    ['id' => 'SUM_JUNVENILE', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
            </div>
        </div>
        
        <div class="form-group">        
            <div class="col-md-2" style="text-align:right;"> 
                    <label class="form-label" style="padding-top:8px;">ลักษณะ</label> 
            </div>                       
            <div class="col-md-4">
                <?php 
                    $opFlagList = array('1' => 'ทำเอง', '2' => 'ร่วมกัน', '3' => 'ร่วมงาน');
                    
                    echo $form->field($model, 'OP_FLAG')
                                ->radioList($opFlagList, 
                                            [
                                                'class' => 'radio clearfix',
                                                'item' => function ($index, $label, $name, $checked, $value){
                                                                return Html::radio($name, $checked, [
                                                                   'value' => $value,
                                                                   'label' => $label,
                                                                   'labelOptions' => ['class' => 'col-md-3'],
                                                                   'class' => 'form',
                                                                ]);
                                                            }
                                            ]);
                ?>
            </div>
        </div>         
          
        <div class="form-group">        
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php //echo $model->getAttributeLabel('NP_WORK_GROUP_ACTIVITY_ID'); ?></label> </div>
            <div class="col-md-2">  <?php //echo $form->field($model, 'NP_WORK_GROUP_ACTIVITY_ID')->dropDownList(ArrayHelper::map(NP_WORK_GROUP_ACTIVITY::find()->all(), 'NP_WORK_GROUP_ACTIVITY_ID', 'ACTIVITY_SUBJECT')) ; ?>  </div>   
        </div> 
      
        <div class="form-group" style="margin-bottom: 15px">
			<?= Html::activeLabel($model, 'IMAGE_PATH', ['class'=>'col-sm-2 control-label']); ?>
			<div class="col-sm-4">
				<?php
					if ($mode=='edit') {
						echo Html::img(Utils::adjustImagePath($model->IMAGE_PATH), 
                            ['class'=>'cursor-hand img-viewer', 'width'=>'100', 'height'=>'100', 
							'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->RB_PROJ_LAUN_RES_ID.'@@IMAGE_PATH")',
							'data-toggle'=>'modal', 'data-target'=>'#modalUploadFile']);
					} else {
						echo '<input type="file" class="form-control image-file" name="IMAGE_FILE" />';
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
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->RB_PROJ_LAUN_RES_ID.'@1@IMAGE_PATH_1")',
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
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->RB_PROJ_LAUN_RES_ID.'@2@IMAGE_PATH_2")',
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
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->RB_PROJ_LAUN_RES_ID.'@3@IMAGE_PATH_3")',
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
                            'onclick'=>'Common.bootstrap.modal.displayImg(this, "'.$model->RB_PROJ_LAUN_RES_ID.'@4@IMAGE_PATH_4")',
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
    <?=$form->field($model, 'RB_PROJ_LAUN_RES_ID')->hiddenInput()?>

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
								<?php
                                    if ($mode=='edit') {
                                        echo Html::img(Url::to('@web/images/no_image_available.jpg'), ['id' => 'reviewImage'] );
                                    }
                                ?>
                            </a>
						</div>
					</div>
					<div class="row">
	  					<div class="col-md-offset-2 col-md-8">
	  						<?php
	  							echo Html::fileInput('fileToUpload', 
                                                        null, 
                                                        ['id' => 'fileToUpload',
                                                        'class' => '',
                                                        'onchange' =>  "fncReviewImage(this, 'reviewImage')"]); 
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
    // function onChangeProvince(t){
    //     var province_id=t.value;
    //     var data = {province : province_id};
    //     if(province_id!=''){
    //         jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
    //             //on ajax success.
    //             //console.log(data);
    //             jQuery('#rb_proj_laun_res-amphoe_code').find('option').remove();
    //             jQuery.each(data, function(i, row){
    //                 jQuery('#rb_proj_laun_res-amphoe_code').append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
    //             });
    //         }, 'json');
    //     }
    // }

    function onClickSave(){
        var nameth = jQuery('#nameth').val();
        
        var data = {nameth: nameth};
        
        jQuery.post('<?=Url::to(['rbprojlaunres/save']);?>', data, function(data){
            //on ajax success.
            alert('Success');
            
        }, 'json');
        
    }

    jQuery('#btnUploadFile').click(function() {
    	var url = "<?= Url::to(['rbprojlaunres/uploadfile']); ?>";
        // format => 24@1@IMAGE_PATH_1v
        var fileuploadData = $('#fileuploadid').val().split('@'); 
        var id = fileuploadData[0];
        var imageSeq = fileuploadData[1];
        var imageField = fileuploadData[2];
        var year = jQuery('[name="RB_PROJ_LAUN_RES[BUDGET_YEAR]"]').val();
        var selectorImageViewer = '.img-viewer';
        if (imageSeq) {
            selectorImageViewer += '-' + imageSeq;
        }

        jQuery.ajaxFileUpload(
        {
            url: url,
            secureuri: false,
            fileElementId: $('#fileToUpload').attr('id'),
            dataType: 'json',
            data: {id:id, image_field:imageField, year: year},
            success: function (data, status) {
                if (typeof (data.success) != 'undefined') {
                    if (data.success == true) {
                        $(selectorImageViewer).attr('src', data.imagePath);
                        $('#modalUploadFile').modal('hide');
                    } else {
                        Common.utils.showFlashMessage({
                            text: 'บันทึกข้อมูลสำเร็จ',
                            className: 'success'
                        });
                    }
                }
                else {
                    return BootstrapDialog.alert('(1)Failed to upload logo!');
                }
            },
            error: function (data, status, e) {
                return BootstrapDialog.alert('(2)Failed to upload logo!');
            }
        });
    });

    function fncReviewImage(input, container) {
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

    function onProjLaunChange(obj) {
        var url = "<?=Url::to(['rbprojlaunres/ajax-get-sub-proj-list']);?>";
        var rbProjLaunId = $(obj).val();

        $('.loading').show();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            data:{rb_proj_laun_id:rbProjLaunId}
        })
        .done(function(data, textStatus, jqXHR) {
            rederRbSubProjList(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            alert('เกิดข้อผิดพลาด');
        })
        .always(function(data, textStatus, jqxhr_error) {
            $('.loading').hide();
        });
    }

    function rederRbSubProjList(data) {
        var $rbSubProjList = $('#rbSubProjList');
        var html = '<option value="">กรุณาเลือก</option>';

        $.each(data, function(i, e) {
            html += '<option value="'+e.RB_SUB_PROJ_ID+'">'+e.SUB_PROJ_NAME_TH+'</option>';
        });

        $rbSubProjList.html(html);
    }

    var CalculateSummary = function() {
        elementInput = '';
        elementSummary = '';
    }
    CalculateSummary.prototype.setSummary = function() {
        var sum = 0;
        $(this.elementInput).each(function(i, obj){
            var val = $(obj).val();

            if (val) {
                sum += parseInt(val);
            }
        });

        $(this.elementSummary).val(sum);
    }

    function setToViewMode() {
        $form = $('#rbProjLaunResForm');
        $form.find('input').prop('disabled', true);
        $form.find('select').prop('disabled', true);
        $form.find('img[class*="img-viewer"]')
        .removeAttr('onclick')
        .removeAttr('data-toggle')
        .removeAttr('data-target')
        .removeClass('cursor-hand');

        $form.find('.footcontentbutton').hide();
    }

    $(function() {

		jQuery('body').css('min-width', '1300px').css('overflow', 'auto');
		
        // TODO: disable field when data has not current month or status has equal 'C'
        if (Common.utils.isCurrentMonth('<?= $model->BUDGET_YEAR; ?>', '<?= $model->MONTH; ?>', true)
            && ('<?= $mode; ?>' == 'edit' && '<?= $model->STATUS; ?>' == 'C')) {
            setToViewMode();
        } else if (!Common.utils.isCurrentMonth('<?= $model->BUDGET_YEAR; ?>', '<?= $model->MONTH; ?>', true)
            && ('<?= $mode; ?>' == 'edit' && '<?= $model->STATUS; ?>' != 'S')) {
            setToViewMode();
        } else {}

        $('.loading').hide();

        // Summary
        citizenSummary = new CalculateSummary();
        citizenSummary.elementInput = 'input.citizen';
        citizenSummary.elementSummary = '#SUM_CITIZEN';
        citizenSummary.setSummary();
        $(citizenSummary.elementInput).on('keyup', function() {
            citizenSummary.setSummary();
        });

         // Summary
        junvenileSummary = new CalculateSummary();
        junvenileSummary.elementInput = 'input.junvenile';
        junvenileSummary.elementSummary = '#SUM_JUNVENILE';
        junvenileSummary.setSummary();
        $(junvenileSummary.elementInput).on('keyup', function() {
            junvenileSummary.setSummary();
        });
    });

    $(document).on('submit', '#rbProjLaunResForm', function(event) {
        var $imgViewer = $('.img-viewer');
        var $imageFile = $('.image-file');

        // expected false && false
        if (!$imgViewer.attr('src') && !$imageFile.val()) { 
            BootstrapDialog.alert('กรุณาเลือก ภาพกิจกรรม');
            event.preventDefault();
        }
    });
</script>