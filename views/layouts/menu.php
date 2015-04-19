<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\web\Authentication;
use himiklab\jqgrid\JqGridWidget;
use app\models\WA_GROUP;
use app\models\WA_GROUP_USER;
use app\models\WA_MENU_SUB;

/* @var $this \yii\web\View */
/* @var $content string */
$this->title='DPE E-Warning System - '.$this->context->title;
$this->beginPage();
AppAsset::register($this);
$this->registerJs("$('#menu2').metisMenu({toggle:false});");

		$authenInfo = Authentication::getAuthenInfo();

        if(!empty($authenInfo->IS_LDAP_AUTHEN)){
        	
        	$model = WA_GROUP::find()
        	->where(['LDAP_ORG_CODE' => $authenInfo->LDAP_ORG_CODE])
        	->one();
        	$grp_id = (isset($model->WA_GROUP_ID)?$model->WA_GROUP_ID:'');
        	
        }else{
        
			$user_id = $authenInfo->IDENTITY;
			$model = WA_GROUP_USER::find()
			->where(['WA_USER_ID' => $user_id])
			->one();
			$grp_id = $model->WA_GROUP_ID;
		
        }
		
		$command = Yii::$app->db->createCommand("select distinct C.MENU_MAIN_NAME_TH,C.WA_MENU_MAIN_ID,c.SEQ from WA_GROUP_ROLE a join WA_MENU_SUB b on (a.WA_MENU_SUB_ID = b.WA_MENU_SUB_ID) join WA_MENU_MAIN c on (C.WA_MENU_MAIN_ID = b.WA_MENU_MAIN)
where a.WA_GROUP_ID = '$grp_id' AND c.STATUS ='A' ORDER BY c.SEQ");
		$result= $command->queryAll();

	
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="" lang="<?= Yii::$app->language ?>"> <!--<![endif]-->

<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <link rel="shortcut icon" type="image/ico" href="<?php echo Url::to('@web/images/favicon.ico'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript">
    
    	var APP = {};
	    APP.ajaxAction = {
	        	getProvince: '<?=Url::to(['common/getddlprovince']);?>',
	        	getAmphoe: '<?=Url::to(['common/getddlampore']);?>',
	        	uploadAnnoucementImg: '<?=Url::to(['waannouncement/uploadimg']);?>'
	        };

        APP.path = {
				nicEditorButtons: '<?=Yii::$app->request->baseUrl?>/assets/nicEdit/nicEditorIcons.gif'
        	};
    </script>
    <?php $this->head() ?>
    
    <style type="text/css">
        .ui-jqgrid .ui-jqgrid-view, .ui-jqgrid .ui-jqgrid-titlebar{
            font-size: 15px;
        }
        .ui-jqgrid {
            margin: auto;
        }
        .ui-jqgrid tr.jqgrow, .ui-jqgrid tr.ui-jqgrid-labels, div.ui-jqgrid-titlebar{
            height:40px;
        }
        .ui-jqgrid .ui-jqgrid-view input, .ui-jqgrid .ui-jqgrid-view select, .ui-jqgrid .ui-jqgrid-view textarea, .ui-jqgrid .ui-jqgrid-view button{
            font-size:15px;
        }
        .ui-jqgrid .ui-jqgrid-view textarea{
            margin-top: 5px;
        }
        .ui-jqgrid .ui-jqgrid-pager{
            height: 35px;
            font-size: 14px;
        }
        .ui-jqgrid .ui-pg-input{
            height: 25px;
            font-size: 14px;
        }
        .ui-jqgrid .ui-pg-selbox{
            height: 25px;
            font-size: 14px;
        }
        .ui-jqgrid .ui-jqgrid-hdiv, .ui-jqgrid .ui-jqgrid-titlebar, .ui-jqgrid .ui-jqgrid-pager{
            width:99.8%;
        }
        .ui-jqgrid .ui-jqgrid-titlebar{
        	padding-top:.6em;
        }
        .myAltRowClassEven { background: #F7F7F7; }
        input{
            color: #000;
        }
        #pagered span.glyphicon{
            padding: 2px 5px;
            font-size: 20px;
        }
        td.ltr{
            padding-top:4px;
        }
        div.modal-dialog{
            margin-top: 15%;
        }
        .ui-jqdialog{
        	font-size:14px;
        }
        .ui-jqdialog-content td.EditButton{
        	text-align:center;
        }
        legend{
            margin-bottom:5px;
        }
        .form-group{
            margin-bottom: 0px;
        }
        .form-filtering .form-group{
            padding: 0 0 10px 0;
        }
        select{
        	color: black;
        }
        .ui-jqgrid .ui-jqgrid-htable th div{
        	height: initial;
        }
        .form-horizontal .form-group{
        	margin-left: 0px;
        	margin-right: 0px;
        }

        /* Style for flag message */
        #flagMessage {
            position: fixed;
            top: 30px;
            left: 0;
            right: 0;
            height: 0;
            text-align: center;
            z-index: 1001;
        }
        #flagMessage .message {
            padding: 5px;
            color: black;
            background-color: #FAFAFA;
            border: 0.75px solid white;
            border-left: 5px solid white;
            font-size: 1.25em;
        }
        #flagMessage .message.error{
            color: red;
            border: 0.75px solid red;
            border-left: 5px solid red;
        }
        #flagMessage .message.success{
            color: green;
            border: 0.75px solid green;
            border-left: 5px solid green;
        }
        table.ui-jqgrid-htable tr.ui-jqgrid-labels input.cbox{
        	display: none;
        }
    </style>
