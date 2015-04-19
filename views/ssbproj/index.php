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

	$ssbMainProjId = $ssbMainProj == null? '': $ssbMainProj->SSB_MAIN_PROJ_ID;
?>
<form class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการหลัก</label>
				<div class="col-sm-7">
			    	<?= Html::textInput('MAIN_PROJ_NAME_TH', 
			    						$ssbMainProj->MAIN_PROJ_NAME_TH, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
			    	<?= Html::textInput('BUDGET_YEAR', 
			    						$ssbMainProj->BUDGET_YEAR, 
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
	var gridurl_1 = "<?= Url::to(['ssbproj/gridview', 'ssb_main_proj_id' => $ssbMainProjId]); ?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?= $rtparams; ?>', {sidx: 'SSB_PROJ_ID'});

	function gotoSsbSubProj(t){
		var sssProjId = jQuery(t).closest('tr[role="row"]').attr('id');
	    var url = '';

	    // TODO: check edit mode
	    if (!isNaN(sssProjId)) {
	    	url += "<?= Url::to(['ssbsubproj/index']); ?>";
			url += '&ssb_proj_id=' + sssProjId;
			url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
			
			window.location=url;
	    }
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 410,
		width: jQuery('div.content').width()-20,
		colNames:['ลำดับที่', 
					'รหัสแผนงาน/โครงการหลัก', 
					'โครงงาน/แผนการ (ไทย)*', 
					'โครงงาน/แผนการ (ENG)', 
					'งบประมาณ', 
					'สถานะ*', 
					'โครงการย่อย',
					'Actions'],
	   	colModel:[
	        {name:'id', index:'SSB_PROJ_ID', width:80, align:'center', sorttype:"int", editable: true, editable: false},
			{name:'ssb_main_proj_id', index:'SSB_MAIN_PROJ_ID', hidden:true , editable:true, editrules:{edithidden:false}, editoptions:{defaultValue:'<?= $ssbMainProjId; ?>'}},
			{name:'proj_name_th',index:'PROJ_NAME_TH', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'proj_name_en',index:'PROJ_NAME_EN', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'budget', index:'BUDGET', width:100, sortable:true, sorttype:"int", editable: true, align:'center', edittype:"text", editoptions:{maxlength:"255"}},
			{name:'status',index:'STATUS', width:80, sortable:true, editable: true, align:'center', edittype:"checkbox", editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'sub', index:'sub', width:100, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoSsbSubProj(this);" class="ui-icon ui-icon-circle-zoomout"></span>';}},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){
		},
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