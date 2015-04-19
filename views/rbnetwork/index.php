<?php
    use app\assets\AppAsset;
    use himiklab\jqgrid\JqGridWidget;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\WA_REGION;
    use app\models\WA_PROVINCE;
    use app\models\WA_AMPHOE;
    use app\models\RB_NETWORK;

    AppAsset::register($this);

    // if (\Yii::$app->user->isGuest) {
    // 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
    // }

	JqGridWidget::widget();

	$region_code = Yii::$app->getRequest()->getQueryParam('region_code');
	$province_code = Yii::$app->getRequest()->getQueryParam('province_code');
	$amphoe_code = Yii::$app->getRequest()->getQueryParam('amphoe_code');
	
	if(empty($region_code)) $region_code = '1';
	if(empty($province_code)) $province_code = '10';
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
            <div class="col-md-1 right" >  <label>ภาค</label> </div>
            <div class="col-md-2" ><?=Html::dropDownList('REGION_CODE', $region_code, ArrayHelper::map(WA_REGION::find()->all(), 'REGION_CODE', 'REGION_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeRegion(this);'])?></div>
            <div class="col-md-1 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('PROVINCE_CODE', $province_code, ArrayHelper::map(WA_PROVINCE::find()->where(['REGION_CODE'=>$region_code])->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);'])?></div>
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" ><?=Html::dropDownList('AMPHOE_CODE', $amphoe_code, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>$province_code])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])?></div>
            
        </div>
    </div> 
</div>
</form>

<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['rbnetwork/gridview']);?>";
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

function getFilterParam(){

	var frmsearch = jQuery('#frmsearch');
	
	var url = '&region_code='+frmsearch.find('[name="REGION_CODE"]').val();
	url += '&province_code='+frmsearch.find('[name="PROVINCE_CODE"]').val();
	url += '&amphoe_code='+frmsearch.find('[name="AMPHOE_CODE"]').val();
	return url;
}

function gotoView1(t){
    var url='<?=Url::to(['rbnetwork/'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function gotoView2(t){
    var url='<?=Url::to(['rbnetwork/detail'])?>';
	url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
	url += getFilterParam();
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}

function getUrlView3(){
	var url='<?=Url::to(['rbnetwork/detail'])?>';
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
   	colNames:['ลำดับที่','ชื่อเครือข่าย','ผู้ประสานงานหลัก','หมายเลขโทรศัพท์','ภาค','จังหวัด','อำเภอ','แก้ไข','','',''],
   	colModel:[
   		{name:'id',index:'SSB_CLUB_ID', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'networkname',index:'NETWORK_NAME',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'cofirstname',index:'CO_FIRST_NAME',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'cotel',index:'CO_TEL',editable: true,editoptions:{size:"27",maxlength:"255"}},
   		{name:'tmpregion',index:'REGION_CODE',editable: true,editoptions:{size:"27",maxlength:"255"}},   
                {name:'tmpprovince',index:'PROVINCE_CODE',editable: true,editoptions:{size:"27",maxlength:"255"}},     	
                {name:'tmpamphoe',index:'AMPHOE_CODE',editable: true,editoptions:{size:"27",maxlength:"255"}},                 	
                {name:'edit', index:'edit', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView2(this);" class="glyphicon glyphicon-pencil"></span>'; }},
                    {name:'amphoecode', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
                    {name:'provincecode', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},                   
                    {name:'regioncode', hidden: true, editable: true, editrules: { edithidden: false }, hidedlg: true},
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
    }).navButtonAdd('#pagered',Common.jqgrid.getAddButton(getUrlView3));
</script>