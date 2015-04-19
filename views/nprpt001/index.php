<style type="text/css">
	.form-horizontal .form-group{
	    padding: 0 0 10px 0;
	}
</style>
<!-- ################################################################################# -->
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

	JqGridWidget::widget();
	
	$year = Yii::$app->getRequest()->getQueryParam('year');
	$month = Yii::$app->getRequest()->getQueryParam('month');
	$province = Yii::$app->getRequest()->getQueryParam('province');
	$amphoe = Yii::$app->getRequest()->getQueryParam('amphoe');
?>
<form id="frmsearch" class="form-horizontal">
<div class="panel panel-primary">
  	<div class="panel-body">        
        <div class="form-group">
           	<div class="col-md-2 right" >  <label>ประจำปี</label> </div>
           	<div class="col-md-2" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="YEAR"><?php Utils::getOptionsYears(); ?></select></div>

           	<div class="col-md-2 right" >  <label>เดือน</label> </div>
           	<div class="col-md-2" ><select class="form-control" onchange="Common.jqgrid.onFilter()" name="MONTH"><?php Utils::getOptionsMonth(); ?></select></div>
        </div>
        <div class="form-group">
            <div class="col-md-2 right" >  <label>จังหวัด</label> </div>
            <div class="col-md-3" ><?php 
            		//echo Html::dropDownList('PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control', 'onchange'=>'onChangeProvince(this);'])
		            echo Utils::getDDLProvince_AutoComplete(null, null, 'PROVINCE_CODE',
		            		'PROVINCE_CODE',
		            		'PROVINCE_NAME_TH',
		            		$province,
		            		$user_info,
		            		['onchange' => 'Common.ajax.onChangeProvince(this, \'AMPHOE_CODE\', \''.$amphoe.'\')']
		            );
            	?></div>
            <div class="col-md-1 right" >  <label>อำเภอ</label> </div>
            <div class="col-md-3" ><?php 
            		//echo Html::dropDownList('AMPHOE_CODE', null, ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH'),['class'=>'form-control', 'onchange'=>'Common.jqgrid.onFilter();'])
		            echo Utils::getDDLAmphoe_AutoComplete(null, null, 'AMPHOE_CODE',
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
            <a onclick="getReport(this);" class="btn btn-success btn-md" data-report-name="NP_AMPHOE.pdf"><span class="glyphicon glyphicon-search"></span> รายงานการปฏิบัติงาน</a> 
            <a onclick="getReport(this);" class="btn btn-success btn-md" data-report-name="NP_SAT_AMP.pdf"><span class="glyphicon glyphicon-search"></span> รายงานสำรวจความพึงพอใจ</a>
        </div>  
  	</div> 
</div>
</form>

<form name="FormClearReports" action="http://report.dpe.go.th:9000" method="POST" target="_blank" >
        <input type="hidden" name="report" >
	    <input type="hidden" name="promptI_YEAR" >
        <input type="hidden" name="prompt_MONTH" >
        <input type="hidden" name="promptI_AMPHOE_CODE" >
        <input type="hidden" name="promptI_PROVINCE_CODE" >
        
        <script type="text/javascript">
            function getReport(e){
            	var reportName = $(e).attr('data-report-name');
                var year = $('select[name=YEAR').val();
                var month = $('select[name=MONTH').val();
                var amphoeCode = $('select[name=AMPHOE_CODE').val();
                var provinceCode = $('select[name=PROVINCE_CODE').val();
                var $form = jQuery('form[name="FormClearReports"]');

                $form.find('[name="report"]').val("file:/C:/Program Files/i-net Clear Reports/startpage/"+reportName);
                $form.find('[name="promptI_YEAR"]').val(year);
                $form.find('[name="promptI_MONTH"]').val(month);
                $form.find('[name="promptI_AMPHOE_CODE"]').val(amphoeCode);
                $form.find('[name="promptI_PROVINCE_CODE"]').val(provinceCode);
                
                $form.submit() ;
          }
        </script>
</form>
<!-- ############################################################################### -->
<script type="text/javascript">
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
</script>