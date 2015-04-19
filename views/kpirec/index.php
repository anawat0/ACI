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

?>

<?php 
	JqGridWidget::widget();

	$budget_year = Yii::$app->getRequest()->getQueryParam('budget_year');
	$quater = Yii::$app->getRequest()->getQueryParam('quater');
	$dimention_id = Yii::$app->getRequest()->getQueryParam('dimention_id');
	
	$budget_year = (empty($budget_year)?(intval(date('Y'))+543):$budget_year);
	
?>
<style type="text/css">
.form-filtering .form-group {
	padding: 0 0 10px 0;
}
#frminput input{
	text-align: center;
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
					<select class="form-control"
						name="YEAR" onchange="onChangeBudgetYear(this,'V_KPI_ALL_INDC.KPI_DIMENTION_ID');">
                	<?php Utils::getOptionsYears($budget_year,false); ?>
           		 	</select>
				</div>
				<div class="col-md-1 right">
					<label>ไตรมาส</label>
				</div>
				<div class="col-md-2">
					<select class="form-control"
						name="QUATER" onchange="this_onFilter()">
                	<?php Utils::getOptionsQuaters($quater); ?>
            		</select>
				</div>
				<div class="col-md-3 right">
					<label>มิติตัวชี้วัดผลการปฎิบัติราชการ</label>
				</div>
				<div class="col-md-3"><?=Html::dropDownList('V_KPI_ALL_INDC.KPI_DIMENTION_ID', $dimention_id, ArrayHelper::map(KPI_DIMENTION::find()->where(['BUDGET_YEAR'=>$budget_year])->all(), 'KPI_DIMENTION_ID', 'DIMENTION_NAME_TH'),['class'=>'form-control', 'onchange'=>'this_onFilter()'])?></div>
			</div>
		</div>
	</div>
</form>

<form id="frminput" action="<?=Url::to(['kpirec/save']);?>" method="post" >

<table id="rowed5"></table>

<div style="text-align: center;padding: 20px;">
	<input type="hidden" name="return[budget_year]" value="" />
	<input type="hidden" name="return[quater]" value="" />
	<input type="hidden" name="return[dimention_id]" value="" />
	<a onclick="setParamBeforeSubmit(); jQuery(this).closest('form').submit();" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
</div>
</form>


<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['kpirec/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx:'', rows:1000});

function setParamBeforeSubmit(){
	jQuery('input[name="return[budget_year]"]').val(jQuery('[name="YEAR"]').val());
	jQuery('input[name="return[quater]"]').val(jQuery('[name="QUATER"]').val());
	jQuery('input[name="return[dimention_id]"]').val(jQuery('[name="V_KPI_ALL_INDC.KPI_DIMENTION_ID"]').val());
}

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

function this_onFilter(){
	if(!Common.utils.isNullOrBlank(jQuery('[name="V_KPI_ALL_INDC.KPI_DIMENTION_ID"]').val())){
		Common.jqgrid.onFilter();
	}
}

function onChangeBudgetYear(t, elm_relate_name){
	var budget_year=t.value;
    var data = {budget_year : budget_year};
    var jElm = jQuery('select[name="'+elm_relate_name+'"]');
    if(budget_year!=''){
        jQuery.post('<?=Url::to(['kpirec/getddldimention']);?>', data, function(data){
            //on ajax success.
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.KPI_DIMENTION_ID+'">'+row.DIMENTION_NAME_TH+'</option>');
            });
            jElm.change();
        }, 'json');
    }else{
    	jElm.find('option').remove();
    	jElm.change();
    }
}

