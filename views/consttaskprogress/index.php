<?php
  use app\assets\AppAsset;
  use himiklab\jqgrid\JqGridWidget;
  use yii\helpers\Url;
  use yii\helpers\ArrayHelper;
  use yii\helpers\Html;
  use yii\web\Utils;
  use app\models\WA_PROVINCE;
  use app\models\WA_AMPHOE;
  AppAsset::register($this);

  // if (\Yii::$app->user->isGuest) {
  // 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
  // }

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
            <div class="col-md-1 right" >  <label>ปีที่เริ่มสัญญา</label> </div>
            <div class="col-md-1" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="CONST_TASK.START_DATE"><?php Utils::getOptionsYears(); ?></select></div>
            <div class="col-md-1 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-2" ><?=Html::dropDownList('CONST_PROJ.PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);'])?></div>
            <div class="col-md-1 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-2" ><?=Html::dropDownList('CONST_PROJ.AMPHOE_CODE', null, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
            <div class="col-md-1 right" >  <label>โครงการ</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('CONST_PROJ.CONST_PROJ_ID', null, [],['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
        </div>
    </div> 
</div>
</form>

<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = '<?=Url::to(['consttaskprogress/gridview'])?>';

function gotoView1(t){
    var url='<?=Url::to(['consttaskimage/'])?>';
	url += '&consttask=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
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
	datatype: "json",
	height: 405,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่','ชื่อรายละเอียดงวดงาน','ความคืบหน้า %','ภาพประกอบ','เอกสารประกอบ','วันทีทำรายการล่าสุด'],
   	colModel:[
   		{name:'id',index:'CONST_TASK_ID', width:35, align:'center', sorttype:"int", editable: false},
   		{name:'taskNameTh',index:'TASK_NAME_TH',editable: true, editoptions: {size:"40",maxlength:"255"}},
   		{name:'progress',index:'PROGRESS', width:40, align:'center', editable: true, editoptions: {size:"10",maxlength:"255"}},
   		{name:'sub1',index:'sub1', width:40, align:'center', editable: false, formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
   		{name:'docs',index:'docs', width:70, align:'center', editable: false},
   		{name:'lastupdtime',index:'LAST_UPD_TIME', width:50, align:'center', editable: false}
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		Common.jqgrid.onCellSelect(this, id, iCol, cellcontent, [4]);
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "บันทึกติดตามงานก่อสร้างสนามกีฬา",
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagered',
   	sortname: 'TASK_NAME_TH',
    viewrecords: true,
    sortorder: "asc",
    scrollOffset: 3,
    gridComplete: Common.jqgrid.onGridCompleted
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
}).navButtonAdd('#pagered',Common.jqgrid.getSaveButton()
).navButtonAdd('#pagered',Common.jqgrid.getCancelButton());

</script>