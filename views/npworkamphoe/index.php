<?php
	use app\assets\AppAsset;
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use app\models\NP_WORK_GROUP;
	use yii\db\Query;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) 
// 	{
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}
?>

<?php $form = ActiveForm::begin([
    'id' => 'submitForm',
    'options' => ['class' => 'form-horizontal'],
    'action' => Url::to(['npwork/index'])
]); ?>
	

<?php
    $npwork  = NP_WORK_GROUP::find()->where("GROUP_TYPE=2 AND STATUS='A'")->all();

    foreach ($npwork as $row) { ?>
            
            <div class="panel panel-primary">        
                <div class="panel-heading"> <h3 class="panel-title"> <?=$GROUP_SUBJECT =$row['GROUP_SUBJECT']?></h3> </div>
                <div class="panel-body">
                    <div class="form-group">
<?php
        //echo "<div><label class='col-sm-8 control-label' style='text-align: left;'><a href='#'>".$GROUP_SUBJECT =$row['GROUP_SUBJECT']."</a></label>" ;
        $GROUP_ID =$row['NP_WORK_GROUP_ID'];
        $where_id  ="NP_WORK_GROUP_ACTIVITY.NP_WORK_GROUP_ID='".$GROUP_ID."'";
        $query = new Query;
        $query->select('NP_WORK_GROUP.FORM_TYPE,NP_WORK_GROUP_ACTIVITY_ID,ACTIVITY_SUBJECT,NP_WORK_GROUP_ACTIVITY.NP_WORK_GROUP_ID as NP_W_ID')->from('NP_WORK_GROUP')->innerJoin('NP_WORK_GROUP_ACTIVITY', 'NP_WORK_GROUP_ACTIVITY.NP_WORK_GROUP_ID=NP_WORK_GROUP.NP_WORK_GROUP_ID')->where($where_id);   
        $result = $query->all();
        $command = $query->createCommand();
        $result = $command->queryAll();
        $i=0;   
        
        foreach ($result as $row) {  $i++ ;  
            if ($row['FORM_TYPE'] == '1') { ?>                       
                        <div><label class="col-md-8" ><a href=index.php?r=npactivityform1amphoe&act_id=<?php echo $row['NP_WORK_GROUP_ACTIVITY_ID']; ?>&work_id=<?php echo $row['NP_W_ID']; ?>><?php echo $i." ".$GROUP_SUBJECT =$row['ACTIVITY_SUBJECT']?></a> </label></div>			
<?php       } else if ($row['FORM_TYPE'] == '2') {  ?>
                        <div><label class="col-md-8" ><a href=index.php?r=npactivityform2amphoe&act_id=<?php echo $row['NP_WORK_GROUP_ACTIVITY_ID']; ?>&work_id=<?php echo $row['NP_W_ID']; ?>><?php echo $i." ".$GROUP_SUBJECT =$row['ACTIVITY_SUBJECT']?></a> </label></div>			
<?php       } else {?>
                        <div><label class="col-md-8" ><?php echo $i." ".$GROUP_SUBJECT =$row['ACTIVITY_SUBJECT']?></label></div>			
<?php       }  
        } // for result ?>
                    </div>
                </div>
            </div>
<?php } // for $npwork ?>
            	
<?php ActiveForm::end(); ?>