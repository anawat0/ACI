<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;

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
			    	<?= Html::textInput('MAIN_PROJ_NAME_TH', 
			    						$imProjLaun->imProj['PROJ_NAME_TH'], 
			    						['class' => 'form-control',
			    						'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ประจำปี</label>
				<div class="col-sm-2">
			    	<?= Html::textInput('BUDGET_YEAR', 
			    						$imProjLaun['BUDGET_YEAR'], 
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
	var gridurl_1 = "<?=Url::to(['improjlauntarget/gridview', 'im_proj_laun_id' => $im_proj_laun_id]);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'IM_PROJ_LAUN_TARGET_ID'});

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 410,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่', 
				   	'ชื่อกลุ่มเป้าหมาย (ไทย)*', 
				   	'ชื่อกลุ่มเป้าหมาย (Eng)', 
				   	'เป้าหมาย (คน)*', 
				   	'สถานะ',
				   	'Actions'],
	   	colModel:[
	   		{name:'seq',index:'SEQ', width:60, sorttype:"int", sortable:true, editable: false, align:'center'},
	   		{name:'target_name_th',index:'TARGET_NAME_TH', width:350, sortable:true, editable: true, edittype:"text"},
	   		{name:'target_name_en',index:'TARGET_NAME_EN', width:350, sortable:true, editable: true, edittype:"text"},
	   		{name:'target_amount',index:'TARGET_AMOUNT', width:120, sortable:true,editable: true, edittype:"text", stype:"integer", align:'center'},
	   		{name:'status', index:'STATUS', width:80, sortable:true, editable: true, align:'center', edittype:"checkbox", editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	 	],
		onSelectRow: function(rowid, status, e) {},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "IM002 แผนงาน-โครงการ ที่กำลังดำเนินการ -> กลุ่มเป้าหมาย",
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