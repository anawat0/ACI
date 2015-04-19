<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Json;
	use yii\web\Utils;

	AppAsset::register($this);

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['sbmainproj/gridview']);?>";//"/browallia/web/index.php?r=sbmainproj/gridview";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SB_MAIN_PROJ_ID'});

	// TODO: goto sub menu page
	function gotoProj(t) {
		var id = jQuery(t).closest('tr[role="row"]').attr('id');
		var url = "";

		// TODO: check edit mode
		if (!isNaN(id)) {
			url += "<?=Url::to(['sbproj/'])?>";
			url += '&sb_main_proj_id=' + id
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
				   	'ชื่อแผนงาน/โครงการหลัก (ไทย)', 
				   	'ชื่อแผนงาน/โครงการหลัก (Eng)', 
				   	'ประจำปี', 
				   	'สถานะ', 
				   	'โครงการ',
				   	'Action'],
	   	colModel:[
	   		{name:'id',index:'SB_MAIN_PROJ_ID', width:50, sorttype:"int", align:'center', sortable:true, editable: false},
	   		{name:'nameth',index:'MAIN_PROJ_NAME_TH', width:320, sortable:true, editable: true, editoptions:{width:"350",maxlength:"255"}},
	   		{name:'nameen',index:'MAIN_PROJ_NAME_EN', width:320, sortable:true, editable: true, editoptions:{width:"350",maxlength:"255"}},
	   		{name:'budget_year',index:'BUDGET_YEAR', width:100, sortable:true, editable: true, align:'center', edittype:"select", editoptions:{width:"80", value:<?= Json::encode(Utils::getArrYears(false)); ?>}},
	   		{name:'status',index:'STATUS', width:100, sortable:true, editable: true, align:'center', edittype:"checkbox",editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'sub', index:'sub', width:100, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoProj(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
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
	   	rowNum: rtparams.rows,
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