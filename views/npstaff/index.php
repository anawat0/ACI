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
use yii\web\Authentication;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);

// if (\Yii::$app->user->isGuest) {
// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// }
$user_info = Authentication::getAuthenInfo();

?>

<?php 
	JqGridWidget::widget();

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
            <div class="col-md-3" >
              <?php 
              $province = '';
              $amphoe = '';
              echo Utils::getDDLProvince(null, null, 'NP_STAFF.ORG_PROVINCE_CODE',
              		'PROVINCE_CODE',
              		'PROVINCE_NAME_TH',
              		$province,
              		$user_info,
              		['onchange' => 'Common.ajax.onChangeProvince(this, \'NP_STAFF.ORG_AMPHOE_CODE\', \''.$amphoe.'\')']
              );
              ?>
            </div>
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" >
              <?php 
              echo Utils::getDDLAmphoe(null, null, 'NP_STAFF.ORG_AMPHOE_CODE',
              		'AMPHOE_CODE',
              		'AMPHOE_NAME_TH',
              		$amphoe,
              		$user_info,
              		$province,
              		['onchange' => 'Common.jqgrid.onFilter();']
              );
              ?>
            </div>
            <div class="col-md-1"><a onclick="Common.jqgrid.onFilter();" class="btn btn-success btn-md"><span class="glyphicon glyphicon-search"></span> ค้นหา</a> </div>
         </div> 
  </div> 
</div>
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
            jElm.append('<option value="">กรุณาเลือก</option>');
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
	height: 720,
	width: jQuery('div.content').width()-20,
	colNames:['ลำดับที่', 'ประจำปี', 'ชื่อ - สกุล', 'ที่ทำงาน', 'เบอร์โทรศัพท์', 'รูปภาพ','แก้ไข'],
   	colModel:[
        {name:'id',index:'STAFF_ID', width:60, align:'center', sorttype:"int", editable: false},
		{name:'year',index:'YEAR', width:65, align:'center', sortable:true, editable: false},
		{name:'fullName',index:'FIRST_NAME', width:190, sortable:true, editable: false},
		{name:'orgType',index:'ORG_TYPE', width:380, sortable:true, editable: false},
		{name:'mobileNo',index:'MOBILE_NO', width:140, align:'center', sortable:true, editable: false},
   		{name:'fileToUploadTmp',index:'IMAGE_PATH', width:80, align:'center', editable: false, formatter: Common.jqgrid.playerPicFormatter},
		{name:'edit', index:'edit', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView2(this);" class="glyphicon glyphicon-pencil"></span>'; }},
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
  Common.jqgridOptions.navGridDelete,  
  {
  	multipleSearch: true,
  	multipleGroup: false
  }).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlView3));
</script>