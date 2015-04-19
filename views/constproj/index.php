<?php
  use app\assets\AppAsset;
  use himiklab\jqgrid\JqGridWidget;
  use yii\helpers\Url;
  use yii\helpers\ArrayHelper;
  use yii\helpers\Html;
  use yii\web\Utils;
  use app\models\WA_PROVINCE;
  use app\models\WA_AMPHOE;
  use app\models\CONST_PROJ;

  AppAsset::register($this);

  // 	if (\Yii::$app->user->isGuest) {
  // 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
  // 	}
	$year = Yii::$app->getRequest()->getQueryParam('year');
	$province = Yii::$app->getRequest()->getQueryParam('province');
	$amphoe = Yii::$app->getRequest()->getQueryParam('amphoe');

	JqGridWidget::widget();
?>

<style type="text/css">
.form-filtering .form-group{
    padding: 0 0 10px 0;
}
</style>

<form id="frmsearch" class="form-horizontal form-filtering">
    
<div class="panel panel-primary">
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2 right" >  <label>ปีที่เริ่มสัญญา</label> </div>
            <div class="col-md-2" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="START_DATE"><?php Utils::getOptionsYears($year); ?></select></div>
            
            <div class="col-md-1 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('CONST_PROJ.PROVINCE_CODE', $province, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);Common.jqgrid.onFilter();'])?></div>
            
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('CONST_PROJ.AMPHOE_CODE', $amphoe, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
        </div>
    </div> 
</div>
</form>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['constproj/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'PROJ_NAME_TH'});

function getFilterParam(){
	var frmsearch = jQuery('#frmsearch');
	
  var url = '&year='+frmsearch.find('[name="START_DATE"]').val();
	url += '&province='+frmsearch.find('[name="PROVINCE_CODE"]').val();
	url += '&amphoe='+frmsearch.find('[name="AMPHOE_CODE"]').val();
	return url;
}

function gotoView1(t){
    var url='<?=Url::to(['consttask/'])?>';
	url += '&constproj=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function gotoView2(t){
    var url='<?=Url::to(['constproj/add'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function getUrlView3(){
	var url='<?=Url::to(['constproj/add'])?>';
	url += getFilterParam();
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	return url;
}

function onChangeProvince(t){

    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            var jElm = jQuery('#frmsearch').find('select[name="CONST_PROJ.AMPHOE_CODE"]');
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
            });
            jElm.change();
        }, 'json');
    }
}


jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: function() {
    Common.jqgrid.onFilter();
  },
	height: 410,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่','ชื่อโครงการสร้างสนามกีฬา','งบประมาณ','ช่วงเวลาสัญญา','จังหวัด','อำเภอ','งวดงาน','สถานะ','แก้ไข'],
   	colModel:[
   		{name:'id',index:'CONST_PROJ_ID', width:50, align:'center', sorttype:"int", editable: false},
   		{name:'nameth',index:'PROJ_NAME_TH',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'budget',index:'BUDGET', editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'startdate',index:'START_DATE', width:100, editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'province',index:'PROVINCE_CODE', width:100, editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'amphoe',index:'AMPHOE_CODE', width:100, editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'sub', index:'sub', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
   		{name:'status',index:'STATUS', width:50, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
   		{name:'edit', index:'edit', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView2(this);" class="glyphicon glyphicon-pencil"></span>'; }},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "CON001",
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
  }).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlView3));
</script>