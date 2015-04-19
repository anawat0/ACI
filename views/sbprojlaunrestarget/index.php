<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\BaseArrayHelper;
	use yii\helpers\BaseJson;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['sbprojlaunrestarget/gridview', 'sb_proj_laun_res_id' => $sb_proj_laun_res_id]);?>";

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 250,
		autowidth: true,
	   	colNames:['ลำดับที่',
	   				'กลุ่มเป้าหมาย',
	   				'เป้าหมาย',
	   				'ทำได้',
	   				'Actions'],
	   	colModel:[
	   		{name:'id',index:'SB_PROJ_LAUN_RES_TARGET_ID', width:60, align:'center', sorttype:"int", editable: false},
	   		{name:'sb_proj_laun_target_id',index:'SB_PROJ_LAUN_TARGET_ID', width:350, editable: true, edittype:'select', editoptions:{style:'width:100%;', value:<?= BaseJson::encode(BaseArrayHelper::map($sbProjLaunTargets, 'SB_PROJ_LAUN_TARGET_ID', 'TARGET_NAME_TH')); ?>}},
	   		{name:'target_amount',index:'TARGET_AMOUNT', width:80, align:'right', sortable:true, editable: true, edittype:"text", editoptions:{width:'80'}},		
	   		{name:'actual_amount',index:'ACTUAL_AMOUNT', width:80, align:'right', editable: true, edittype:"text", editoptions:{width:'80'}},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		//caption: "WA001 จัดการเมนูหลัก",
	   	rowList:[5,10,15],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: function(){
	    	var pager = jQuery(this).getGridParam("pager");

		    Common.jqgrid.setEditMode(pager, false);
		    jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
	        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
	    },
	   	rowNum: 5,
	   	sortname: 'SB_PROJ_LAUN_RES_TARGET_ID',
	    sortorder: 'asc',
	    page: 1,
	});


	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		edit: false,
		add: false,
		del: true,
			refresh: false
	}, 
	{}, 
	{
		height: 230,
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