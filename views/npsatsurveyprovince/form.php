<style>
	.control-label {
		padding-left: 5px;
		padding-right: 0;
	}
	.form-group.sum {
		padding-bottom: 10px;
	}
</style>
<!-- ################################################################################### -->
<?php
	use app\assets\AppAsset;
	use app\models\SB_PROJ_LAUN_TARGET;
	use app\models\SB_PROJ_LAUN_RES;
	use yii\base\Controller;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\BaseArrayHelper;
	use yii\helpers\BaseJson;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	use yii\jui\DatePicker;
	use himiklab\jqgrid\JqGridWidget;
	use yii\web\Utils;
	use yii\web\Authentication;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");
?>

<?php $form = ActiveForm::begin([
    'id' => 'npSatSurveyProvinceForm',
    'method' => 'POST',
    'action' => empty($model['NP_SAT_SURVEY_PROVINCE_ID'])? Url::to(['npsatsurveyprovince/create']): Url::to(['npsatsurveyprovince/update']),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => '',
            'offset' => '',
            'wrapper' => '',
            'error' => '',
            'hint' => '',
        ],
    ],
]);

$user_info = Authentication::getAuthenInfo();

$model->STATUS = empty($model->npSpOpenProvince)? $model->STATUS: $model->npSpOpenProvince->STATUS;
if(empty($model->YEAR)){
	$model->YEAR = $year;
}
if(empty($model->MONTH)){
	$model->MONTH = $month;
}

