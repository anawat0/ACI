<?php
    use app\assets\AppAsset;
    use himiklab\jqgrid\JqGridWidget;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\web\Utils;
    use app\models\WA_PROVINCE;
    use app\models\KPI_FST;
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

<form class="form-horizontal form-filtering">  
    <div class="panel panel-primary">
      <div class="panel-body">
            <div class="form-group">
                <div class="col-md-2 right" >  <label>ประจำปี</label> </div>
                <div class="col-md-2" > <?=Html::textInput('BUDGET_YEAR', $budgetyear , ['class'=>'form-control', 'disabled'=>'disabled'])?>   </div>
                <div class="col-md-3 right" >  <label>มิติตัวชี้วัดผลการปฏิบัติราชการ</label> </div>
                <div class="col-md-4" > <?=Html::textInput('DIMENTEON_NAME_TH', $dimentionnameth , ['class'=>'form-control', 'disabled'=>'disabled'])?> </div>
            </div> 
        </div>
    </div>    
</form>

<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['kpifst/gridview', 'kpidimention'=>$kpidimention]);?>";

var rtparams = Common.jqgrid.getDefaultPostData('', {sidx: 'FST_NAME_TH'});

function gotoView1(t){
    var url='<?=Url::to(['kpisecd/'])?>';
	url += '&kpifst=' + jQuery(t).closest('tr[role="row"]').attr('id');
        url += '&kpidimention=<?=$kpidimention?>'  ;
        url += '&budgetyear=<?=$budgetyear?>'  ;
        url += '&dimentionnameth=<?=$dimentionnameth?>'  ;
        url += '&fstnameth=' + jQuery(t).closest('tr[role="row"]').find('td')[2].innerHTML;
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}


jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: "json",
	height: 420,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
                'ตัวชี้วัดผลการปฏิบัติราชการ',
                'น้ำหนัก (ร้อยละ)',
                'สถานะ',
                'ตัวชี้วัดรอง',
                'Actions'],
   	colModel:[
   		{name:'seq',index:'seq', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'fstnameth',index:'FST_NAME_TH',editable: true,editoptions:{maxlength:"255"}},   		
        {name:'targetscore', index:'TARGET_SCORE', width:100, sortable:true,editable: true,editoptions:{maxlength:"255"}},   	                
        {name:'status',index:'STATUS', width:60, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
        {name:'sub', index:'sub', width:50, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
        {name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
   	],
	onSelectRow: function(id){},
    onCellSelect: function(id, iCol, cellcontent){
        // var noActioniCol = [5];
        // Common.jqgrid.onCellSelect(this, id, iCol, cellcontent, noActioniCol);
    },
	multiselect: true,
	editurl: gridurl_1,
	caption: "KPI001",
   	rowList:[10,20,30],
   	pager: '#pagered',
    viewrecords: true,
    scrollOffset: 3,
    gridComplete: Common.jqgrid.onGridCompleted,
   	rowNum:rtparams.rows,
   	sortname: rtparams.sidx,
    sortorder: rtparams.sord,
    page: rtparams.page,
});

jQuery("#rowed5").jqGrid('navGrid', '#pagered', 
                                        {   edit: false , add: false , del: true,
			refresh: false} , 
                                        {}, 
                                        {   height: 280 , reloadAfterSubmit: true   }, 
                                        Common.jqgridOptions.navGridDelete,
                                        {   multipleSearch: true , multipleGroup: true} 
                        );

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