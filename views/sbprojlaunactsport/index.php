<?php
	use app\assets\AppAsset;
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) 
// 	{
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}
?>
<?php 
	$form = ActiveForm::begin([
	    'id' => 'submitForm',
	    'options' => ['class' => 'form-horizontal'],
	    'action' => Url::to(['sbprojlaunactsport/saved'])
	]); 
?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			ชื่อรูปแบบกิจกรรม <?php echo $sbProjLaunAct['ACT_NAME_TH']; ?>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-offset-1 col-md-10">
					<?php
						//echo Html::activeHiddenInput($model, 'SB_PROJ_LAUN_ACT_ID', ['value' => '555']);
						// echo $form->field($model, 'WA_SPORT_ID')->checkboxlist(
						// 					ArrayHelper::map($waSports, 'WA_SPORT_ID', 'SPORT_NAME_TH'),
						// 					// custom checkbox Ref. => http://stackoverflow.com/questions/27480061/yii2-checkboxlist-custom-class
						// 					[
						// 					'class' => 'checkbox',
						// 					'item' => function ($index, $label, $name, $checked, $value){
						// 								    return Html::checkbox($name, $checked, [
						// 								       'value' => $value,
						// 								       'label' => $label,
						// 								       'labelOptions' => ['class' => 'col-md-3'],
						// 								       'class' => '',
						// 								    ]);
						// 								}
						// 					]); 

						echo Html::hiddenInput('SB_PROJ_LAUN_ACT_ID', $sb_proj_laun_act_id);
						echo Html::checkboxlist('WA_SPORT_ID', 
											$sbProjLaunActSports? ArrayHelper::getColumn($sbProjLaunActSports, 'WA_SPORT_ID'): null, 
											ArrayHelper::map($waSports, 'WA_SPORT_ID', 'SPORT_NAME_TH'),
											// custom checkbox Ref. => http://stackoverflow.com/questions/27480061/yii2-checkboxlist-custom-class
											[
											'class' => 'checkbox',
											'item' => function ($index, $label, $name, $checked, $value){
														    return Html::checkbox($name, $checked, [
														       'value' => $value,
														       'label' => $label,
														       'labelOptions' => ['class' => 'col-md-3'],
														       'class' => '',
														    ]);
														}
											]); 
					?>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-offset-1 col-md-10 text-center">
					<?php
					 	echo Html::submitButton('บันทึก', ['class' => 'btn btn-primary', 'name' => 'submit-button']);
					?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>
