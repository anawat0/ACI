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
<div>
	<?= $this->render('/layouts/_add_button'); ?>
</div>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['improjlaunrestarget/gridview', 'im_proj_laun_res_id' => $im_proj_laun_res_id]);?>";

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 220,
		autowidth: true,
	   	colNames:['ลำดับที่',
	   				'กลุ่มเป้าหมาย',
	   				'เป้าหมาย',
	   				'ทำได้',
	   				'สถานะ',
	   				'Actions'],
	   	colModel:[
	   		{name:'seq',index:'seq', width:80, align:'center', sorttype:"int", editable: false},
	   		{name:'im_proj_laun_target_id',index:'IM_PROJ_LAUN_TARGET_ID', width:400, editable: true, edittype:'select', editoptions:{value:<?= BaseJson::encode(BaseArrayHelper::map($imProjLaunTargets, 'IM_PROJ_LAUN_TARGET_ID', 'TARGET_NAME_TH')); ?>}},
	   		{name:'target_qt',index:'TARGET_QT', width:80, sortable:true, editable: true, edittype:"text"},		
	   		{name:'actual_qt',index:'ACTUAL_QT', width:80, align:'center', editable: true, edittype:"text"},
	   		{name:'status',index:'STATUS', width:80, sortable:true, editable: true, edittype:"checkbox", align:'center', editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(id) {},
		onCellSelect: function(id, iCol, cellcontent) {
		},
		multiselect: true,
		editurl: gridurl_1,
		//caption: "",
	   	rowList:[5,10,15],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: function(){ },
	   	rowNum: 5,
	   	sortname: 'IM_PROJ_LAUN_RES_ID',
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
		height: 250,
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