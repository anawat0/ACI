<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use app\models\WA_PROVINCE;
use app\models\KPI_DIMENTION;
use miloschuman\highcharts\Highcharts;

AppAsset::register($this);

// if (\Yii::$app->user->isGuest) {
// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// }

?>

<?php 
// 	JqGridWidget::widget();
	Highcharts::widget([]);
	
	$year = intval(date('Y'))+543;
?>

<style type="text/css">
.form-filtering .form-group {
	padding: 0 0 10px 0;
}
</style>

<form id="frmsearch" class="form-horizontal form-filtering">

	<div class="panel panel-primary">
	      <div class="panel-heading">
            <h3 class="panel-title"><a>สรุปผล ตัวชี้วัดผลการปฏิบัติราชการ</a></h3>
          </div>
  
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-1 right">
					<label>ประจำปีงบ</label>
				</div>
				<div class="col-md-2">
					<select class="form-control" onchange="onChangeBudgetYear(this, 'DIMENTION')"
						name="YEAR">
                	<?php Utils::getOptionsYears(); ?>
           		 	</select>
				</div>
				<div class="col-md-1 right">
					<label>ไตรมาส</label>
				</div>
				<div class="col-md-2">
					<select class="form-control" onchange="" name="MONTH">
					<option value="" selected="selected">แสดงทั้งหมด</option>
                	<?php Utils::getOptionsQuaters(); ?>
            		</select>
				</div>
				<div class="col-md-3 right">
					<label>มิติตัวชี้วัดผลการปฎิบัติราชการ</label>
				</div>
				<div class="col-md-3"><?=Html::dropDownList('DIMENTION', null, ArrayHelper::map(KPI_DIMENTION::find()->where(['BUDGET_YEAR'=>$year])->all(), 'KPI_DIMENTION_ID', 'DIMENTION_NAME_TH'),['class'=>'form-control', 'onchange'=>''])?></div>
			</div>
		</div>
		
		<div id="showchart">
		</div>
        <div id="chart"></div>
        
	</div>
</form>

<script type="text/javascript">

$(document).ready(function() {

	$('select[name="MONTH"], select[name="DIMENTION"]').change(function() {
		showHighChart();
    });

	showHighChart();

});

function onChangeBudgetYear(t, elm_relate_name){
	var budget_year=t.value;
    var data = {budget_year : budget_year};
    var jElm = jQuery('select[name="'+elm_relate_name+'"]');
    if(budget_year!=''){
        jQuery.post('<?=Url::to(['kpigraph/getddldimention']);?>', data, function(data){
            //on ajax success.
            jElm.find('option').remove();
            jQuery.each(data, function(i, row){
                jElm.append('<option value="'+row.KPI_DIMENTION_ID+'">'+row.DIMENTION_NAME_TH+'</option>');
            });
            jElm.change();
        }, 'json');
    }else{
    	jElm.find('option').remove();
    	jElm.change();
    }
}

function showHighChart(){
	 var year =  $('select[name=YEAR').val();
	 var month = $('select[name=MONTH').val();
	 var dimention = $('select[name=DIMENTION').val();
	
	 var url = "<?=Url::to(['kpigraph/show-graph']);?>";

	 if(!Common.utils.isNullOrBlank(dimention)){
     $.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: { year: year, month: month, dimention:dimention }
		})
		.done(function(data) {
			$( "#showchart" ).html( "" );
			$.each( data, function( i, l ){
				//console.log(data)
//  					alert(data[i]['FST_NAME_TH']);
					$( "#showchart" ).append( "<div id=\'chart"+data[i]['KPI_FST_ID']+"\'></div>" );
					InitHighChart(data[i]['KPI_FST_ID'],data[i]['FST_NAME_TH']);
		    });
	}).error(function(){
		$( "#showchart" ).html( "" );
	});
	 }else{
		 $( "#showchart" ).html( "" );
	 }
	
}

function InitHighChart(fstId,name)
{
	$('.loading').show();
	var year =  $('select[name=YEAR').val();
	var month = $('select[name=MONTH').val();
	var dimention = $('select[name=DIMENTION').val();
	
	var options = {
		chart: {
			renderTo: 'chart'+fstId	,
		},
		credits: {
			enabled: false
		},
		title: {
			text:  name,
			x: -20
		},
		subtitle: {
            text: 'ผลการดำเนินการ'
        },
		xAxis: {
			categories: [{}]
		},
		tooltip: {
            formatter: function() {
                var s = '<b>'+ this.x +'</b>';
                
                $.each(this.points, function(i, point) {
                    s += '<br/>'+point.series.name+': '+point.y;
                });
                
                return s;
            },
            shared: true
        },
		series: [{}]
	};

    var url = "<?=Url::to(['kpigraph/data-graph']);?>";
            $.ajax({
				type: "POST",
				url: url,
				dataType: "json",
				data: { year: year, month: month,dimention:dimention,fstId:fstId }
			})
			.done(function(data) {
				options.chart.type = 'column';
				options.xAxis.categories = data.X;
				options.series[0].name = 'ผลการดำเนินการ';
				options.series[0].data = data.Y;
				var chart = new Highcharts.Chart(options);	
		});
}

</script>