<?php
  use app\assets\AppAsset;
  use himiklab\jqgrid\JqGridWidget;
  use yii\helpers\Url;
  use yii\helpers\Html;
  use yii\helpers\ArrayHelper;
  use app\models\WA_PROVINCE;
  use app\models\SSB_CLUB;

  AppAsset::register($this);

  // if (\Yii::$app->user->isGuest) {
  // 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
  // }

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
            <div class="col-md-2 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
            <div class="col-md-1 right" >  <label>ชมรม</label> </div>
            <div class="col-md-3" ><?=Html::textInput('CLUB_NAME_TH', null , ['class'=>'form-control'])?></div>
            <div class="col-md-1"><a onclick="Common.jqgrid.onFilter();" class="btn btn-success btn-md"><span class="glyphicon glyphicon-search"></span> ค้นหา</a> </div>
            
        </div>
    </div> 
</div>
</form>

<!-- <div>Filter</div>
<div><a href="#" onclick="window.location='<?=Url::to(['ssbclub/detail']);?>'">เพิ่ม Record</a></div>  -->
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['ssbclub/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'CLUB_NAME_TH'});

function onChangeProvince(t){
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
}

function gotoView1(t){
    var url='<?=Url::to(['ssbclub/'])?>';
	url += '&consttask=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function gotoView2(t){
    var url='<?=Url::to(['ssbclub/detail'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function getUrlView3(){
	var url='<?=Url::to(['ssbclub/detail'])?>';
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	return url;
}


  jQuery("#rowed5").jqGrid({
  	url: gridurl_1+"&oper=request",
  	datatype: function(postdata) {
      Common.jqgrid.onFilter();
    },
  	height: 410,
  	width: jQuery('div.content').width()-20,
     	colNames:['ลำดับที่','ชื่อชมรม','ประธานชมรม','หมายเลขโทรศัพท์','จำนวนสมาชิก','สถานะ','แก้ไข'],
     	colModel:[
     		{name:'id',index:'SSB_CLUB_ID', width:60, align:'center', sorttype:"int", editable: false},
     		{name:'clubnameth',index:'CLUB_NAME_TH',editable: true,editoptions:{size:"27",maxlength:"255"}},
     		{name:'leader',index:'LEADER',editable: true,editoptions:{size:"27",maxlength:"255"}},
     		{name:'tel',index:'TEL',editable: true,editoptions:{size:"27",maxlength:"255"}},
     		{name:'amount',index:'AMOUNT',editable: true,editoptions:{size:"27",maxlength:"255"}},   
     		{name:'status',index:'STATUS', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
        {name:'edit', index:'edit', width:60, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView2(this);" class="glyphicon glyphicon-pencil"></span>'; }},
     	],
  	onSelectRow: function(id){},
  	onCellSelect: function(id, iCol, cellcontent){
  		
  	},
  	multiselect: true,
  	editurl: gridurl_1,
  	caption: "SSB003",
     	rowList:[10,20,30],
     	pager: '#pagered',
      viewrecords: true,
      scrollOffset: 3,
      gridComplete: function(){
      	var pager = jQuery(this).getGridParam("pager");
        jQuery(pager).find('.ui-icon-disk, .ui-icon-cancel').closest('td').removeClass('ui-state-disabled').addClass('ui-state-disabled');
  	    Common.jqgrid.setEditMode(pager, false);
  	    jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
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
  }).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlView3));
</script>