</head>
<body onbeforeunload="loading();">
    <?php $this->beginBody() ?>
	<?php
		$alertmessage = Yii::$app->getRequest()->getQueryParam('alertmessage');
		if(!empty($alertmessage)) echo '<script type="text/javascript">BootstrapDialog.alert("'.base64_decode($alertmessage).'");</script>';
	?>
	<div class="header" id="headerSection">
	    <div class="header-left-label"></div>
	    <div class="logo"></div>
	    <div class="banner-linenumber"></div>
	    <div class="header-name"></div>
<!-- 	    <div class="ewarning-icon"></div> -->
	    <div class="header-right-label">
			<div class="set-user"><?php  echo "Welcome ".$authenInfo->USER_NAME_EN;?></div>
<!-- 			<div class="logout"><a href="?r=site/signout">Logout</a></div> -->
		</div>
	</div>
<?php 
	$nosuuportbrowser = '<div style="-webkit-border-radius: 5px;border-radius: 5px;  margin: 0 15px;border: 1px solid red;background-color: linen;padding: 20px;">Browser version นี้ไม่สามารถใช้งานได้ กรุณาใช้ Browser ที่รองรับ HTML5 หรือดาวน์โหลดได้ที่นี่ <a href="https://www.google.com/chrome/browser/desktop/index.html?system=true&standalone=1">คลิก</a></div>';
?>
<!--[if lt IE 7 ]><?=$nosuuportbrowser?><![endif]-->
<!--[if IE 7 ]><?=$nosuuportbrowser?><![endif]-->
<!--[if IE 8 ]><?=$nosuuportbrowser?><![endif]-->
	<aside  class="sidebar">
    <div class="sidebar-nav"  id="menu2">
        <div class="menu-header">DPE E-Warning System</div>
	 	<ul>
<?php 
	 		
			foreach ($result as $row) {

				$main_id =$row['WA_MENU_MAIN_ID'];
				$main_menu =$row['MENU_MAIN_NAME_TH'];
				//$menu_main =$row['WA_MENU_MAIN'];
				//$l_url =$row['MENU_LINK'];
	
				//array_push($arr_main_menu,$menu_main);
				echo '<li class="main">';
				echo "<a href=\"#\">".$main_menu.'<span class="glyphicon arrow"></a>';
				echo '<ul>';
				//echo '<li>';
				GetSub($main_id, $grp_id);
				//echo "<a href=\"#\">".$main_menu.'</a>';
				//echo '</li>';
				echo '</ul>';

				echo '</li>';
			
		}
		
		echo '<li class="main">'.
				'<a href="'.Url::to("/web/doc/manual_warning.pdf").'" target="_blank" style="background-color:#DBE6EC;">download คู่มือการใช้งาน<span class="glyphicon arrow"></a>'.
				'</li>';
		
		?>
      </ul>
