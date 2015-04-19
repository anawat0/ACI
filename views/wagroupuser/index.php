<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;

	AppAsset::register($this);

	// if (\Yii::$app->user->isGuest) {
	// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
	// }

	JqGridWidget::widget();
?>
<form class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">ชื่อกลุ่ม</label>
				<div class="col-sm-7">
			    	<?= Html::textInput('GROUP_NAME_TH', 
			    						$waGroup->GROUP_NAME_TH, 
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

<script type="text/javascript">
	var gridurl_1 = '<?=Url::to(['wagroupuser/gridview', 'wagroup'=>$waGroup->WA_GROUP_ID])?>';

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่',
	   				'ชื่อผู้ใช้',
	   				'รหัสผู้ใช้',
	   				'สถานะ',
	   				'Actions'],
	   	colModel:[
	   		{name:'id',index:'WA_USER_ID', width:40, align: 'center', sorttype:"int", editable: false},
	   		{name:'email',index:'EMAIL', width:150,editable: true,editoptions:{size:"20",maxlength:"255"}},
	   		{name:'password',index:'PASSWORD', width:150,editable: true, sortable:false, edittype:'password', editoptions:{size:"20",maxlength:"255"}},
	   		{name:'status',index:'STATUS', width:60, editable: true, align:'center', sortable:false, edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction}
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "WA002 จัดการผู้ใช้งาน ของกลุ่มผู้ใช้งาน",
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	   	sortname: 'EMAIL',
	    viewrecords: true,
	    sortorder: "asc",
	    scrollOffset: 3,
	    gridComplete: function(){
	    	Common.jqgrid.onGridCompleted();
	    }
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