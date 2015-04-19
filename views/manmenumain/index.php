<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\web\Utils;
	AppAsset::register($this);

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['manmenumain/gridview']);?>";

var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SEQ'});

function gotoView1(t){
    var url='<?=Url::to(['manmenusub/'])?>';
	url += '&menumain=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 450,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
   				'ชื่อเมนู',
   				'รายละเอียด',
   				'ลำดับ',
   				'สถานะ',
   				'เมนูย่อย',
   				'Actions'],
   	colModel:[
   		{name:'id',index:'WA_MENU_MAIN_ID', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'nameth',index:'MENU_MAIN_NAME_TH', width:180, editable: true,editoptions:{maxlength:"255"}},
   		{name:'description',index:'DESCRIPTION', width:200, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"1",cols:"27"}},		
   		{name:'seq',index:'SEQ', width:50, align:'center',editable: true,editoptions:{maxlength:"3"}},
   		{name:'status',index:'STATUS', width:50, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
   		{name:'sub', index:'sub', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	    
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "WA001 จัดการเมนูหลัก",
   	rowList:[10,20,30],
   	pager: '#pagered',
    viewrecords: true,
    scrollOffset: 3,
    gridComplete: Common.jqgrid.onGridCompleted,
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