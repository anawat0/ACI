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
var gridurl_1 = "<?=Url::to(['manmenusub/gridview', 'menumain'=>$menumain]);?>";

function gotoView1(t){
	//TODO: goto sub menu page
}

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 450,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
   				'ชื่อเมนูย่อย',
   				'Path',
   				'ลำดับแสดง',
   				'สถานะ',
   				'Actions'],
   	colModel:[
   		{name:'no',index:'WA_MENU_SUB_ID', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'nameth',index:'MENU_SUB_NAME_TH', width:200,editable: true,editoptions:{maxlength:"255"}},
   		{name:'menulink',index:'MENU_LINK', width:200,editable: true,editoptions:{maxlength:"255"}},
   		{name:'seq',index:'SEQ', width:80, align:'center',editable: true,editoptions:{maxlength:"3"}},
   		{name:'status',index:'STATUS', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "WA001 จัดการเมนูย่อย (<?php echo $waMenuMain['MENU_MAIN_NAME_TH']; ?>)",
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagered',
   	sortname: 'SEQ',
    viewrecords: true,
    sortorder: "asc",
    scrollOffset: 3,
    gridComplete: function(){
    	var pager = jQuery(this).getGridParam("pager");
    	Common.jqgrid.setEditMode(pager, false);
	    jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
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