</div>
</aside>
<?php
    /*$menusub = $request->post('menusub', ''); $menusub = (empty($menusub)?$request->get('menusub', '0'):$menusub);
    $menusub_nameth = '';
    // print_r('AAA'.$menusub);
    if(empty($menusub)){
        $menusub_nameth = 'DPE E-Warning System';
    }else{
        $waMenuSub = WA_MENU_SUB::findOne($menusub);
        $menusub_nameth=$waMenuSub->MENU_SUB_NAME_TH;
    }*/
    //echo $this->params['title'];
    // echo $this->context->title;
    // $menusub_nameth = 'DPE E-Warning System';
    // if(isset($title) && !empty($title)) $menusub_nameth = $title;
    
    // print_r($waMenuSub->MENU_SUB_NAME_TH);
?>
<!-- flash message -->
<div id="flagMessage"></div>
<!-- end flash message -->
<div class="content-breadcrumb">
	<div class="logout"><a href="?r=site/signout">Logout</a></div>
	<div class="breadcrumb1">
		<a class="menu-toggle-button glyphicon glyphicon-th-large"></a>
		<?php 
		    for($i=0; $i<count($this->context->breadcrumbs); $i++){
		        if(!$i==0){
		            echo ' > ';
		        }
		        // echo $breadcrumbs[$i]['label'].$breadcrumbs[$i]['link'];
		        if(empty($this->context->breadcrumbs[$i]['link'])){
		            echo $this->context->breadcrumbs[$i]['label'];
		        }else{
		            echo '<a href="'.$this->context->breadcrumbs[$i]['link'].'">'.$this->context->breadcrumbs[$i]['label'].'</a>';
		        }
		    }
		?>
	</div>
</div>
<!--div class="content-header"><?=$this->context->title?></div-->
<div class="content">
<noscript>
	<div style="-webkit-border-radius: 5px;border-radius: 5px;  margin: 0 15px;border: 1px solid red;background-color: linen;padding: 20px;">ไม่ Support Javascript กรุณาเปิดการใช้งาน Javascript!!! <!-- <br />กรุณาใช้ Browser อื่น<br />ดาวน์โหลดได้ที่ <a href="https://www.google.com/chrome/browser/desktop/index.html?system=true&standalone=1">คลิก</a> --></div>
</noscript>
    
    <?php echo $content; ?>
    
</div>
<br /><br />
<div id="block-page" class="loading-unblock"><div id="block-page_hdn"></div></div>
<div id="div_loading" class="loading-invisible">
    &nbsp;
    <table border="0" style="margin-left: auto;margin-right: auto;">
        <tr>
            <td align="center" valign="middle">
            		<?php //echo $this->Html->image('loading.gif', array('style'=>'border: 0;')); ?>
            		<?php echo Html::img(Url::to('@web/images/loading.gif')); ?>
            </td>
        </tr>
        <tr>
            <td align="center" valign="middle">
                <font size="2" color="#FFFFFF"><b><span class="message">Loading</span></b></font>
            </td>
        </tr>
    </table>
    &nbsp;
</div>
<?php //$this->endContent() ?>
<?php $this->endBody() ?>
<script type="text/javascript">

    jQuery('div.ui-jqgrid-hdiv, div.ui-jqgrid-pager').attr('style','');
    jQuery('.menu-toggle-button, .menu-toggle').click(function(){
        jQuery("aside.sidebar").toggle( 'slide', {complete:function(){
        		adjustGridWidth();
            }}, 300 );
    });
    jQuery('input:file').change(Common.utils.checkFileSize_OnChange);
    //jQuery('#'+jQuery('.ui-jqgrid').attr('id').replace('gbox_', '')).setGridWidth(600);
    
//     var jqgridID = jQuery('.ui-jqgrid').attr('id');
//     if(!jqgridID==undefined){
    
