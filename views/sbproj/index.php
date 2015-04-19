<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;

	AppAsset::register($this);

	JqGridWidget::widget();
?>
<form class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการหลัก</label>
				<div class="col-sm-7">
			    	<?= Html::textInput('MAIN_PROJ_NAME_TH', 
			    						$sbMainProj->MAIN_PROJ_NAME_TH, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
			    	<?= Html::textInput('BUDGET_YEAR', 
			    						$sbMainProj->BUDGET_YEAR, 
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
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['sbproj/gridview', 'sb_main_proj_id'=>$sb_main_proj_id]);?>";//browallia/web/index.php?r=sbproj/gridview&sb_main_proj_id=<?php echo $sb_main_proj_id; ?>";
	var rtparams2 = Common.jqgrid.getDefaultPostData('<?=$rtparams2?>', {sidx: 'SB_PROJ_ID'});

	// TODO: goto sub menu page
	function gotoSubProj(t){
		var id = jQuery(t).closest('tr[role="row"]').attr('id');
		var url = '';

		// TODO: check edit mode
		if (!isNaN(id)) {
			url = "<?=Url::to(['sbsubproj/'])?>";
			url += '&sb_proj_id=' + id;
			url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
			window.location = url;
		}
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
    	height: 450,
    	width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับ', 
	   				'ชื่อแผนงาน/โครงการ (ไทย)', 
	   				'ชื่อแผนงาน/โครงการ (Eng)', 
	   				'งบประมาณ', 
	   				'สถานะ', 
	   				'โครงการย่อย',
	   				'Action'],
	   	colModel:[
	   		{name:'id',index:'SB_PROJ_ID', width:50, sorttype:"int", align:'center', sortable:true, editable: false},
	   		{name:'nameth',index:'PROJ_NAME_TH', width:320, sortable:true, editable: true, editoptions:{width:"320",maxlength:"255"}},
	   		{name:'nameen',index:'PROJ_NAME_EN', width:320, sortable:true, editable: true, editoptions:{width:"320",maxlength:"255"}},
	   		{name:'budget',index:'BUDGET', width:100, sortable:true,editable: true, edittype:"text", editoptions:{width:"100"}},
	   		{name:'status',index:'STATUS', width:100, sortable:true, editable: true, align:'center', edittype:"checkbox",editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'sub', index:'sub', width:100, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoSubProj(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "SB001 รายชื่อโครงการ สกฬ.",
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: function(){
	    	var pager = jQuery(this).getGridParam("pager");
		    Common.jqgrid.setEditMode(pager, false);
		    jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
	        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
	    },
	   	rowNum:rtparams2.rows,
	   	sortname: rtparams2.sidx,
	    sortorder: rtparams2.sord,
	    page: rtparams2.page,
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