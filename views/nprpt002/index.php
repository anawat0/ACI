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
	use yii\web\Utils;
	use yii\bootstrap\ActiveForm;
	use yii\web\Authentication;

	AppAsset::register($this);

	$user_info = Authentication::getAuthenInfo();

	JqGridWidget::widget();
	
    $year = Yii::$app->getRequest()->getQueryParam('year');
    $month = Yii::$app->getRequest()->getQueryParam('month');
    $province = Yii::$app->getRequest()->getQueryParam('province');
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
            	//echo Html::dropDownList('PROVINCE_CODE', null, ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['class'=>'form-control'])
	            echo Utils::getDDLProvince_AutoComplete(null, null, 'PROVINCE_CODE',
	            		'PROVINCE_CODE',
	            		'PROVINCE_NAME_TH',
	            		$province,
	            		$user_info,
	            		['onchange' => 'Common.jqgrid.onFilter()']
	            );
            	?></div>
        </div>
        <div class="footcontentbutton">
            <a onclick="getReport(this);" class="btn btn-success btn-md" data-report-name="NP_PROVINCE.pdf"><span class="glyphicon glyphicon-search"></span> รายงานการปฏิบัติงาน</a> 
            <a onclick="getReport(this);" class="btn btn-success btn-md" data-report-name="NP_SAT_PROV.pdf"><span class="glyphicon glyphicon-search"></span> รายงานสำรวจความพึงพอใจ</a>
        </div>  
  	</div> 
</div>
</form>

<form name="FormClearReports" action="http://report.dpe.go.th:9000" method="POST" target="_blank" >
    <input type="hidden" name="report" >
    <input type="hidden" name="promptI_YEAR" >
    <input type="hidden" name="promptI_MONTH" >
    <input type="hidden" name="promptI_PROVINCE_CODE" >
    
    <script type="text/javascript">
        function getReport(e){
        	var reportName = $(e).attr('data-report-name');
            var year = $('select[name=YEAR').val();
            var month = $('select[name=MONTH').val();
            var provinceCode = $('select[name=PROVINCE_CODE').val();
            var $form = jQuery('form[name="FormClearReports"]');

            $form.find('[name="report"]').val("file:/C:/Program Files/i-net Clear Reports/startpage/"+reportName);
            $form.find('[name="promptI_YEAR"]').val(year);
            $form.find('[name="promptI_MONTH"]').val(month);
            $form.find('[name="promptI_PROVINCE_CODE"]').val(provinceCode);
            
            $form.submit() ;
      }
    </script>
</form>