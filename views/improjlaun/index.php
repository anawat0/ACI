<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Json;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['improjlaun/gridview']);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'IM_PROJ_LAUN_ID'});

	function gotoCreate(t){
		var url = "<?=Url::to(['improjlaun/form']);?>";
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	function getUrlCreate(t){
		var url = "<?=Url::to(['improjlaun/form']);?>";
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		return url;
	}

	function gotoEdit(t){
		var url = "<?=Url::to(['improjlaun/form']);?>";
		url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	function gotoImProjLaunTarget(t){
		var url = "<?=Url::to(['improjlauntarget/']);?>";
		url += '&im_proj_laun_id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 410,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับ', 
				   	'แผนงาน/โครงการหลัก', 
				   	'ประจำปี', 
				   	'ผู้รับผิดชอบ', 
				   	'งบประมาณ',
				   	'สถานะ',
				   	'กลุ่มเป้าหมาย',
				   	'แก้ไข'],
	   	colModel:[
	   		{name:'seq', index:'seq', width:50, align:'center', sorttype:"int", sortable:true, editable: false},
	   		{name:'im_proj_name', index:'IM_PROJ_NAME', width:300, sortable:true, editable: false},
	   		{name:'year', index:'YEAR', width:80, sortable:true, editable: false},
	   		{name:'proj_resp', index:'PROJ_RESP', width:200, sortable:true, editable: false},
	   		{name:'budget', index:'BUDGET', width:100, sorttype:"int", sortable:true, editable: false},
	   		{name:'status', index:'STATUS', width:80, sortable:true, editable:false, align:'center'},
	   		{name:'im_proj_laun_target', index:'IM_PROJ_LAUN_TARGET', width:80, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoImProjLaunTarget(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
	   		{name:'edit', index:'EDIT', width:80, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoEdit(this);" class="glyphicon glyphicon-pencil"></span>'; }},
	   	],
		onSelectRow: function(rowid, status, e){
		},
		onCellSelect: function(id, iCol, cellcontent){
			var pager = jQuery(this).getGridParam("pager");
			
			if ( iCol==9 || iCol==10  ) { //TODO: set index column for no action (edit & select) when click this index cell.
				Common.jqgrid.restoreRow(this, id, pager);
				jQuery(this).setSelection(id, false);
			}else if(iCol>1){
				if(id && id!==lastsel2){ 
					 Common.jqgrid.restoreRow(this, id, pager);
			    }
				jQuery(this).editRow(id, true
				, {/*oneditfunc*/}
				, function(res){jQuery(this).trigger('reloadGrid'); g_OnEditRowId=undefined;} //successfunc
				, undefined //url
				, {/*extraparam*/}
				, {/*aftersavefunc*/}
				, {/*errorfunc*/}
				, function(){jQuery(this).trigger('reloadGrid'); g_OnEditRowId=undefined;}/*afterrestorefunc*/
				)
				.setSelection(id, false);
				Common.jqgrid.setEditMode(pager, true);
				g_OnEditRowId = id;
			}else{
				Common.jqgrid.restoreRow(this, id, pager);
			}
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'IM002 โครงการที่กำลังดาเนินการ สอม.',
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
	}).navButtonAdd('#pagered', Common.jqgrid.getAddButton(getUrlCreate));
</script>