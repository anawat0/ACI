<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\Json;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");

	JqGridWidget::widget();
?>
<form class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">แผนงาน/โครงการหลัก</label>
				<div class="col-sm-7">
			    	<?= Html::textInput('PROJ_NAME_TH', 
			    						$sbProjLaun->sbProj['PROJ_NAME_TH'], 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
			    	<?= Html::textInput('BUDGET_YEAR', 
			    						$sbProjLaun->BUDGET_YEAR, 
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
	var gridurl_1 = "<?=Url::to(['sbprojlauntarget/gridview', 'sb_proj_laun_id'=>$sb_proj_laun_id]);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SB_PROJ_LAUN_TARGET_ID'});

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับ', 
				   	'ชื่อกลุ่มเป้าหมาย (ไทย)', 
				   	'ชื่อกลุ่มเป้าหมาย (Eng)', 
				   	'เป้าหมาย (คน)', 
				   	'สถานะ',
				   	'Action'],
	   	colModel:[
	   		{name:'sb_proj_laun_target_act_id',index:'SB_PROJ_LAUN_TARGET_ID', width:50, sorttype:"int", align:'center', sortable:true, editable: false},
	   		{name:'target_name_th',index:'TARGET_NAME_TH', width:300, sortable:true, editable: true, edittype:"text", editoptions:{width:'350'}},
	   		{name:'target_name_en',index:'TARGET_NAME_EN', width:300, sortable:true, editable: true, edittype:"text", editoptions:{width:'350'}},
	   		{name:'target_amount',index:'TARGET_AMOUNT', width:100, sortable:true,editable: true, align:'right', edittype:"text", stype:"integer", editoptions:{width:'100'}},
	   		{name:'status', index:'STATUS', width:80, sortable:true, editable: true, align:'center', edittype:"checkbox", editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	 	],
		onSelectRow: function(rowid, status, e){		
		},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "แผนงาน-โครงการ ที่กำลังดำเนินการ -> กลุ่มเป้าหมาย",
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