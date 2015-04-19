<?php
use app\assets\AppAsset;
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\Utils;
use app\models\CONST_PROJ;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
AppAsset::register($this);



/*$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ; */

$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'action' => Url::to(['constproj/save']),
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

// $model = new \app\models\CONST_PROJ ;
$model = $constProj;
$mode = (empty($id)?'add':'edit'); //add, edit.
$isEditMode = !empty($constProj);
if($isEditMode){
	$model->STATUS = ($model->STATUS=='A'?1:0);
}
?>
<div class="panel panel-primary">        
        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER1'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PROJ_NAME_TH'); ?></label> </div>
            <div class="col-md-5" >   <?php echo $form->field($model, 'PROJ_NAME_TH')->textInput(['maxlength' => 100]) ; ?>  </div>
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('BUDGET'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'BUDGET')->textInput() ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONTRACT_NO'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'CONTRACT_NO')->textInput() ; ?>  </div>      
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('START_DATE'); ?></label> </div>
            <div class="col-md-2"><?php echo $form->field($model, 'START_DATE')
                                                ->widget(Datepicker::classname()); ?></div>                            
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('GRANT_ORG'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'GRANT_ORG')->textInput() ; ?>  </div>
                                           
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('END_DATE'); ?></label> </div>
            <div class="col-md-2"><?php echo $form->field($model, 'END_DATE')
                                                ->widget(Datepicker::classname()); ?></div>   
        </div>    
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RESP1'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'RESP1')->textInput() ; ?>  </div>
                                           
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('STATUS'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'STATUS')->checkbox()->label(false) ; ?>  </div>   
        </div>            
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RESP2'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'RESP2')->textInput() ; ?>  </div>
        </div>     
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('RESP3'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'RESP3')->textInput() ; ?>  </div>
        </div>        
    </div> 
</div>      
<div class="panel panel-primary">

  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER2'); ?></h3>
  </div>
  <div class="panel-body">      
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PLACE'); ?></label> </div>
            <div class="col-md-5">  <?php echo $form->field($model, 'PLACE')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ADDR'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'ADDR')->textInput() ; ?>  </div>      
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MOO'); ?></label> </div>
            <div class="col-md-1">  <?php echo $form->field($model, 'MOO')->textInput() ; ?>  </div>                            
        </div>
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('SOI'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'SOI')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('ROAD'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'ROAD')->textInput() ; ?>  </div>      
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TUMBOL'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'TUMBOL')->textInput() ; ?>  </div>                            
        </div>   
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('PROVINCE_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'PROVINCE_CODE')->dropDownList(ArrayHelper::map(WA_PROVINCE::find()->all(), 'PROVINCE_CODE', 'PROVINCE_NAME_TH'),['onchange'=>'onChangeProvince(this)']) ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('AMPHOE_CODE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'AMPHOE_CODE')->dropDownList(ArrayHelper::map(WA_AMPHOE::find()->where(['PROVINCE_CODE'=>'10'])->all(), 'AMPHOE_CODE', 'AMPHOE_NAME_TH')) ; ?>  </div>
            
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('POST_CODE'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'POST_CODE')->textInput() ; ?>  </div>
        </div>
   </div> 
</div>
<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER3'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONTRACTOR'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'CONTRACTOR')->textInput() ; ?>  </div>
        </div>       
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('CONTRACTOR_ADDR'); ?></label> </div>
            <div class="col-md-6">  <?php echo $form->field($model, 'CONTRACTOR_ADDR')->textInput() ; ?>  </div>
        </div>          
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('TEL'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'TEL')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('FAX'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'FAX')->textInput() ; ?>  </div>      
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('MOBILE'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'MOBILE')->textInput() ; ?>  </div>                            
        </div>   
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('EMAIL'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'EMAIL')->textInput() ; ?>  </div>
        </div>    
   </div> 
</div> 

<div class="panel panel-primary">        
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo $model->getAttributeLabel('HEADER4'); ?></h3>
  </div>
  <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('LATI'); ?></label> </div>
            <div class="col-md-2">  <?php echo $form->field($model, 'LATI')->textInput() ; ?>  </div>
            
            <div class="col-md-1" style="text-align:right;" >  <label style="padding-top:8px;"><?php echo $model->getAttributeLabel('LONGI'); ?></label> </div>
            <div class="col-md-3">  <?php echo $form->field($model, 'LONGI')->textInput() ; ?>  </div>    
        </div>   
        <div class="form-group">
          <div class="col-md-1"></div>
          <div class="col-md-1"><div id="map" style="width: 800px; height: 400px;"></div></div>
        </div>     
  </div>
    
<div class="footcontentbutton">
    <?=$form->field($model, 'CONST_PROJ_ID')->hiddenInput()?>
    <input type="hidden" name="mode" value="<?=$mode?>" />
    <?php 
    	foreach($backAction as $key=>$value){
			echo '<input type="hidden" name="return['.$key.']" value="'.$value.'" />';
		}
    ?>
    <!-- <a onclick="window.location='<?=Url::to($backAction)?>'" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Back</a> -->
    <a onclick="jQuery(this).closest('form').submit()" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
</div>    

<?php ActiveForm::end() ?>
<script type="text/javascript">

function onChangeProvince(t){
    var province_id=t.value;
    var data = {province : province_id};
    if(province_id!=''){
        jQuery.post('<?=Url::to(['common/getddlampore']);?>', data, function(data){
            //on ajax success.
            //console.log(data);
            jQuery('#const_proj-amphoe_code').find('option').remove();
            jQuery.each(data, function(i, row){
                jQuery('#const_proj-amphoe_code').append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
            });
        }, 'json');
    }
}

function onClickSave(){
    var nameth = jQuery('#nameth').val();
    var data = {nameth: nameth};    
    
    jQuery.post('<?=Url::to(['constproj/save']);?>', data, function(data){
        //on ajax success.
        alert('Success');
        
    }, 'json');
    
}
var constProJname = jQuery('#const_proj-proj_name_th').val();
var lati = jQuery('#const_proj-lati').val();
var longi = jQuery('#const_proj-longi').val();

var locations = [
 [constProJname, lati, longi, 3],
];

var map = new google.maps.Map(document.getElementById('map'), {
  zoom: 10,
  center: new google.maps.LatLng(13.898374899999999, 100.5250784),
  mapTypeId: google.maps.MapTypeId.ROADMAP
});

var infowindow = new google.maps.InfoWindow();

var marker, i;
marker = new google.maps.Marker({
    position: new google.maps.LatLng(locations[0][1], locations[0][2]),
    map: map
  });
  

google.maps.event.addListener(map, 'click', function(event) {
// 	  alert('AAA');
	    //alert('Point.X.Y: ' + event.latLng);
	    //alert( 'Lat: ' + event.latLng.lat() + ' and Longitude is: ' + event.latLng.lng() );	  
	    lati = event.latLng.lat();
	    longi = event.latLng.lng();
	    
// 	    map = new google.maps.Map(document.getElementById('map'), {
// 		  zoom: 10,
// 		  center: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()),
// 		  mapTypeId: google.maps.MapTypeId.ROADMAP

// 	    });  
        marker.setMap(null);
        
	    marker = new google.maps.Marker({
	        position: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()),
	        map: map
	    });

	    jQuery('#const_proj-lati').val(event.latLng.lat());
	    jQuery('#const_proj-longi').val(event.latLng.lng());
});

google.maps.event.addListener(marker, 'click', (function(marker, i) {
  return function() {
    infowindow.setContent(locations[0][0]);
    infowindow.open(map, marker);
    //alert('Point.X.Y: ' + event.latLng);
  }
})(marker, i));
</script>