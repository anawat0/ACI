<?php
    use app\assets\AppAsset;
    use himiklab\jqgrid\JqGridWidget;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\BaseArrayHelper;
    use yii\bootstrap\ActiveForm;
    use app\models\WA_PROVINCE;
    use app\models\WA_AMPHOE;
    use yii\web\Utils;

    AppAsset::register($this);

//     if (\Yii::$app->user->isGuest) {
//         \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
//     }
    JqGridWidget::widget();
?>
<style>
    .form-group {
        margin-bottom: 15px;
    }
</style>
<!-- ################################################################################### -->
<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
    <div class="panel panel-primary">
      <div class="panel-body">
            <div class="form-group">
                <div class="col-md-2 control-label">
                    <label>ประจำปี</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" onchange="Common.jqgrid.onFilter()"
                        name="BUDGET_YEAR">
                        <?php Utils::getOptionsyears(); ?>
                     </select>
                </div>
                <div class="col-md-2 control-label">
                    <label>เดือน</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" onchange="Common.jqgrid.onFilter()"
                        name="MONTH">
                        <?php Utils::getOptionsMonth(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <?= Html::label('จังหวัด', 'RB_PROJ_LAUN_RES.PROVINCE_CODE', ['class' => 'col-md-2 control-label']); ?>
                <div class="col-md-3">
                    <?php  
                        echo Html::dropDownList('RB_PROJ_LAUN_RES.PROVINCE_CODE',
                                                    null,
                                                    BaseArrayHelper::map(WA_PROVINCE::find()->all(), 
                                                                                    'PROVINCE_CODE', 
                                                                                    'PROVINCE_NAME_TH'),
                                                    [
                                                        'id' => 'provineList',
                                                        'class' => 'form-control',
                                                        'prompt' => 'กรุณาเลือก',
                                                        'onchange' => 'Common.jqgrid.onFilter();onChangeProvince(this);'
                                                    ]); 
                    ?>
                </div>
                <?= Html::label('อำเภอ', 'RB_PROJ_LAUN_RES.AMPHOE_CODE', ['class' => 'col-md-2 control-label']); ?>
                <div class="col-md-3">
                    <?php
                        echo Html::dropDownList('RB_PROJ_LAUN_RES.AMPHOE_CODE',
                                                    null,
                                                    $firstOptionDDL,
                                                    [
                                                        'id' => 'amphoeList',
                                                        'class' => 'form-control',
                                                        'onchange' => 'Common.jqgrid.onFilter();'
                                                    ]); 
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="centered">
                    <?= Html::button('<span class="glyphicon glyphicon-search"></span> พิมพ์รายงานสรุป',
                                    [
                                        'class' => 'btn btn-success btn-md open-report',
                                        'data-report-name' => 'RB_SUM.pdf'
                                    ]); ?>
                </div>
            </div>
        </div>
     </div>
<?= Html::endForm(); ?>
<?php
    echo Html::beginForm('http://report.dpe.go.th:9000', 
                        'POST', 
                        [
                            'id' => 'reportForm',
                            'class' => '',
                            'target' => '_blank'
                        ]);
    echo Html::hiddenInput('report');
    echo Html::hiddenInput('promptI_BUDGET_YEAR');
    echo Html::hiddenInput('promptI_MONTH'); 
    echo Html::endForm(); 
?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
    var gridurl_1 = "<?=Url::to(['rbprojlaunres/grid-view-for-summary']);?>";
    var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'RB_PROJ_LAUN_RES_ID'});

    function updateStatus(rb_proj_laun_res_id, status) {
        var url = "<?=Url::to(['rbprojlaunres/ajax-update-status']);?>";
        // var rb_proj_laun_res_id = $(obj).closest('tr[role="row"]').attr('id');
        // var status = $(obj).attr('data-status');

        $('.loading').show();
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data:{rb_proj_laun_res_id: rb_proj_laun_res_id,
                 status: status}
        })
        .done(function(data, textStatus, jqXHR) {
            BootstrapDialog.alert(data.msg);
            if (data.status_update) {
                $("#rowed5").trigger("reloadGrid");
            } else {
                $('.loading').hide();
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            BootstrapDialog.alert('เกิดข้อผิดพลาด');
            $('.loading').hide();
        });
    }

    function onChangeProvince(t){
        var province_id=t.value;
        var data = {province : province_id};
        if(province_id!=''){
            jQuery.post('<?=Url::to(["common/getddlampore"]);?>', data, function(data){
                var jElm = jQuery('#frmsearch').find('select[name="RB_PROJ_LAUN_RES.AMPHOE_CODE"]');
                jElm.find('option').remove();
                jElm.append('<option value="">กรุณาเลือก</option>');
                jQuery.each(data, function(i, row){
                    jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
                });
                jElm.change();
            }, 'json');
        }
    }

    // function getEditLink(data) {
    //     var className = '';

    //     if (data == 'A') {
    //         className += 'glyphicon glyphicon-pencil';
    //     } else {
    //         className += 'glyphicon glyphicon-ban-circle';
    //     }

    //     return '<span style="margin:auto;cursor:pointer;" onclick="updateStatus(this);" class="'+className+'" data-status="'+data+'"></span>';
    // }

    jQuery("#rowed5").jqGrid({
        url: gridurl_1+"&oper=request",
        datatype: function() {
            Common.jqgrid.onFilter();
        },
        height: 410,
        width: jQuery('div.content').width()-20,
        colNames:['ลำดับที่', 
                    'เดือน', 
                    'จังหวัด', 
                    'อำเภอ', 
                    'โครงการ', 
                    'กิจกรรม', 
                    'สถานะ',
                    'ปีงบประมาณ',
                    'เดือน',
                    'การแก้ไข'],
        colModel:[
                {name:'seq',index:'seq', width:60, sortable:false, editable: false, align:'center'},
                {name:'month',index:'MONTH' ,width:80, sortable:true, editable: false},
                {name:'province_code', index:'PROVINCE_CODE', width:100, sortable:true, editable:false},
                {name:'amphoe_code',index:'AMPHOE_CODE', width:100, sortable:true, editable:false},
                {name:'proj_name_th', index:'PROJ_NAME_TH', width:200, sortable:true, editable: false},
                {name:'sub_proj_name_th', index:'SUB_PROJ_NAME_TH', width:200, sortable:true, editable: false},
                {name:'status', index:'STATUS', width:60, sortable:true, editable: false, align:'center'},
                {name:'budget_year', index:'BUDGET_YEAR', hidden:true},
                {name:'month', index:'MONTH', hidden:true},
                {name:'edit', index:'edit', width:60, editable:false, sortable:false, align:'center', formatter: function (cellvalue, options, rowObject) {
                                                                                                                    return Common.jqgrid.getEditLink({
                                                                                                                                                        year: rowObject[7],
                                                                                                                                                        month: rowObject[8],
                                                                                                                                                        status: rowObject[6],
                                                                                                                                                        functionName: "updateStatus(\'"+options.rowId+"\', \'"+rowObject[6]+"\');"
                                                                                                                                                        });
                                                                                                                }},
        ],
        onSelectRow: function(id){},
        onCellSelect: function(id, iCol, cellcontent){
            
        },
        multiselect: false,
        editurl: gridurl_1,
        caption: "RD004 สรุปรายงานผลการดาเนินการ สนก.",
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
        del: false,
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

    $(function() {
        $('.open-report').click(function(event) {
            var $frmsearch = $('#frmsearch');
            var $reportForm = $('#reportForm');
            var $this = $(this);
            var dataReportName = $this.attr('data-report-name');
            var budgetYear = $frmsearch.find('select[name="BUDGET_YEAR"]').val();
            var month = $frmsearch.find('select[name="MONTH"]').val();

            $reportForm.find('input[name="report"]').val('file:/C:/Program Files/i-net Clear Reports/startpage/'+dataReportName);
            $reportForm.find('input[name="promptI_BUDGET_YEAR"]').val(budgetYear);
            $reportForm.find('input[name="promptI_MONTH"]').val(month);

            $reportForm.submit();
        });
    });
</script>