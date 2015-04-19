<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;

	AppAsset::register($this);

?>
<?php 
	JqGridWidget::widget();
?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['ssbprojlaun/gridview']);?>";// "/browallia/web/index.php?r=rbprojlaun/gridview";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SSB_PROJ_LAUN_ID'});

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่',
	   				'ประจำปี',
	   				'แผนงาน/โครงการ *', 
	   				'หน่วยงานผู้รับผิดชอบ *', 
	   				'ผู้รับผิดชอบโครงการ *', 
	   				'วันที่ดำเนินการ *', 
	   				'ถึง *', 
	   				'ระดับความพึงพอใจ *', 
	   				'งบประมาณ *', 
	   				'สถานะ *',
	   				'Action'],
	   	colModel:[
	   		{name:'seq', index:'seq', width:50, align:'center', sortable:false, editable: false},
	   		{name:'budget_year', index:'BUDGET_YEAR', width:80, sortable:true, sorttype:'int', editable: true},
	   		{name:'ssb_proj_id', index:'SSB_PROJ_ID', width:120, sortable:true, editable: true, edittype:'select', editoptions:{width:"200", value:<?= $listRbProj; ?>}},
	   		{name:'wa_section_id', index:'WA_SECTION_ID', width:120, sortable:true, editable: true, edittype:'select', editoptions:{width:"200", value:<?= $listWaSection; ?>}},
	   		{name:'proj_responsible', index:'PROJECT_RESPONSIBLE', width:100, sortable:true, editable:true},
	   		{name:'from_date', index:'FROM_DATE', width:100, sortable:true, editable: true, editoptions: { dataInit: function(el) { setTimeout(function() { $(el).datepicker(); $(el).removeClass('form-control'); }, 200); } }},
	   		{name:'to_date', index:'TO_DATE', width:80, sortable:true, editable: true, editoptions: { dataInit: function(el) { setTimeout(function() { $(el).datepicker(); $(el).removeClass('form-control'); }, 200); } }},
	   		{name:'satisfaction_level', index:'SATISFACTION_LEVEL', width:80, sorttype:"int", sortable:true, editable: true, edittype:'text', align:'center', editoptions:{maxlength:"2"}},
	   		{name:'budget', index:'BUDGET', width:80, sorttype:"int", sortable:true, editable: true, edittype:'text', align:'center'},
	   		{name:'status', index:'STATUS', width:50, sortable:true, editable: true, align:'center', edittype:"checkbox",editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(rowid, status, e){
		},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'โครงการที่กาลังดาเนินการ สวฬ.',
	   	rowNum: 10,
	   	rowList: [10,20,30],
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
		height: 300,
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
	jQuery("#rowed5").jqGrid('inlineNav',"#pagered", {edit:true, save:true, cancel:true,
		onbeforeeditfunc: function(){
	    },
	    onbeforeaddfunc: function(){
	    },
	    onafteraddfunc: function(data){
	    	console.log(data);
	    },
	    addParams: {
			addRowParams: myEditOptions
	    },
	    editParams: myEditOptions
	});
</script>