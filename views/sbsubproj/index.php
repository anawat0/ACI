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
			    						$sbProj->sbMainProj->MAIN_PROJ_NAME_TH, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
					<?= Html::textInput('BUDGET_YEAR', 
			    						$sbProj->sbMainProj->BUDGET_YEAR, 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการ</label>
				<div class="col-sm-7">
					<?= Html::textInput('PROJ_NAME_TH', 
			    						$sbProj->PROJ_NAME_TH, 
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
	var gridurl_1 = "<?=Url::to(['sbsubproj/gridview', 'sb_proj_id'=>$sb_proj_id]);?>";//browallia/web/index.php?r=sbsubproj/gridview&sb_proj_id=<?php echo $sb_proj_id; ?>";
	var rtparams3 = Common.jqgrid.getDefaultPostData('', {sidx: 'SB_SUB_PROJ_ID'});

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
    	height: 450,
    	width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับ', 
				   	'โครงการย่อย-กิจกรรม (ไทย)', 
				   	'โครงการย่อย-กิจกรรม (ENG)', 
				   	'งบประมาณ', 
				   	'สถานะ',
				   	'Action'],
	   	colModel:[
	   		{name:'id',index:'SB_SUB_PROJ_ID', width:50, sorttype:"int", align:'center', sortable:true, editable: false},
	   		{name:'nameth',index:'SUB_PROJ_NAME_TH', width:350, sortable:true, editable: true, editoptions:{width:"350",maxlength:"255"}},
	   		{name:'nameen',index:'SUB_PROJ_NAME_EN', width:350, sortable:true, editable: true, editoptions:{width:"350",maxlength:"255"}},
	   		{name:'budget',index:'BUDGET', width:100, sortable:true, editable: true, edittype:"text", editoptions:{width:"80"}},
	   		{name:'status',index:'STATUS', width:100, sortable:false, editable: true, align:'center', edittype:"checkbox",editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "SB001 รายชื่อโครงการ สกฬ.<br>",
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
	   	rowNum:rtparams3.rows,
	   	sortname: rtparams3.sidx,
	    sortorder: rtparams3.sord,
	    page: rtparams3.page,
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