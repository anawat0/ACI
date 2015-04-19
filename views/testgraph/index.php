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

use miloschuman\highcharts\Highcharts;

AppAsset::register($this);

// if (\Yii::$app->user->isGuest) {
// 	\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// }

?>

<div id="chart"></div>
<input type="button" value="Show HighChart" onclick="InitHighChart();" />

<script>

function InitHighChart()
{
	$('.loading').show();
	
	var options = {
		chart: {
			renderTo: 'chart',
		},
		credits: {
			enabled: false
		},
		title: {
			text: 'เปรียบเทียบ',
			x: -20
		},
		subtitle: {
            text: 'คะแนนถ่วงน้ำหนัก'
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
		series: [{},{}]
	};

    var url = "<?=Url::to(['testgraph/data-graph']);?>";
            $.ajax({
				type: "POST",
				url: url,
				dataType: "json",
				data: {}
			})
			.done(function(data) {
				options.chart.type = 'column';
				options.xAxis.categories = data.X;
				options.series[0].name = 'รอบที่';
				options.series[0].data = data.Y;
				var chart = new Highcharts.Chart(options);	
		});
	
}

</script>
<?php echo Highcharts::widget([]);?>
