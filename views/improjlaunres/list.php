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
			        								$budgetYear,
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
			        								$month,
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
	        	<?= Html::label('โครงการ', 'FILTER_IM_PROJ_LAUN_ID', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-8">
		            <?php
			        	echo Html::dropDownList('IM_PROJ_LAUN_ID',
			        								$imProjLaunId,
			        								ArrayHelper::map($imProjLauns, 
		        																'IM_PROJ_LAUN_ID', 
		        																'imProj.PROJ_NAME_TH'),
													[
														'id' => 'FILTER_IM_PROJ_LAUN_ID',
														'class' => 'form-control',
														'prompt' => 'กรุณาเลือก',
														'onchange' => 'Common.jqgrid.onFilter()'
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
	var gridurl_1 = "<?=Url::to(['improjlaunres/gridview', 'im_proj_laun_id' => $imProjLaunId, 'budget_year' => $budgetYear]);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'IM_PROJ_LAUN_RES_ID'});

	function gotoEdit(t) {
		var url = "<?=Url::to(['improjlaunres/form']);?>";
		url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		url += '&'+getParameterFilters();
		
		window.location = url;
	}

	function getUrlToCreate() {
		var url = "<?=Url::to(['improjlaunres/form']);?>";
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		url += '&'+getParameterFilters();

		return url;
	}

	function getParameterFilters() {
		var url = '';
		url += 'budget_year='+$('#FILTER_BUDGET_YEAR').val();
		url += '&month='+$('#FILTER_MONTH').val();
		url += '&im_proj_laun_id='+$('#FILTER_IM_PROJ_LAUN_ID').val();

		return url;
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: function() {
			Common.jqgrid.onFilter();
		},
		height: 410,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่',
	   				'รหัสเดือน', 
	   				'เดือน',
	   				'โครงการ', 
	   				'งบประมาณ', 
	   				'ความพึงพอใจ%', 
	   				'แก้ไข'],
	   	colModel:[
	   		{name:'seq',index:'SEQ', width:80, sorttype:"int", sortable:true, editable: false, align:'center'},
	   		{name:'month',index:'MONTH', hidden: true},
	   		{name:'monthStr',index:'MONTH_STR', width:100, sortable:true, editable: false, align:'center'},
	   		{name:'proj_name_th',index:'PROJ_NAME_TH', width:300, sortable:true, editable: false},
	   		{name:'target_budget',index:'TARGET_BUDGET', width:200, sorttype:"int", sortable:true, editable: false},
	   		{name:'target_satisfaction',index:'TARGET_SATISFACTION', width:200, sorttype:"int", sortable:true, editable: false},
	   		{name:'sub', index:'sub', width:80, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoEdit(this)" class="glyphicon glyphicon-pencil"></span>'; }}
	   	],
		onSelectRow: function(rowid, status, e){
		},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'แสดงผลกำรดำเนินกำรประจำเดือน สอม.',
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: Common.jqgrid.onGridCompleted(),
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
	}).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlToCreate));
</script>