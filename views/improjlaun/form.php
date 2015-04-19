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
	use yii\helpers\ArrayHelper;
	use yii\helpers\Json;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	use yii\jui\DatePicker;
	use himiklab\jqgrid\JqGridWidget;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	//$this->registerJs("$('nav#menu').mmenu();");
?>

<?php $form = ActiveForm::begin([
    'id' => 'imProjLaunForm',
    'method' => 'POST',
    'action' => empty($model['IM_PROJ_LAUN_ID'])? Url::to(['improjlaun/create']): Url::to(['improjlaun/update']),
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
]); ?>
	<div class="panel panel-primary">        
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER_SECTION_1'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<?= $form->field($model, 'IM_PROJ_LAUN_ID')->hiddenInput(); ?>

				<?= Html::activeLabel($model, 'IM_PROJ_ID', ['class'=>'col-sm-2 control-label']); ?>
				<div class="col-sm-3">
			        <?php
			        	$imProjList = ArrayHelper::merge($firstOptionDDL,
			        									ArrayHelper::map($imProjs, 'IM_PROJ_ID', 'PROJ_NAME_TH'));

			        	echo $form->field($model, 'IM_PROJ_ID', ['options' => ['class' => '']])
			        				->dropDownList($imProjList,
													['class' => 'form-control im-proj-list']); 
					?>
			    </div>

			    <?= Html::activeLabel($model, 'BUDGET_YEAR', ['class'=>'col-sm-offset-3 col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_YEAR', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control gender']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'PROJ_RESP', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-3">
			        <?= $form->field($model, 'PROJ_RESP', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control gender']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'FROM_DATE', ['class'=>'col-sm-2 control-label']); ?>
			    <?= $form->field($model, 'FROM_DATE', ['options' => ['class' => 'col-sm-2']])->widget(DatePicker::classname()) ; ?>

				<?= Html::activeLabel($model, 'TO_DATE', ['class'=>'col-sm-1 control-label']); ?>
			    <?= $form->field($model, 'TO_DATE', ['options' => ['class' => 'col-sm-2']])->widget(DatePicker::classname()) ; ?>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'SATISFACTION_LEVEL', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'SATISFACTION_LEVEL', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'PLACE', ['class'=>'col-sm-1 control-label']); ?>
			    <div class="col-sm-4">
			        <?= $form->field($model, 'PLACE', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control age']); ?>
			    </div>

			   	<?= Html::activeLabel($model, 'STATUS', ['class'=>'col-sm-1 control-label']); ?>
		        <?= $form->field($model, 'STATUS', ['options' => ['class' => 'col-sm-2']])
		        				->checkbox(['value' => 'A', 
					        				'uncheck' => 'C', 
					        				'class' => 'form-control'], 
					        				false); ?>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'BUDGET', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

				<?= Html::activeLabel($model, 'BUDGET_TTPROV', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_TTPROV', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'BUDGET_INST_SPORT', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_INST_SPORT', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>
			</div>
			<div class="form-group">
				<?= Html::activeLabel($model, 'BUDGET_SCHL_SPORT', ['class'=>'col-sm-offset-4 col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_SCHL_SPORT', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>

			    <?= Html::activeLabel($model, 'BUDGET_MT_CENTER', ['class'=>'col-sm-2 control-label']); ?>
			    <div class="col-sm-2">
			        <?= $form->field($model, 'BUDGET_MT_CENTER', ['options' => ['class' => '']])
			        				->textInput(['class' => 'form-control edu']); ?>
			    </div>
			</div>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-footer">
			<div class="form-group">
				<div class="col-md-12 text-center">
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