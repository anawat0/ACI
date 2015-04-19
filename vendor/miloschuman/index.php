<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use app\models\WA_PROVINCE;
use app\models\KPI_DIMENTION;
AppAsset::register($this);

if (\Yii::$app->user->isGuest) {
	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
}

?>

<?php 
	JqGridWidget::widget();

?>
<style type="text/css">
.form-filtering .form-group {
	padding: 0 0 10px 0;
}
</style>

<form id="frmsearch" class="form-horizontal form-filtering">

	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-1 right">
					<label>ประจำงบปี</label>
				</div>
				<div class="col-md-2">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="YEAR">
                	<?php Utils::getOptionsYears(); ?>
           		 	</select>
				</div>
				<div class="col-md-1 right">
					<label>ไตรมาส</label>
				</div>
				<div class="col-md-2">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="MONTH">
                	<?php Utils::getOptionsQuaters(); ?>
            		</select>
				</div>
				<div class="col-md-3 right">
					<label>มิติตัวชี้วัดผลการปฎิบัติราชการ</label>
				</div>
				<div class="col-md-3"><?=Html::dropDownList('', null, ArrayHelper::map(KPI_DIMENTION::find()->all(), 'KPI_DIMENTION_ID', 'DIMENTION_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter()'])?></div>
			</div>
		</div>
	</div>
</form>

<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['kpirec/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx:''});

/*function gotoView1(t){
    var url='<?=Url::to(['ssbclublaun/'])?>';
	url += '&consttask=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}*/

/*function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['ssbclublaun/getclubs']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            var jElm = jQuery('#frmsearch').find('select[name="SSB_CLUB_ID"]');
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.SSB_CLUB_ID+'">'+row.CLUB_NAME_TH+'</option>');
            });
        }, 'json');
    }
}*/

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 750,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่','ตัวชี้วัดผลการปฎิบัติราชการ','น้ำหนัก ร้อยละ','ผลดำเนินงาน','ค่าคะแนน','คะแนนถ่วงน้ำหนัก','เป้าหมายไตรมาส','หมายเหตุ'],
   	colModel:[
   		{name:'id',index:'KPI_DIMENTION_ID', width:50, align:'center', sorttype:"int", editable: false},
   		{name:'nameth',index:'NAME_TH',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'point',index:'POINT',editable: false,editoptions:{size:"27",maxlength:"255"}},
   		{name:'actualscore',index:'ACTUAL_SCORE',editable:false,editoptions:{size:"3",maxlength:"255"}},
   		{name:'targetscore',index:'TARGET_SCORE',width:100,editable: false,editoptions:{size:"3",maxlength:"255"}, },
        {name:'wt',index:'WT',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'targetquatercode',index:'TARGET_QUATER_CODE',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}},   
        {name:'targetquaterremark',index:'TARGET_QUATER_REMARK',width:35, align:'center', editable: true,editoptions:{size:"1",maxlength:"3"}}
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "KPI002",
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
    page: rtparams.page,
//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
});

jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
	edit: false,
	add: false,
	del: true
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
	multipleGroup: false
});

var myEditOptions = {
        aftersavefunc: function (rowid, response, postdata) {
            
        }
    };

jQuery("#rowed5").jqGrid('inlineNav',"#pagered", {edit:true, save:true, cancel:true,
		onbeforeeditfunc: function(){
	    },
	    onbeforeaddfunc: function(){
	    },
	    onafteraddfunc: function(){
	    },
	    addParams: {
			addRowParams: myEditOptions
	    }
	});

// setTimeout(function(){
// 	Common.jqgrid.onFilter();
// }, 500);
</script>