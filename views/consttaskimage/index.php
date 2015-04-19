<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	AppAsset::register($this);

	// if (\Yii::$app->user->isGuest) {
	// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
	// }

	JqGridWidget::widget();
?>

<!--form action="<?=Url::to(['consttaskimage/uploadfile'])?>" method="post" enctype="multipart/form-data"><input type="file" name="fileToUpload" /><input type="submit" /></form-->
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

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
var gridurl_1 = '<?=Url::to(['consttaskimage/gridview', 'consttask'=>$consttask])?>';

jQuery('#fileToUpload').change(function() {
	reviewImage(this, 'reviewImage');
});

jQuery('#btnUploadFile').click(function() {
	var url2 = "<?= Url::to(['consttaskimage/uploadfile']); ?>";
	
	Common.ajax.ajaxFileUpload(url2, '#fileToUpload', jQuery('#fileuploadid').val(), function(){
	    jQuery('#rowed5').trigger('reloadGrid');
	    $('#modalUploadFile').modal('hide');
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

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 750,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
   				'ภาพประกอบ',
   				'รายละเอียดภาพ',
   				'สถานะ',
   				'Actions'],
   	colModel:[
   	    {name:'id',index:'CONST_TASK_IMAGE_ID', width:60, align:'center', sorttype:"int", editoptions: {}}, //5
   		{name:'fileToUpload',index:'IMAGE_PATH', width:50, align:'center', edittype:'file', formatter: Common.jqgrid.playerPicFormatter}, //7
   		{name:'imagedescription',index:'IMAGE_DESCRIPTION', width:300, editable: true, editoptions: {maxlength:"255"}},
   		{name:'status',index:'STATUS', width:40, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		Common.jqgrid.onCellSelect(this, id, iCol, cellcontent, [3]);
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "CONST002 ภาพประกอบ",
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagered',
   	sortname: 'CONST_TASK_IMAGE_ID',
    viewrecords: true,
    sortorder: "asc",
    scrollOffset: 3,
    gridComplete: Common.jqgrid.onGridCompleted
});


	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		edit: false,
		add: false,
		del: true,
			refresh: false
	}, 
	{}, 
	{
		height: 280,
		reloadAfterSubmit: true,
		afterShowForm: function (form) {
		   // alert('AAA');
		   //console.log(form);
		},
	    afterSubmit: function(response, postdata){
	    	Common.jqgrid.uploadImage(this, '<?=Url::to(['consttaskimage/uploadfile'])?>', '#fileToUpload', response, postdata);
	    }
		
	}, 
	Common.jqgridOptions.navGridDelete,
	{
		multipleSearch: true,
		multipleGroup: false
	});

	var myEditOptions = {
        aftersavefunc: function (rowid, response, postdata) {
            //alert("row with rowid=" + rowid + " is successfuly modified.");
            Common.jqgrid.uploadImage(this, "<?=Url::to(['consttaskimage/uploadfile'])?>", 'input:file[name="fileToUpload"]', rowid, response, postdata);
        }
    };
	jQuery("#rowed5").jqGrid('inlineNav',"#pagered", {edit:true, save:true, cancel:true,
		onbeforeeditfunc: function(){
	        jQuery("#rowed5").jqGrid('setColProp', 'fileToUpload', {editable:false});
	    },
	    onbeforeaddfunc: function(){
	    	jQuery("#rowed5").jqGrid('setColProp', 'fileToUpload', {editable:true});
	    },
	    onafteraddfunc: function(){	    
	    	var selector = "#rowed5";	    	
	        Common.jqgrid.setGridHiddenInput(selector, 'provincecode', jQuery('select[name="PROVINCE_CODE"]').val());
	        Common.jqgrid.setGridHiddenInput(selector, 'year', jQuery('select[name="YEAR"]').val());
	    },
	    addParams: {
			addRowParams: myEditOptions
	    },
	    editParams: myEditOptions
	});
</script>