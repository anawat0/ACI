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
<?= Html::beginForm('#', 'GET', ['class' => 'form-horizontal']); ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			เลือกโครงการที่ต้องการบันทึกประจำเดือน
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label">ประจำปี</label>
				<div class="col-md-4">
					<?php 
						echo Html::dropDownList('budget_year', 
												$currentYear, 
												Utils::getArrYears(), 
												['class' => 'form-control list-budget-year']);
					?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4 list-im-proj-laun">
					<?= Html::ol($imProjLauns, 
						['item' => function($item, $index) {
							$url = Url::to(['improjlaunres/list', 
											'im_proj_laun_id' => $item['IM_PROJ_LAUN_ID'],
											'budget_year' => $item['BUDGET_YEAR']]);
							$html = "<a href=\"{$url}\">{$item->imProj['PROJ_NAME_TH']}</a>";

						    return Html::tag(
						        'li',
						        $html,
						        ['class' => '']
						    );
						},
						'class' => '']) ?>
				</div>
			</div>
		</div>
	</div>
<?= Html::endForm(); ?>
<!-- #################################################################################### -->
<script type="text/javascript">
	$(function() {

		$('.list-budget-year').change(function(e) {
			var url = '<?= Url::to(["improjlaunres/ajax-get-im-proj-laun"]) ?>';
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
				var url = '<?= Url::to(["improjlaunres/list"]) ?>';
				var html = '<ul>';
				
				$.each(data, function(i, e){
					html += '<li><a href="'+url+'&im_proj_laun_id='+i+'&budget_year='+budgetYear+'">'+e+'</a></li>';
				});
				html += '</ul>';

				$('.list-im-proj-laun').html(html);
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
