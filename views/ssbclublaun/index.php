<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use yii\web\Utils;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	use app\models\WA_PROVINCE;
	use app\models\SSB_CLUB;
	AppAsset::register($this);

	// if (\Yii::$app->user->isGuest) {
	// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
	// }

	JqGridWidget::widget();
?>
<style type="text/css">
.form-filtering .form-group {
	padding: 0 0 10px 0;
}
</style>

<form id="frmsearch" class="form-horizontal form-filtering">

	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-2 right">
					<label>ประจำปี</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="YEAR">
		                <?php Utils::getOptionsYears('', false); ?>
		            </select>
				</div>
				<div class="col-md-1 right">
					<label>เดือน</label>
				</div>
				<div class="col-md-3">
					<?php
						$monthList = ['1' => 'มกราคม',
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

						echo Html::dropDownList('MONTH', 
												null, 
												$monthList, 
												[
													'class' => 'form-control',
													'onchange' => 'Common.jqgrid.onFilter()'
												]);
					?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2 right">
					<label>จังหวัด</label>
				</div>
				<div class="col-md-3">
					<?php
						echo Html::dropDownList('PROVINCE_CODE', 
												null, 
												ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),
												[
													'prompt' => 'กรุณาเลือก',
													'class' => 'form-control', 
													'onchange' => 'Common.jqgrid.onFilter();onChangeProvince(this);'
												]
												);
					?>
				</div>
				<div class="col-md-1 right">
					<label>ชมรม</label>
				</div>
				<div class="col-md-3">
					<?php
						echo Html::dropDownList('SSB_CLUB_ID', 
											null, 
											ArrayHelper::map(SSB_CLUB::find()->where(['PROVINCE_CODE'=>10])->all(), 'SSB_CLUB_ID', 'CLUB_NAME_TH'),
											[
												'prompt' => 'กรุณาเลือก',
												'class' => 'form-control', 
												'onchange' => 'Common.jqgrid.onFilter()'
											]);
					?>
				</div>
			</div>
		</div>
	</div>
</form>
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
var gridurl_1 = "<?=Url::to(['ssbclublaun/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx:'ACT_NAME_TH'});

function gotoView1(t){
    var url='<?=Url::to(['ssbclublaun/'])?>';
	url += '&consttask=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['ssbclublaun/getclubs']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            var jElm = jQuery('#frmsearch').find('select[name="SSB_CLUB_ID"]');
            jElm.find('option').remove();
            jElm.append('<option value="">กรุณาเลือก</option>')
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.SSB_CLUB_ID+'">'+row.CLUB_NAME_TH+'</option>');
            });
        }, 'json');
    }
}

jQuery('#fileToUpload').change(function() {
	reviewImage(this, 'reviewImage');
});

