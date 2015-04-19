<style>
	.form-group {
		margin-bottom: 15px;
	}
</style>
<!-- ############################################################################### -->
<?php
	use app\assets\AppAsset;
	use app\models\WA_PROVINCE;
	use app\models\WA_AMPHOE;
	use app\models\SB_PROJ_LAUN;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use yii\bootstrap\ActiveForm;
	use yii\web\Utils;
	use himiklab\jqgrid\JqGridWidget;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");

	JqGridWidget::widget();
?>

<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
	<div class="panel panel-primary">
	  <div class="panel-body">
	  		<div class="form-group">
	        	<?= Html::label('ประจำปี', 'FILTER_BUDGET_YEAR', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
			        	echo Html::dropDownList('BUDGET_YEAR',
		        								$budget_year,
		        								Utils::getArrYears(),
												[
													'id' => 'FILTER_BUDGET_YEAR',
													'class' => 'form-control',
													'onchange' => 'Common.jqgrid.onFilter()'
												]); 
					?>
				</div>
				<?= Html::label('เดือน', 'FILTER_MONTH', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
			        	echo Html::dropDownList('MONTH',
		        								null,
		        								Utils::getArrMonth(),
												[
													'id' => 'FILTER_MONTH',
													'class' => 'form-control',
													'onchange' => 'Common.jqgrid.onFilter()'
												]); 
					?>
				</div>
	        </div> 
	        <div class="form-group">
	        	<?= Html::label('จังหวัด', 'FILTER_PROVINCE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
			        	echo Html::dropDownList('PROVINCE_CODE',
			        								null,
			        								ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),
													[
														'id' => 'FILTER_PROVINCE_CODE',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter()',
														'prompt' => 'กรุณาเลือก'
													]); 
					?>
				</div>
	        </div> 
	        <div class="form-group">
	        	<?= Html::label('โครงการ', 'FILTER_SB_PROJECT_LAUNCH_ID', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
			        	echo Html::dropDownList('SB_PROJECT_LAUNCH_ID',
			        								$sbProjLaunId,
			        								ArrayHelper::map($sbProjLauns, 
    																'SB_PROJ_LAUN_ID', 
    																'sbProj.PROJ_NAME_TH'),
													[
														'id' => 'FILTER_SB_PROJECT_LAUNCH_ID',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter()',
														'prompt' => 'กรุณาเลือก'
													]); 
					?>
				</div>
	        </div> 
	    </div>
	</div>
<?= Html::endForm(); ?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['sbprojlaunres/gridview', 'budget_year' => $budget_year]);?>";// "/browallia/web/index.php?r=sbprojlaun/gridview";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SB_PROJ_LAUN_RES_ID'});


	function getFilterParam() {
		var frmsearch = jQuery('#frmsearch');
		var url = '';
		url += '&budget_year='+frmsearch.find('[name="BUDGET_YEAR"]').val();
		url += '&month='+frmsearch.find('[name="MONTH"]').val();
		url += '&province_code='+frmsearch.find('[name="PROVINCE_CODE"]').val();
		// url += '&amphoe_code='+frmsearch.find('[name="AMPHOE_CODE"]').val();
		return url;
	}
	
	function gotoEdit(t){
		var url = "<?=Url::to(['sbprojlaunres/form']);?>";
		url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		url += getFilterParam();

		window.location = url;
	}

	function gotoCreate() {
		var url = "<?=Url::to(['sbprojlaunres/form']);?>";
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		url += getFilterParam();

		window.location = url;
	}

	function getUrlForAdd() {
		var url = "<?=Url::to(['sbprojlaunres/form']);?>";
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		url += getFilterParam();

		return url;
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: function() {
			Common.jqgrid.onFilter();
		},
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่', 'โครงการ', 'เดือน', 'จังหวัด', 'อำเภอ', 'งบประมาณ', 'ความพึงพอใจ%', 'แก้ไข'],
	   	colModel:[
	   		{name:'id',index:'SB_PROJ_LAUN_RES_ID', width:60, align:'center', sorttype:'int', sortable:true, editable: false},
	   		{name:'proj_name_th',index:'PROJ_NAME_TH', width:300, sortable:true, editable: false},
	   		{name:'month',index:'MONTH', width:80, sortable:true, editable: false},
	   		{name:'province_code',index:'PROVINCE_CODE', width:100, sortable:false, editable: false},
	   		{name:'amphoe_code',index:'AMPHOE_CODE', width:100, sortable:false, editable: false},
	   		{name:'target_budget',index:'TARGET_BUDGET', width:100, sorttype:"int", sortable:true, editable: false},
	   		{name:'target_satisfaction',index:'TARGET_SATISFACTION', width:120, sortable:true, editable: false},
	   		{name:'sub', index:'sub', width:60, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoEdit(this)" class="glyphicon glyphicon-pencil"></span>'; }}
	   	],
		onSelectRow: function(rowid, status, e){
		},
		onCellSelect: function(id, iCol, cellcontent){
			var pager = jQuery(this).getGridParam("pager");
			
			if ( iCol>1 ) {
				if(id && id!==lastsel2){ 
					 Common.jqgrid.restoreRow(this, id, pager);
			    }
				jQuery(this).editRow(id, true
				, {/*oneditfunc*/}
				, function(res){jQuery(this).trigger('reloadGrid'); g_OnEditRowId=undefined;} //successfunc
				, undefined //url
				, {/*extraparam*/}
				, {/*aftersavefunc*/}
				, {/*errorfunc*/}
				, function(){jQuery(this).trigger('reloadGrid'); g_OnEditRowId=undefined;}/*afterrestorefunc*/
				)
				.setSelection(id, false);
				Common.jqgrid.setEditMode(pager, true);
				g_OnEditRowId = id;
			}else{
				Common.jqgrid.restoreRow(this, id, pager);
			}
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'รายงานบันทึกผลการดำเนินงานประจำเดือน',
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: Common.jqgrid.onGridCompleted,
	   	rowNum:rtparams.rows,
	   	sortname: rtparams.sidx,
	    sortorder: rtparams.sord,
	    page: rtparams.page,
	});

	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		edit: false,
		add: false,
		del: true,
			refresh: false
	}, 
	{}, 
	{
		height: 250,
		width: 500,
		reloadAfterSubmit: true
	}, 
	Common.jqgridOptions.navGridDelete,
	{
		multipleSearch: true,
		multipleGroup: true
	})
	.navButtonAdd('#pagered', Common.jqgrid.getAddButton(getUrlForAdd));
</script>