?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER_SECTION_1'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<?= $form->field($model, 'NP_SAT_SURVEY_PROVINCE_ID')->hiddenInput(); ?>
				<?= Html::activeLabel($model, 'YEAR', ['class'=>'col-sm-1 control-label']); ?>
				<div class="col-sm-3">
			        <?= $form->field($model, 'YEAR', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrYears(),
													['class' => 'form-control',
													'options' =>
							                            [
							                                $province => ['selected ' => true]
							                            ]
							                        ]); ?>
			    </div>
			    <?= Html::activeLabel($model, 'MONTH', ['class'=>'col-sm-1 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	echo $form->field($model, 'MONTH', ['options' => ['class' => '']])
			        				->dropDownList(Utils::getArrMonth($month),
													['class' => 'form-control']); ?>
			    </div>
			    <?= Html::activeLabel($model, 'PROVINCE_CODE', ['class'=>'col-sm-1 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	// $provinceList = BaseArrayHelper::merge($plaseSelect, 
			        	// 										BaseArrayHelper::map($waProvinces, 'PROVINCE_CODE', 'PROVINCE_NAME_TH'));
			        	
			        	// echo $form->field($model, 'PROVINCE_CODE', ['options' => ['class' => '']])
			        	// 			->dropDownList($provinceList,
												// 	['class' => 'form-control']); 
						echo Utils::getDDLProvince_AutoComplete($form, $model, 'PROVINCE_CODE',
			        		'PROVINCE_CODE',
			        		'PROVINCE_NAME_TH',
			        		$province,
			        		$user_info,
			        		['onchange' => '']
			        	);
					?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::label('<p class="text-left">1. เพศ</p>', null, ['class'=>'col-sm-1 control-label']); ?>

				<?= Html::activeLabel($model, 'NUM_MALE', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'NUM_MALE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control gender']); ?>
			    </div>

				<?= Html::activeLabel($model, 'NUM_FEMALE', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'NUM_FEMALE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control gender']); ?>
			    </div>

			    <?= Html::label('<u>รวม</u>', null, ['class'=>'col-sm-offset-1 col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= Html::textInput('SUM_GENDER', 
								        null, 
								        ['id' => 'SUM_GENDER', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::label('<p class="text-left">2. อายุ</p>', null, ['class'=>'col-sm-1 control-label']); ?>

				<?= Html::activeLabel($model, 'AGE_1', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'AGE_1', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'AGE_2', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'AGE_2', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'AGE_3', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'AGE_3', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'AGE_4', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'AGE_4', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'AGE_5', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'AGE_5', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>
			</div>
			<div class="form-group sum">
			    <?= Html::label('<u>รวม</u>', null, ['class'=>'col-sm-offset-9 col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= Html::textInput('SUM_AGE', 
									        null, 
									        ['id' => 'SUM_AGE', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::label('<p class="text-left">3. การศึกษา</p>', null, ['class'=>'col-sm-1 control-label']); ?>

				<?= Html::activeLabel($model, 'EDU_1', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_1', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

				<?= Html::activeLabel($model, 'EDU_2', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_2', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'EDU_3', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_3', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'EDU_4', ['class'=>'col-sm-offset-1 col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_4', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'EDU_5', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_5', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'EDU_6', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'EDU_6', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>
			</div>
			<div class="form-group sum">
			    <?= Html::label('<u>รวม</u>', null, ['class'=>'col-sm-offset-9 col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= Html::textInput('SUM_EDU', 
								        null, 
								        ['id' => 'SUM_EDU', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::label('<p class="text-left">4. สถานะภาพ</p>', null, ['class'=>'col-sm-1 control-label']); ?>

				<?= Html::activeLabel($model, 'SINGLE', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'SINGLE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control status']); ?>
			    </div>

				<?= Html::activeLabel($model, 'MARRIAGE', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'MARRIAGE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control status']); ?>
			    </div>

				<?= Html::activeLabel($model, 'WIDOWED', ['class'=>'col-sm-1 control-label']); ?>
                            <div class="col-sm-1">
			        <?= $form->field($model, 'WIDOWED', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control status']); ?>
			    </div>
			</div>
			<div class="form-group sum">
			    <?= Html::label('<u>รวม</u>', null, ['class'=>'col-sm-offset-9 col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= Html::textInput('SUM_STATUS', 
								        null, 
								        ['id' => 'SUM_STATUS', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::label('<p class="text-left">4. อาชีพ</p>', null, ['class'=>'col-sm-1 control-label']); ?>

				<?= Html::activeLabel($model, 'WORK_1', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_1', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

				<?= Html::activeLabel($model, 'WORK_2', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_2', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'WORK_3', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_3', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'WORK_4', ['class'=>'col-sm-offset-1 col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_4', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'WORK_5', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_5', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'WORK_6', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_6', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'WORK_7', ['class'=>'col-sm-offset-1 col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_7', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'WORK_8', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_8', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'WORK_9', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-1">
			        <?= $form->field($model, 'WORK_9', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control work']); ?>
			    </div>
			</div>                    
			<div class="form-group sum">
			    <?= Html::label('<u>รวม</u>', null, ['class'=>'col-sm-offset-9 col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= Html::textInput('SUM_WORK', 
								        null, 
								        ['id' => 'SUM_WORK', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
			    </div>
			</div>
			<!-- <div class="form-group sum">
				<?= Html::activeLabel($model, 'STATUS', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'STATUS', ['options' => ['class' => '']])
			        				->checkbox(['uncheck' => '0', 'class' => 'form-control'], false); ?>
			    </div>
			</div> -->
		</div>
	</div>

	<div class="panel panel-primary">        
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER_SECTION_2'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<div class="col-sm-12 table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<colgroup>
							<col style="width: 50%;">
							<col style="width: 10%;">
							<col style="width: 10%;">
							<col style="width: 10%;">
							<col style="width: 10%;">
							<col style="width: 10%;">
						</colgroup>
						<thead>
							<tr>
								<th rowspan="2" class="text-center">รายการ</th>
								<th colspan="5" class="text-center">คะแนนความพึงพอใจ (1 น้อย --> 5 มาก)</th>
							</tr>
							<tr>
								<th class="text-center">1</th>
								<th class="text-center">2</th>
								<th class="text-center">3</th>
								<th class="text-center">4</th>
								<th class="text-center">5</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>ด้านบุคลากร</td>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_1'); ?></td>
								<?php 
									$emptyArrKpi = array('', '', '', '', '');
									$kpi1s = $model->KPI_1? BaseJson::decode($model->KPI_1): $emptyArrKpi;
									$countKpi1s = count($kpi1s);
									for ($i = 0; $i < $countKpi1s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_1[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi1s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_2'); ?></td>
								<?php 
									$kpi2s = $model->KPI_2? BaseJson::decode($model->KPI_2): $emptyArrKpi;
									$countKpi2s = count($kpi2s);
									for ($i = 0; $i < $countKpi2s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_2[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi2s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_3'); ?></td>
								<?php 
									$kpi3s = $model->KPI_3? BaseJson::decode($model->KPI_3): $emptyArrKpi;
									$countKpi3s = count($kpi3s);
									for ($i = 0; $i < $countKpi3s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_3[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi3s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td>ด้านบริการ</td>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_4'); ?></td>
								<?php 
									$kpi4s = $model->KPI_4? BaseJson::decode($model->KPI_4): $emptyArrKpi;
									$countKpi4s = count($kpi4s);
									for ($i = 0; $i < $countKpi4s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_4[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi4s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_5'); ?></td>
								<?php 
									$kpi5s = $model->KPI_5? BaseJson::decode($model->KPI_5): $emptyArrKpi;
									$countKpi5s = count($kpi5s);
									for ($i = 0; $i < $countKpi5s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_5[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi5s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_6'); ?></td>
								<?php 
									$kpi6s = $model->KPI_6? BaseJson::decode($model->KPI_6): $emptyArrKpi;
									$countKpi6s = count($kpi6s);
									for ($i = 0; $i < $countKpi6s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_6[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi6s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_7'); ?></td>
								<?php 
									$kpi7s = $model->KPI_7? BaseJson::decode($model->KPI_7): $emptyArrKpi;
									$countKpi7s = count($kpi7s);
									for ($i = 0; $i < $countKpi7s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_7[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi7s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_8'); ?></td>
								<?php 
									$kpi8s = $model->KPI_8? BaseJson::decode($model->KPI_8): $emptyArrKpi;
									$countKpi8s = count($kpi8s);
									for ($i = 0; $i < $countKpi8s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_8[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi8s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td>ด้านสิ่งอำนวยความสะดวก</td>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_9'); ?></td>
								<?php 
									$kpi9s = $model->KPI_9? BaseJson::decode($model->KPI_9): $emptyArrKpi;
									$countKpi9s = count($kpi9s);
									for ($i = 0; $i < $countKpi9s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_9[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi9s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
							<tr>
								<td><?= $model->getAttributeLabel('KPI_10'); ?></td>
								<?php 
									$kpi10s = $model->KPI_10? BaseJson::decode($model->KPI_10): $emptyArrKpi;
									$countKpi10s = count($kpi10s);
									for ($i = 0; $i < $countKpi10s; $i++) {
										echo '<td>';
										echo $form->field($model, 'KPI_10[]', ['options' => ['class' => '']])
			        										->textInput(['value' => $kpi10s[$i], 'class' => 'form-control kpi']);
			        					echo '</td>';
									}
								?>
							</tr>
						</tbody>
						<tfoot class="hide">
							<tr>
								<td colspan="5" class="text-right">รวม</td>
								<td>
									<?= Html::textInput('SUM_KPI', 
													        null, 
													        ['id' => 'SUM_KPI', 'class' => 'form-control', 'readonly' => 'readonly']); ?>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-primary panel-button">
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-12 text-center">
					<?php 
				    	foreach($backAction as $key=>$value){
							echo '<input type="hidden" name="return['.$key.']" value="'.$value.'" />';
						}
				    ?>
					<?php
					 	echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Save',
					 							 ['class' => 'btn btn-success btn-lg', 
					 							 	'name' => 'submit-button']);
					?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>
<!-- ######################################################################################## -->
<script type="text/javascript">
	$(function() {
		jQuery('body').css('min-width', '1300px').css('overflow', 'auto');

		// TODO: disable field when data has not current month or status has equal 'C'
        if (('<?= $isCurrentMonth ?>' == 'true')
			&& ('<?= $model["NP_SAT_SURVEY_PROVINCE_ID"]; ?>' && '<?= $model->STATUS; ?>' == 'C')) {
            setToViewMode();
        } else if (('<?= $isCurrentMonth ?>' == 'false')
			&& ('<?= $model["NP_SAT_SURVEY_PROVINCE_ID"]; ?>' && '<?= $model->STATUS; ?>' != 'S')) {
            setToViewMode();
        } else {}

		// Summary
		genderSummary = new CalculateSummary();
		genderSummary.elementInput = 'input.gender';
		genderSummary.elementSummary = '#SUM_GENDER';
		genderSummary.setSummary();
		$(genderSummary.elementInput).on('keyup', function() {
			genderSummary.setSummary();
		});

		ageSummary = new CalculateSummary();
		ageSummary.elementInput = 'input.age';
		ageSummary.elementSummary = '#SUM_AGE';
		ageSummary.setSummary();
		$(ageSummary.elementInput).on('keyup', function() {
			ageSummary.setSummary();
		});

		eduSummary = new CalculateSummary();
		eduSummary.elementInput = 'input.edu';
		eduSummary.elementSummary = '#SUM_EDU';
		eduSummary.setSummary();
		$(eduSummary.elementInput).on('keyup', function() {
			eduSummary.setSummary();
		});

		statusSummary = new CalculateSummary();
		statusSummary.elementInput = 'input.status';
		statusSummary.elementSummary = '#SUM_STATUS';
		statusSummary.setSummary();
		$(statusSummary.elementInput).on('keyup', function() {
			statusSummary.setSummary();
		});

		workSummary = new CalculateSummary();
		workSummary.elementInput = 'input.work';
		workSummary.elementSummary = '#SUM_WORK';
		workSummary.setSummary();
		$(workSummary.elementInput).on('keyup', function() {
			workSummary.setSummary();
		});

		kpiSummary = new CalculateSummary();
		kpiSummary.elementInput = 'input.kpi';
		kpiSummary.elementSummary = '#SUM_KPI';
		kpiSummary.setSummary();
		$(kpiSummary.elementInput).on('keyup', function() {
			kpiSummary.setSummary();
		});
	});

	$(document).on('submit','#npSatSurveyProvinceForm',function(event){
		var sumGender = parseInt($('#SUM_GENDER').val());
		var flagSumbit = true;

		$('input[id^=SUM_').each(function(i,e) {
			var $e = $(e);

			if ($e.attr('id') != 'SUM_GENDER' && $e.attr('id') != 'SUM_KPI') {
				if ( parseInt($e.val()) > sumGender ) {
					flagSumbit = false;
					return flagSumbit;
				}
			}
		});

		if (!flagSumbit) {
			BootstrapDialog.alert('ผลรวมไม่ถูกต้อง');
			event.preventDefault();
		}
	});

	var CalculateSummary = function() {
		elementInput = '';
		elementSummary = '';
	}
	CalculateSummary.prototype.setSummary = function() {
		var sum = 0;
		$(this.elementInput).each(function(i, obj){
			var val = $(obj).val();

			if (val) {
				sum += parseInt(val);
			}
		});

		$(this.elementSummary).val(sum);
	}

	function setToViewMode() {
		$form = $('#npSatSurveyProvinceForm');
        $form.find('input').prop('disabled', true);
        $form.find('select').prop('disabled', true);

        $form.find('.panel-button').hide();
	}
</script>