//         var gridObj = jQuery('#'+jqgridID.replace('gbox_', ''));
//         var gridHorObj = jQuery('.ui-jqgrid .ui-jqgrid-hdiv, .ui-jqgrid .ui-jqgrid-titlebar, .ui-jqgrid .ui-jqgrid-pager');
        
        jQuery(window).resize(function(a, b, c){
            //  console.log(a);
//             gridObj.setGridWidth(jQuery('div.content').width()-20);
//             gridHorObj.css('width', '99.8%');
        	adjustGridWidth();
        });
//     }

    function adjustGridWidth(){
    	var jqgridID = jQuery('.ui-jqgrid').attr('id');
        if(jqgridID!=undefined){
        
            var gridObj = jQuery('#'+jqgridID.replace('gbox_', ''));
            var gridHorObj = jQuery('.ui-jqgrid .ui-jqgrid-hdiv, .ui-jqgrid .ui-jqgrid-titlebar, .ui-jqgrid .ui-jqgrid-pager');
            
//             jQuery(window).resize(function(a, b, c){
                //  console.log(a);
                gridObj.setGridWidth(jQuery('div.content').width()-20);
//                 gridObj.find('.ui-jqgrid-hdiv').css('width', '99.8%');
				setTimeout(function(){ gridHorObj.css('width', '99.8%') }, 200);
//             });
        }
    }

    /* Flash message plugin */
    (function($) {
        $.fn.showFlashMessage = function(options) {
            options = $.extend(
            {
                text: 'Done',
                time: 5000,
                how: 'append',
                className: ''
            }, options);

            return $(this).each(function() {
                // TODO: check flash message exist
                if( $(this).find('.message').get(0) ) return;

                var message = $('<span />', 
                {
                    'class': 'message ' + options.className,
                    text: options.text
                }).hide().fadeIn('fast');

                $(this)[options.how](message);

                message.delay(options.time).fadeOut('normal', function() {
                  $(this).remove();
                });
            });
        };
    })(jQuery);

    $(function() {
        $('#addButton').click(function(e) {
            var $gridAddButton = ($('#rowed5_iladd').length!=0)? $('#rowed5_iladd'): $('#navBtnAdd');

            $gridAddButton.trigger('click');
        });
    });
</script>
<!-- show flash meassage -->
<?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        if ($key == 'error') {
            echo "<script type=\"text/javascript\">BootstrapDialog.alert(\"$message\");</script>";
        } else {
            echo "<script type=\"text/javascript\">Common.utils.showFlashMessage({text:'$message', className:'$key'});</script>";
        }
    }
?>
<!-- end show flash meassage -->
</body>
</html>
<?php $this->endPage() ?>
<?php 

function GetSub($main_menu, $grp_id){
	$command2 = Yii::$app->db->createCommand("select distinct a.WA_MENU_SUB_ID,b.*,b.MENU_LINK as LINK from WA_GROUP_ROLE a join WA_MENU_SUB b on (a.WA_MENU_SUB_ID = b.WA_MENU_SUB_ID) join WA_MENU_MAIN c on (C.WA_MENU_MAIN_ID = b.WA_MENU_MAIN)
		 where C.WA_MENU_MAIN_ID = '$main_menu' AND b.STATUS ='A' AND a.ACCESS_FLAG='Y' AND a.WA_GROUP_ID = '$grp_id' ORDER BY b.SEQ");
	$result2= $command2->queryAll();
	foreach ($result2 as $row) {
		$sub_menu =$row['MENU_SUB_NAME_TH'];
		$sub_link =$row['LINK'];
		$addition_parameter = (strpos($sub_link, '?')===false?'?':'&').'menusub='.$row['WA_MENU_SUB_ID'];
		echo '<li class="sub">';
		//echo "<a href=\"{$sub_link}\">".$sub_menu.'</a>';
		echo '';
		echo "<a class=\"\" href=\"".Url::to("{$sub_link}").$addition_parameter."\">".'<table><tr><td valign="middle"><span class="glyphicon glyphicon-circle-arrow-right menubulleticon"></span></td><td>'.$sub_menu.'</td></tr></table></a>';
		
		echo '</li>';
	}
	}
?>
