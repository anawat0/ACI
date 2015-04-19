<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	AppAsset::register($this);

	// if (\Yii::$app->user->isGuest) {
	// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
	// }

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
	var gridurl_1 = '<?=Url::to(['consttask/gridview', 'constproj'=>$constproj])?>';

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 410,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่',
				   	'ชื่อรายละเอียดงวดงาน',
				   	'วันที่เริ่มดำเนินการ',
				   	'วันที่สิ้นสุดดำเนินงาน',
				   	'งบประมาณ',
				   	'สถานะ',
				   	'Actions'],
	   	colModel:[
	   		{name:'id',index:'CONST_TASK_ID', width:35, align:'center', sorttype:"int", editable: false},
	   		{name:'taskNameTh',index:'TASK_NAME_TH',editable: true, editoptions: {maxlength:"255"}},
	   		{name:'startDate',index:'START_DATE', width:70, editable: true, editoptions: { dataInit: function(el) { setTimeout(function() { $(el).datepicker(); $(el).removeClass('form-control'); }, 200); } }},
	   		{name:'endDate',index:'END_DATE', width:70, align:'center', editable: true,editoptions: { dataInit: function(el) { setTimeout(function() { $(el).datepicker(); $(el).removeClass('form-control'); }, 200); } }},
	   		{name:'budget',index:'BUDGET', width:60, align:'center', editable: true,editoptions: {maxlength:"255"}},
	   		{name:'status',index:'STATUS', width:40, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
			Common.jqgrid.onCellSelect(this, id, iCol, cellcontent);
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "CONST001 โครงการงานก่อสร้างสนามกีฬา - งวดงาน ของโครงการงานก่อสร้างสนามกีฬา",
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	   	sortname: 'TASK_NAME_TH',
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