var g_row=null;

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "local",
	height: 750,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่','ตัวชี้วัดผลการปฎิบัติราชการ','น้ำหนัก ร้อยละ','ผลดำเนินงาน','ค่าคะแนน','คะแนนถ่วงน้ำหนัก','เป้าหมายไตรมาส','หมายเหตุ','quater','dimentionid','fstid','secdid','thrdid','frthid','recid'],
   	colModel:[
   		{name:'num',index:'NUM', width:30, align:'center', sortable:false, editable: false, hidden: true, editrules: { edithidden: true }, hidedlg: true},
   		{name:'nameth',index:'NAME_TH', sortable:false,editable: false},
   		{name:'point',index:'POINT',width:45, align:'center', formatoptions: {keys: true}, sortable:false,editable: true,editoptions:{size:"3",maxlength:"3"}},
   		{name:'actualscore',index:'ACTUAL_SCORE',width:40, align:'center', sortable:false,editable:true,editoptions:{size:"3",maxlength:"3"}},
   		{name:'targetscore',index:'TARGET_SCORE',width:30, align:'center', sortable:false,editable: true,editoptions:{size:"2",maxlength:"3"}},
        {name:'wt',index:'WT',width:45, align:'center',width:50, align:'center', sortable:false, editable: true,editoptions:{size:"5",maxlength:"3"}},   
        {name:'targetquatercode',index:'TARGET_QUATER_CODE',width:45, align:'center', sortable:false, editable: true,editoptions:{size:"4",maxlength:"3"}},   
        {name:'targetquaterremark',index:'TARGET_QUATER_REMARK',width:30, align:'center', sortable:false, editable: true,editoptions:{size:"3",maxlength:"3"}},
        {name:'quater', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
        {name:'dimentionid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true},
        {name:'fstid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true},
        {name:'secdid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true},
        {name:'thrdid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true},
        {name:'frthid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true},
        {name:'recid', editable: true, hidden: true, editrules: { edithidden: true }, hidedlg: true}
        /*{name:'quater', width:20, editable: true},
        {name:'dimentionid', width:20, editable: true},
        {name:'fstid', width:20, editable: true},
        {name:'secdid', width:20, editable: true},
        {name:'thrdid', width:20, editable: true},
        {name:'frthid', width:20, editable: true},
        {name:'recid', width:20, editable: true}*/
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		
	},
// 	multiselect: true,
	editurl: gridurl_1,
	caption: "บันทึกผล ตัวชี้วัดผลการปฏิบัติราชการ",
   	rowList:[1000],
    viewrecords: true,
    scrollOffset: 3,
    beforeSelectRow: function(rowid, e) {
        return false;
    },
    gridComplete: function(){ 

    	var $this = $(this), rows = this.rows, l = rows.length, i, row;
    	var jRow;

		var func_setInput = function(jRow, row_number){
			var fields = [{input_name :'quater', field_name:"QUATER"},
			  			  {input_name :'point', field_name:"POINT"},
			              {input_name :'actualscore', field_name:"ACTUAL_SCORE"},
			              {input_name :'targetscore', field_name:"TARGET_SCORE"},
			              {input_name :'wt', field_name:"WT"},
			              {input_name :'targetquatercode', field_name:"TARGET_QUATER_CODE"},
			              {input_name :'targetquaterremark', field_name:"TARGET_QUATER_REMARK"},
			              {input_name :'dimentionid', field_name:"KPI_DIMENTION_ID"},
			              {input_name :'fstid', field_name:"KPI_FST_ID"},
			              {input_name :'secdid', field_name:"KPI_SECD_ID"},
			              {input_name :'thrdid', field_name:"KPI_THRD_ID"},
			              {input_name :'frthid', field_name:"KPI_FRTH_ID"}];

			var record_id = jQuery(jRow).attr('id');
			if(record_id=='null'){
				record_id= 'null_'+row_number;
			}
			
			jQuery.each(fields, function(){
				var val = jQuery.trim(jRow.find('td[aria-describedby="rowed5_'+this.input_name+'"]').text());
				jRow.find('td[aria-describedby="rowed5_'+this.input_name+'"]').html('<input type="text" class="'+this.input_name+'" value="'+val+'" name="kpirec['+record_id+']['+this.field_name+']" size="2" />');
			});
		};

		var func_set_dimention_name = function(jRow, indent_step, number){

			var indent_text = '';
			switch(indent_step){
			case 0:
				break;
			case 1: indent_text='    ';
				break;
			case 2: indent_text='        ';
				break;
			case 3: indent_text='              ';
				break;
			case 4: indent_text='                    ';
				break;
			}
			
			jRow.find('td[aria-describedby="rowed5_nameth"]').prepend(indent_text+'');
		};

		var parent_step = 0;
		var isFirstLoop = false;
		
    	for (i = l-1; i >= 1 ; i--) {
			//console.log(rows[i]);
			
			jRow = jQuery(rows[i]);

			var dimention = jRow.find('td[aria-describedby="rowed5_dimentionid"]').text();
			var fst = jRow.find('td[aria-describedby="rowed5_fstid"]').text();
			var secd = jRow.find('td[aria-describedby="rowed5_secdid"]').text();
			var thrd = jRow.find('td[aria-describedby="rowed5_thrdid"]').text();
			var frth = jRow.find('td[aria-describedby="rowed5_frthid"]').text();

			isFirstLoop = (i == l-1);
			if(isFirstLoop){ //The first loop.
				func_setInput(jRow, i);

				if(!Common.utils.isNullOrBlank(frth)){
					func_set_dimention_name(jRow, 4);
					parent_step=4;
				}else if(!Common.utils.isNullOrBlank(thrd)){
					func_set_dimention_name(jRow, 3);
					parent_step=3;
				}else if(!Common.utils.isNullOrBlank(secd)){
					func_set_dimention_name(jRow, 2);
					parent_step=2;
				}else if(!Common.utils.isNullOrBlank(fst)){
					func_set_dimention_name(jRow, 1);
					parent_step=1;
				}else if(!Common.utils.isNullOrBlank(dimention)){
					func_set_dimention_name(jRow, 0);
					parent_step=0;
				}
			}else{

				if(!Common.utils.isNullOrBlank(frth)){
					func_set_dimention_name(jRow, 4);
					func_setInput(jRow, i);
					parent_step=4;
				}else if(!Common.utils.isNullOrBlank(thrd)){
					func_set_dimention_name(jRow, 3);
					if(parent_step<=3) func_setInput(jRow, i);
					parent_step=3;
				}else if(!Common.utils.isNullOrBlank(secd)){
					func_set_dimention_name(jRow, 2);
					if(parent_step<=2) func_setInput(jRow, i);
					parent_step=2;
				}else if(!Common.utils.isNullOrBlank(fst)){
					func_set_dimention_name(jRow, 1);
					if(parent_step<=1) func_setInput(jRow, i);
					parent_step=1;
				}else if(!Common.utils.isNullOrBlank(dimention)){
					func_set_dimention_name(jRow, 0);
					if(parent_step<=0) func_setInput(jRow, i);
					parent_step=0;
				}
				
			}
			
			//console.log(targetquaterremark);
    	}

    	jQuery('input.quater').val(jQuery('select[name="QUATER"] option:selected').text());
    	
        //TODO: set grid height via row nums.
        jQuery(this).setGridHeight(l*40);

        jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
    },
   	rowNum:rtparams.rows,
   	sortname: rtparams.sidx,
    sortorder: rtparams.sord,
    page: rtparams.page,
//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
});

function saveData(){
	jQuery("#rowed5")
}

setTimeout(function(){
	this_onFilter();
}, 500);
</script>