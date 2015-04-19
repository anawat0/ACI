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

<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = '<?=Url::to(['wagrouprole/gridview', 'wagroup'=>$wagroup])?>';

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 450,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
              'ชื่อเมนูหลัก',
              'ชื่อเมนูย่อย',
              'เข้าใช้งาน',
              'เพิ่มข้อมูล',
              'แก้ไขข้อมูล',
              'ลบข้อมูล',
              'Actions'],
   	colModel:[
   		{name:'id',index:'WA_GROUP_ROLE_ID', width:40, align:'center', sorttype:"int", editable: false},
   		{name:'menumainnameth',index:'MENU_MAIN_NAME_TH',editable: false,editoptions:{size:"20",maxlength:"255"}},
   		{name:'menusubnameen',index:'MENU_SUB_NAME_EN',editable: false,editoptions:{size:"20",maxlength:"255"}},
   		{name:'accessflag',index:'ACCESS_FLAG', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"Y:N"}},
   		{name:'addflag',index:'ADD_FLAG', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"Y:N"}},
   		{name:'editflag',index:'EDIT_FLAG', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"Y:N"}},
   		{name:'deleteflag',index:'DELETE_FLAG', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"Y:N"}},
      {name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "WA002 สิทธิ์การใช้งาน ของกลุ่มผู้ใช้งาน",
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagered',
   	sortname: 'MENU_MAIN_NAME_TH',
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
  	del: false,
			refresh: false
  }, 
  {}, 
  {
  	height: 280,
  	reloadAfterSubmit: true
  }, 
  {
  	reloadAfterSubmit: false
  }, 
  {
  	multipleSearch: true,
  	multipleGroup: true
  });

  var myEditOptions = {
      aftersavefunc: Common.jqgrid.aftersavefunc
  };
  jQuery("#rowed5").jqGrid('inlineNav',"#pagered", 
  {
    edit:true, add:false, save:true, cancel:true,
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