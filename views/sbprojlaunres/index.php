<style>
    .form-group {
        margin-bottom: 15px;
    }
</style>
<?php
	use app\assets\AppAsset;
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use yii\web\Utils;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) 
// 	{
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}
?>

<?php $form = ActiveForm::begin([
    'id' => 'submitForm',
    'options' => ['class' => 'form-horizontal'],
    'action' => Url::to(['sbprojlaunres/show-list'])
]); ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			เลือกโครงการที่ต้องการบันทึกประจำเดือน
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label">ประจำปี</label>
				<div class="col-md-8">
					<?php 
						echo Html::dropDownList('BUDGET_YEAR', 
												$budgetYear, 
												Utils::getArrYears(false), 
												['class' => 'form-control list-budget-year']);
					?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4 list-sb-proj-laun">
					<?php
						echo Html::ol($sbProjLauns, 
									[
										'item' => function($item, $index) {
													$url = Url::to(['sbprojlaunres/list', 
																	'sb_proj_laun_id' => $item['SB_PROJ_LAUN_ID'],
																	'budget_year' => $item['BUDGET_YEAR']]);
													$html = "<a href=\"{$url}\">{$item->sbProj['PROJ_NAME_TH']}</a>";

												    return Html::tag('li', $html, ['class' => '']);
												},
										'class' => ''
									]); 
					?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>
<!-- #################################################################################### -->
<script type="text/javascript">
	$(function() {
		$('.list-budget-year').change(function(e) {
			var url = '<?= Url::to(["sbprojlaunres/ajax-get-sb-proj-laun"]) ?>';
			var budgetYear = $(this).val();

			bDialog = new BootstrapDialog.show({
	            title: 'กำลังโหลดข้อมูล',
	            message: 'กรุณารอสักครู่'
	        });
			$.ajax({
				type: 'GET',
				url: url,
				dataType: 'json',
				data: {budget_year:budgetYear}
			})
			.done(function(data, textStatus, jqXHR) {
				var url = '<?= Url::to(["sbprojlaunres/list"]) ?>';
				var html = '<ul>';
				
				$.each(data, function(i, e){
					html += '<li><a href="'+url+'&sb_proj_laun_id='+i+'&budget_year='+budgetYear+'">'+e+'</a></li>';
				});
				html += '</ul>';

				$('.list-sb-proj-laun').html(html);
			})
			.fail(function(data, textStatus, errorThrow) {
				bDialog.close();
				BootstrapDialog.alert('เกิดข้อผิดพลาด');
			})
			.always(function(data, textStatus, error_jqxhr) {
				bDialog.close();
			});
		});
	});
</script>