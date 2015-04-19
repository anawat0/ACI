<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use app\models\NP_STAFF;
use yii\web\Utils;
use yii\bootstrap\ActiveForm;
	use yii\web\Authentication;

	AppAsset::register($this);

	$user_info = Authentication::getAuthenInfo();

?>

<?php 
	JqGridWidget::widget();
	
	$year = Yii::$app->getRequest()->getQueryParam('year');
	$province = Yii::$app->getRequest()->getQueryParam('province');
	$amphoe = Yii::$app->getRequest()->getQueryParam('amphoe');

?>

<style type="text/css">
.form-horizontal .form-group{
    padding: 0 0 10px 0;
}
</style>

<form id="frmsearch" class="form-horizontal">
    
<div class="panel panel-primary">
  <div class="panel-body">        
       <div class="form-group">
               <div class="col-md-2 right" >  <label>ประจำปี</label> </div>
               <div class="col-md-2" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="YEAR"><?php Utils::getOptionsYears(); ?></select></div>
       
        </div>
        <div class="form-group">
            <div class="col-md-2 right" >  <label>ชื่อ</label> </div>
            <div class="col-md-3" ><?=Html::textInput('FIRST_NAME', null , ['class'=>'form-control'])?></div>
            <div class="col-md-1 right" >  <label>นามสกุล</label> </div>
            <div class="col-md-3 right" ><?=Html::textInput('LAST_NAME', null , ['class'=>'form-control'])?></div>
        </div>   
        <div class="form-group">
            <div class="col-md-2 right" >  <label>ที่ทำงาน</label> </div>
            <div class="col-md-4" >  
                <label><input name="ORG_TYPE" type="radio" value="1"/>&nbsp;สำนักการท่องเที่ยว&nbsp;
			           <input name="ORG_TYPE" type="radio" value="2"/>&nbsp;ที่ว่าการอำเภอ : 
			    </label>     
            </div>
        </div>   
        <div class="form-group">
            <div class="col-md-2 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?php 
            	//echo Html::dropDownList('NP_STAFF.ORG_PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);'])
	            echo Utils::getDDLProvince_AutoComplete(null, null, 'NP_STAFF.ORG_PROVINCE_CODE',
	            		'PROVINCE_CODE',
	            		'PROVINCE_NAME_TH',
	            		$province,
	            		$user_info,
	            		['onchange' => 'Common.ajax.onChangeProvince(this, \'NP_STAFF.ORG_AMPHOE_CODE\', \''.$amphoe.'\')']
	            );
            	?></div>
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" ><?php 
            	//echo Html::dropDownList('NP_STAFF.ORG_AMPHOE_CODE', null, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])
	            echo Utils::getDDLAmphoe_AutoComplete(null, null, 'NP_STAFF.ORG_AMPHOE_CODE',
	            		'AMPHOE_CODE',
	            		'AMPHOE_NAME_TH',
	            		$amphoe,
	            		$user_info,
	            		$province,
	            		['onchange' => '']
	            );
            	?></div>
        </div>
        <div class="footcontentbutton">
            <a onclick="Common.jqgrid.onFilter();" class="btn btn-success btn-md"><span class="glyphicon glyphicon-search"></span> ค้นหา</a> 
            <a onclick="getReportCADetail();" class="btn btn-success btn-md"><span class="glyphicon glyphicon-search"></span> พิมพ์</a>
        </div>  
  </div> 
</div>
</form>

<form name="FormClearReports" action="http://report.dpe.go.th:9000" method="POST" target="_blank" >
        <input type="hidden" name="report" >
	    <input type="hidden" name="promptP_YEAR" >
        <input type="hidden" name="promptP_FIRST_NAME" >
        <input type="hidden" name="promptP_LAST_NAME" >
        <input type="hidden" name="promptP_ORG_TYPE" >
        <input type="hidden" name="promptP_ORG_PROVINCE_CODE" >
        <input type="hidden" name="promptP_ORG_AMPHOE_CODE" >
        
        <script type="text/javascript">
            function getReportCADetail(){

                var year = $('select[name=YEAR').val();
                var firstName = $('select[name=FIRST_NAME').val();
                var lastName = $('select[name=LAST_NAME').val();
                var orgType = $("input[type='radio']:checked").val();
                
                if (year === undefined) {
                	year = "";
                }
                if (firstName === undefined) {
                	firstName = "";
                }
                if (lastName === undefined) {
                	lastName = "";
                }
                if (orgType === undefined) {
                	orgType = "";
                }

                var form = jQuery('form[name="FormClearReports"]');                
                form.find('[name="report"]').val("file:/C:/Program Files/i-net Clear Reports/startpage/npStaffReport1.pdf");
                form.find('[name="promptP_YEAR"]').val($('select[name=YEAR').val());
                form.find('[name="promptP_FIRST_NAME"]').val($('select[name=FIRST_NAME').val());
                form.find('[name="promptP_LAST_NAME"]').val($('select[name=LAST_NAME').val());
                form.find('[name="promptP_ORG_TYPE"]').val($('select[name=ORG_TYPE').val());
                form.find('[name="promptP_ORG_PROVINCE_CODE"]').val($('select[name=ORG_PROVINCE_CODE').val());
                form.find('[name="promptP_ORG_AMPHOE_CODE"]').val($('select[name=ORG_AMPHOE_CODE').val());
                
                form.submit() ;
          }
        </script>
</form>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['npstaff/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'STAFF_ID'});

function gotoView1(t){
    var url='<?=Url::to(['addstaff/'])?>';
	url += '&npstaff=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function gotoView2(t){
    var url='<?=Url::to(['npstaff/add'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function getUrlView3(){
	var url='<?=Url::to(['npstaff/add'])?>';
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
            var jElm = jQuery('#frmsearch').find('select[name="NP_STAFF.ORG_AMPHOE_CODE"]');
            jElm.find('option').remove();
            console.log(data);
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
	height: 720,
	width: jQuery('div.content').width()-20,
	colNames:['ลำดับที่', 'ประจำปี', 'ชื่อ - สกุล', 'ที่ทำงาน', 'เบอร์โทรศัพท์', 'รูปภาพ'],
   	colModel:[
        {name:'id',index:'STAFF_ID', width:60, align:'center', sorttype:"int", editable: false},
		{name:'year',index:'YEAR', width:65, align:'center', sortable:true, editable: false},
		{name:'fullName',index:'FIRST_NAME', width:190, sortable:true, editable: false},
		{name:'orgType',index:'ORG_TYPE', width:380, sortable:true, editable: false},
		{name:'mobileNo',index:'MOBILE_NO', width:140, align:'center', sortable:true, editable: false},
   		{name:'fileToUploadTmp',index:'IMAGE_PATH', width:80, align:'center', editable: false, formatter: Common.jqgrid.playerPicFormatter},
   	],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
		
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "NP008",
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
{
	reloadAfterSubmit: false
}, 
{
	multipleSearch: true,
	multipleGroup: false
}).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlView3));

</script>