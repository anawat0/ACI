<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\web\Authentication;

	AppAsset::register($this);
	if (!Authentication::isLoggedIn()) {
		\Yii::$app->getResponse()->redirect(['site/login']);
	}

	JqGridWidget::widget();
?>

<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>


<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['wagroup/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'GROUP_NAME_TH'});

function gotoView1(t){
	var url='<?=Url::to(['wagroupuser/'])?>';
	url += '&wagroup=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}
function gotoView2(t){
	var url='<?=Url::to(['wagrouprole/'])?>';
	url += '&wagroup=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 450,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
	   			'ชื่อกลุ่ม', 
	   	 		'ผู้ใช้', 
	   			'สิทธิ์การใช้งาน', 
	   			'LDAP Mapping',
	   			'Actions'],
   	colModel:[
   		{name:'id',index:'WA_GROUP_ID', width:40, align:'center', sorttype:"int", editable: false},
   		{name:'nameth',index:'GROUP_NAME_TH',editable: true,editoptions:{maxlength:"255"}},
   		{name:'sub1', index:'sub1', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
   		{name:'sub2', index:'sub2', width:100, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView2(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
   		{name:'ldaporgcode',index:'LDAP_ORG_CODE', width:150, editable: true,editoptions:{maxlength:"255"}},
   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "WA002 จัดการกลุ่มผู้ใช้งาน",
   	rowList:[10,20,30],
   	pager: '#pagered',
    viewrecords: true,
    scrollOffset: 3,
    gridComplete: function(){
    	Common.jqgrid.onGridCompleted();
    },
   	rowNum:rtparams.rows,
   	sortname: rtparams.sidx,
    sortorder: rtparams.sord,
    page: rtparams.page
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