jQuery('#btnUploadFile').click(function() {
	var url2 = "<?= Url::to(['ssbclublaun/uploadfile']); ?>";
	
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
	datatype: function() {
		Common.jqgrid.onFilter();
	},
	height: 750,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
			   	'กิจกรรม',
			   	'จังหวัด',
			   	'ชมรม',
			   	'เดือน',
			   	'ชาย',
			   	'หญิง',
			   	'ชาย',
			   	'หญิง',
			   	'ชาย',
			   	'หญิง',
			   	'ชาย',
			   	'หญิง',
			   	'ภาพ',
			   	'',
			   	'',
			   	'',
			   	'',
			   	'Actions'],
   	colModel:[
   		{name:'id',index:'SSB_CLUB_LAUN_ID', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'actnameth',index:'ACT_NAME_TH',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'tmpprovince',index:'PROVINCE_CODE',editable: false,editoptions:{size:"27",maxlength:"255"}},
   		{name:'tmpssbclub',index:'SSB_CLUB_ID',editable:false,editoptions:{size:"3",maxlength:"255"}},
   		{name:'tmpmonth',index:'MONTH',width:100,editable: false,editoptions:{size:"3",maxlength:"255"}, },
        {name:'agemale0',index:'AGE_MALE_0',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agefemale0',index:'AGE_FEMALE_0',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agemale1',index:'AGE_MALE_1',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agefemale1',index:'AGE_FEMALE_1',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agemale2',index:'AGE_MALE_2',width:35 , align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agefemale2',index:'AGE_FEMALE_2',width:35 , align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'agemale3',index:'AGE_MALE_3',width:35, align:'center', editable: true,editoptions:{size:"1" ,maxlength:"3"}},   
        {name:'agefemale3',index:'AGE_FEMALE_3',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
   		{name:'fileToUpload',index:'IMAGE_PATH',width:80, align:'center', editable: true, edittype:'file', formatter: Common.jqgrid.playerPicFormatter},
   		{name:'year', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
   		{name:'month', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
   		{name:'provincecode', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
   		{name:'ssbclubid', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "SSB005",
   	rowList:[10,20,30],
   	pager: '#pagered',
    viewrecords: true,
    scrollOffset: 3,
    gridComplete: function(){ 
    	Common.jqgrid.onGridCompleted();
    },
   	rowNum:rtparams.rows,
   	sortname: rtparams.sidx,
    sortorder: rtparams.sord,
    page: rtparams.page,
//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
});

jQuery("#rowed5").jqGrid('setGroupHeaders', {
  useColSpanStyle: true, 
  groupHeaders:[
	{startColumnName: 'agemale0', numberOfColumns: 2, titleText: '3-5 ปี'},
	{startColumnName: 'agemale1', numberOfColumns: 2, titleText: '6-24 ปี'},
    {startColumnName: 'agemale2', numberOfColumns: 2, titleText: '25-60 ปี'},
    {startColumnName: 'agemale3', numberOfColumns: 2, titleText: '60 ปีขึ้นไป'},
  ]
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
	reloadAfterSubmit: true
}, 
Common.jqgridOptions.navGridDelete, 
{
	multipleSearch: true,
	multipleGroup: false
});

	var myEditOptions = {
        aftersavefunc: function (rowid, response, postdata) {
            //alert("row with rowid=" + rowid + " is successfuly modified.");
        	Common.jqgrid.uploadImage(this, '<?=Url::to(['ssbclublaun/uploadfile'])?>', 'input:file[name="fileToUpload"]', rowid, response, postdata);
        }
    };

	jQuery("#rowed5").jqGrid('inlineNav',"#pagered", {edit:true, save:true, cancel:true,
		onbeforeeditfunc: function(){
	        jQuery("#rowed5").jqGrid('setColProp', 'fileToUpload', {editable:false});
	    },
	    onbeforeaddfunc: function(){
	    	var provinceCode = jQuery('select[name="PROVINCE_CODE"]').val();
	    	var ssbClubId = jQuery('select[name="SSB_CLUB_ID"]').val();

        	if (!provinceCode) {
        		BootstrapDialog.alert('กรุณาเลือกจังหวัด');
        		return false;
        	}
        	if (!ssbClubId) {
        		BootstrapDialog.alert('กรุณาเลือกชมรม');
        		return false;
        	}

        	jQuery("#rowed5").jqGrid('setColProp', 'fileToUpload', {editable:true});
	    },
	    onafteraddfunc: function(){
	    	var selector = "#rowed5";

	    	// TODO: set tmp for label
		    Common.jqgrid.setColumn(selector, 'tmpprovince', jQuery('[name="PROVINCE_CODE"] option:selected').text());
		    Common.jqgrid.setColumn(selector, 'tmpssbclub', jQuery('[name="SSB_CLUB_ID"] option:selected').text());
		    Common.jqgrid.setColumn(selector, 'tmpmonth', jQuery('[name="MONTH"] option:selected').text());
	
			// TODO: set value for hidden
	    	Common.jqgrid.setGridHiddenInput(selector, 'year', jQuery('select[name="YEAR"]').val());
	        Common.jqgrid.setGridHiddenInput(selector, 'month', jQuery('select[name="MONTH"]').val());
	        Common.jqgrid.setGridHiddenInput(selector, 'provincecode', jQuery('select[name="PROVINCE_CODE"]').val());
	        Common.jqgrid.setGridHiddenInput(selector, 'ssbclubid', jQuery('select[name="SSB_CLUB_ID"]').val());
	    },
	    addParams: {
			addRowParams: myEditOptions
	    },
	    editParams: myEditOptions
	});
</script>