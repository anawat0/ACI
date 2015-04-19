<?php
	use yii\web\Utils;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	JqGridWidget::widget();

	$ssbProjId = $ssbProj == null? '': $ssbProj->SSB_PROJ_ID;
?>
<form class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการหลัก</label>
				<div class="col-sm-7">
			    	<?= Html::textInput('MAIN_PROJ_NAME_TH', 
			    						$ssbProj->ssbMainProj->MAIN_PROJ_NAME_TH, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
					<?= Html::textInput('BUDGET_YEAR', 
			    						$ssbProj->ssbMainProj->BUDGET_YEAR, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการ</label>
				<div class="col-sm-7">
					<?= Html::textInput('PROJ_NAME_TH', 
			    						$ssbProj->PROJ_NAME_TH, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
				</div>
			</div>
		</div>
	</div>
</form>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ################################################################################## -->
<script type="text/javascript">
	var gridurl_1 = "<?= Url::to(['ssbsubproj/gridview', 'ssb_proj_id' => $ssbProjId]); ?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?= $rtparams; ?>', {sidx: 'SSB_SUB_PROJ_ID'});

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 410,
		width: jQuery('div.content').width()-20,
		colNames:['ลำดับที่', 
					'รหัสแผนงาน/โครงการ*', 
					'โครงงาน/แผนการย่อย (ไทย)*', 
					'โครงงาน/แผนการย่อย (ENG)', 
					'งบประมาณ', 
					'สถานะ*',
					'Actions'],
	   	colModel:[
	        {name:'id', index:'SSB_SUB_PROJ_ID', width:80, align:'center', sorttype:"int", editable: true, editable: false},
			{name:'ssb_proj_id', index:'SSB_PROJ_ID', hidden:true , editable:true, editrules:{edithidden:false}, editoptions:{defaultValue:'<?= $ssbProjId; ?>'}},
			{name:'sub_proj_name_th',index:'SUB_PROJ_NAME_TH', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'sub_proj_name_en',index:'SUB_PROJ_NAME_EN', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'budget', index:'BUDGET', width:100, sortable:true, sorttype:"int", editable: true, align:'center', edittype:"text", editoptions:{maxlength:"255"}},
			{name:'status',index:'STATUS', width:80, sortable:true, editable: true, align:'center', edittype:"checkbox", editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
			{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'แสดง/บันทึก แผนงาน/โครงการ',
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: Common.jqgrid.onGridCompleted,
	   	rowNum:rtparams.rows,
	   	sortname: rtparams.sidx,
	    sortorder: rtparams.sord,
	    page: rtparams.page,
	//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
	});

	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		add: false,
		edit: false,
		del: true,
			refresh: false
	}, 
	{}, 
	{
		height: 250,
		reloadAfterSubmit: true
	}, 
	Common.jqgridOptions.navGridDelete,
	{
		multipleSearch: true,
		multipleGroup: false
	});

	var myEditOptions = {
        aftersavefunc: Common.jqgrid.aftersavefunc
    };
	jQuery("#rowed5").jqGrid('inlineNav',"#pagered", 
	{
		edit:true, save:true, cancel:true,
		onbeforeeditfunc: function() {
	    },
	    onbeforeaddfunc: function() {
	    },
	    onafteraddfunc: function() {
	    },
	    addParams: {
			addRowParams: myEditOptions
	    },
	    editParams: myEditOptions
	});
</script>