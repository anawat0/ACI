<?php
	use yii\web\Utils;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\Json;
	use yii\helpers\ArrayHelper;
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ################################################################################## -->
<script type="text/javascript">
	var gridurl_1 = "<?= Url::to(['immainproj/gridview']); ?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?= $rtparams; ?>', {sidx: 'IM_MAIN_PROJ_ID'});

	function gotoImProj(t){
		var imMainProjid = jQuery(t).closest('tr[role="row"]').attr('id');
	    var url = '';

	    // TODO: check edit mode
	    if (!isNaN(imMainProjid)) {
	    	url += "<?= Url::to(['improj/index']); ?>";
			url += '&im_main_proj_id=' + imMainProjid;
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
					'โครงงาน/แผนการหลัก (ไทย)*', 
					'โครงงาน/แผนการหลัก (ENG)', 
					'ประจำปี*', 
					'สถานะ*', 
					'โครงการ',
					'Actions'],
	   	colModel:[
	        {name:'id',index:'IM_MAIN_PROJ_ID', width:80, align:'center', sorttype:"int", editable: true, editable: false},
			{name:'main_proj_name_th',index:'MAIN_PROJ_NAME_TH', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'main_proj_name_en',index:'MAIN_PROJ_NAME_EN', width:250, sortable:true, editable: true, edittype:"text", editoptions:{maxlength:"255"}},
			{name:'budget_year', index:'BUDGET_YEAR', width:100, sortable:true, sorttype:"int", editable: true, align:'center', edittype:"select", editoptions:{value:<?= Json::encode(Utils::getArrYears(false)); ?>}},
			{name:'status',index:'STATUS', width:100, sortable:true, editable: true, align:'center', edittype:"checkbox", editrules:{required:true}, editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'sub', index:'sub', width:100, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoImProj(this);" class="ui-icon ui-icon-circle-zoomout"></span>';}},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'แสดง/บันทึก แผนงาน/โครงการหลัก',
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
			addRowParams: myEditOptions,
			errorfunc: function() {
				console.log('1');
			}
	    },
	    editParams: {
	    	editRowParams: myEditOptions,
			errorfunc: function() {
				console.log('1');
			}
		} // http://www.trirand.com/jqgridwiki/doku.php?id=wiki%3Aform_editing
	});
</script>