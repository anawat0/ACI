<?php
	use app\assets\AppAsset;
	use yii\helpers\Url;
	use yii\web\Authentication;

	AppAsset::register($this);
	if (!Authentication::isLoggedIn()) {
		\Yii::$app->getResponse()->redirect(['site/login']);
	}
	
	$this->registerJsFile('assets/nicEdit/nicEdit.js', ['position' => \yii\web\View::POS_BEGIN]);

?>

<textarea cols="50" id="area1"></textarea>

<script type="text/javascript">
	new nicEditor().panelInstance('area1');
</script>