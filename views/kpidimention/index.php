<?php
    use app\assets\AppAsset;
    use himiklab\jqgrid\JqGridWidget;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Json;
    use yii\web\Utils;
    use app\models\WA_PROVINCE;
    use app\models\KPI_DIMENTION;
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
            <div class="col-md-2 right" >  <label>ประจำปี</label> </div>
            <div class="col-md-3" > <select class="form-control" onchange="Common.jqgrid.onFilter()" name="BUDGET_YEAR">
                                        <?php Utils::getOptionsYears(); ?>
                                    </select>
            </div>
        </div> 
    </div>
</div>    
</form>

<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>

<script type="text/javascript">
var gridurl_1 = "<?=Url::to(['kpidimention/gridview']);?>";
var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>');

function gotoView1(t){
    var url='<?=Url::to(['kpifst/'])?>';
	url += '&kpidimention=' + jQuery(t).closest('tr[role="row"]').attr('id');
        url += '&budgetyear=' + jQuery(t).closest('tr[role="row"]').find('td')[3].innerHTML;
        url += '&dimentionnameth=' + jQuery(t).closest('tr[role="row"]').find('td')[2].innerHTML;
	url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
	
	window.location=url;
}


 
jQuery("#rowed5").jqGrid({
	url: gridurl_1+"&oper=request",
	datatype: function() {
        Common.jqgrid.onFilter();
    },
	height: 420,
	width: jQuery('div.content').width()-20,
   	colNames:['ลำดับที่',
                'ชื่อมิติตัวชี้วัดการปฏิบัติราชการ',
                'ประจำปี',
                'สถานะ',
                'ตัวชี้วัดผลการปฏิบัติราชการ',
                'Actions'],
   	colModel:[
   		{name:'id',index:'KPI_DIMENTION_ID', width:60, align:'center', sorttype:"int", editable: false},
   		{name:'dimentionnameth',index:'DIMENTION_NAME_TH', width:300,editable: true,editoptions:{maxlength:"255"}},   		
        {name:'budgetyear', index:'BUDGET_YEAR', width:80, sortable:true,editable: true, edittype:"select", editoptions:{width:"100", value:<?= Json::encode(Utils::getArrYears(false)); ?>}},                
        {name:'status',index:'STATUS', width:80, align:'center', editable: true,edittype:"checkbox",editoptions: {value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
        {name:'sub', index:'sub', width:80, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoView1(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
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
//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
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