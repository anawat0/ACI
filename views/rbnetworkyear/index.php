<?php
  use app\assets\AppAsset;
  use himiklab\jqgrid\JqGridWidget;
  use yii\helpers\Url;
  use yii\helpers\Html;
  use yii\helpers\ArrayHelper;
  use yii\helpers\Json;
  use app\models\WA_REGION;
  use app\models\WA_PROVINCE;
  use app\models\WA_AMPHOE;
  use app\models\RB_NETWORK;
  use app\models\RB_NETWORK_YEAR;
  use yii\web\Utils;

  AppAsset::register($this);
 
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
               <div class="col-md-1 right" >  <label>ประจำปี</label> </div>
               <div class="col-md-2" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="YEAR"><?php Utils::getOptionsYears(); ?></select></div>
       
        </div>  
        
        <div class="form-group">
            <div class="col-md-1 right" >  <label>ภาค</label> </div>
            <div class="col-md-2" ><?=Html::dropDownList('REGION_CODE', null, ArrayHelper::map(WA_REGION::find()->all(), 'REGION_CODE', 'REGION_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeRegion(this);'])?></div>
            <div class="col-md-1 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->where(['REGION_CODE'=>'1'])->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);'])?></div>
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('AMPHOE_CODE', null, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
        </div>
    </div> 
</div>
</form>

<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['rbnetworkyear/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'NETWORK_NAME'});

function onChangeRegion(t){
    var region_id=t.value;
    var data = {region : region_id};
    if(region_id!=''){
        jQuery.post('<?=Url::to(['common/getddlprovinceregion']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            var jElm = jQuery('#frmsearch').find('select[name="PROVINCE_CODE"]');
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.PROVINCE_CODE+'">'+row.PROVINCE_NAME_TH+'</option>');
            });
            jElm.change();
        }, 'json');
    }
}

function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            var jElm = jQuery('#frmsearch').find('select[name="AMPHOE_CODE"]');
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
            });
            jElm.change();
        }, 'json');
    }
}


function gotoView1(t){
    var url='<?=Url::to(['rbnetworkyear/'])?>';
	url += '&consttask=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function gotoView2(t){
    var url='<?=Url::to(['rbnetworkyear/detail'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function getUrlView3(){
	var url='<?=Url::to(['rbnetworkyear/detail'])?>';
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	return url;
}

jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: function() {
    Common.jqgrid.onFilter();
  },
	height: 410,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
                'ปี',
                's',
                'ชื่อเครือข่าย',
                'ผู้ประสานงานหลัก',
                'หมายเลขโทรศัพท์',
                'ภาค',
                'จังหวัด',
                'อำเภอ',
                '',
                '',
                '',
                'Action'],
   	colModel:[
   		{name:'id',index:'RB_NETWORK_YEAR_ID', width:80, align:'center', sorttype:"int", editable: false},
   		{name:'year', index:'YEAR', width:100, sortable:true,editable: true, edittype:"select", editoptions:{width:"100", value:<?= Json::encode(Utils::getArrYears(false)); ?>}},
   		{name:'networkid',index:'RB_NETWORK_ID',hidden: true,editable: true,editoptions:{size:"27",maxlength:"255"}},
   		//{name:'networkname',index:'NETWORK_NAME',editable: true,edittype:"select", editoptions:{width:"100", value:{2556:'2556', 2557:'2557',2558:'2558'}}},  
   		{name:'networkname', index:'NETWORK_NAME', width:250, editable:true, edittype:'select', 
                          editoptions:{
                              dataUrl: "<?=Url::to(['rbnetworkyear/network-list'])?>", 
                                     buildSelect: function(jsonOrderArray) {
                                             var s = '<select>';
                                             var JSONObject = JSON.parse(jsonOrderArray);
                                             var coName = '';
                                             //console.log(JSONObject);  
                                             if (JSONObject && JSONObject.length) {
                                                for (var i = 0, l=JSONObject.length; i<l ; i++) {
                                                var ri = JSONObject[i];
                                                coName = JSONObject[i]['CO_FIRST_NAME']+' '+JSONObject[i]['CO_LAST_NAME'];
                                                s += '<option data-co-name="'+coName+'" data-co-tel="'+JSONObject[i]['CO_TEL']+'" value="'+JSONObject[i]['RB_NETWORK_ID']+'">'+JSONObject[i]['NETWORK_NAME']+'</option>';
                                                }
                                            }
                                            return s + "</select>";
                              },
                              dataEvents: [
                                { 
                                  type: 'change', 
                                  fn: function(e) {
                                    var $this = $(this);
                                    var $row = $this .closest('tr');
                                    var coName = $this.find('option:selected').attr('data-co-name');
                                    var coTel = $this.find('option:selected').attr('data-co-tel');

                                    $row.find('td[aria-describedby="rowed5_cofirstname"]').html(coName);
                                    $row.find('td[aria-describedby="rowed5_cotel"]').html(coTel);
                                  } 
                                },
                              ]
                          }
                      },   
   		{name:'cofirstname',index:'CO_FIRST_NAME',editable: false,editoptions:{size:"27",maxlength:"255"}},
   		{name:'cotel',index:'CO_TEL',editable: false,editoptions:{size:"27",maxlength:"255"}},
   		{name:'tmpregion',index:'REGION_CODE',editable: false,editoptions:{size:"27",maxlength:"255"}},   
      {name:'tmpprovince',index:'PROVINCE_CODE',editable: false,editoptions:{size:"27",maxlength:"255"}},     	
      {name:'tmpamphoe',index:'AMPHOE_CODE',editable: false,editoptions:{size:"27",maxlength:"255"}},                 	
      {name:'amphoecode', hidden: true, editable: false, editrules: { edithidden: false }, hidedlg: true},
      {name:'provincecode', hidden: true, editable: false, editrules: { edithidden: false }, hidedlg: true},                   
      {name:'regioncode', hidden: true, editable: false, editrules: { edithidden: false }, hidedlg: true},
      {name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},   
    ],
	onSelectRow: function(id){},
	onCellSelect: function(id, iCol, cellcontent){
	},
	multiselect: true,
	editurl: gridurl_1,
	caption: "RB006",
   	rowList:[10,20,30],
   	pager: '#pagered',
    viewrecords: true,
    scrollOffset: 3,
    gridComplete: function(){ 
        Common.jqgrid.onGridCompleted();
        var selector = '#'+jQuery(this).attr('id');
        Common.jqgrid.setColumn(selector, 'tmpprovince', jQuery('[name="PROVINCE_CODE"] option:selected').text());
        Common.jqgrid.setColumn(selector, 'tmpamphoe', jQuery('[name="AMPHOE_CODE"] option:selected').text());
        Common.jqgrid.setColumn(selector, 'tmpregion', jQuery('[name="REGION_CODE"] option:selected').text());
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