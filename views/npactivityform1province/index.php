<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\BaseArrayHelper;
	use yii\bootstrap\ActiveForm;
	use app\models\WA_PROVINCE;
	use app\models\WA_AMPHOE;
	use app\models\NP_WORK_GROUP_ACTIVITY;
	use yii\web\Utils;
	use yii\web\Authentication;
	AppAsset::register($this);

	$user_info = Authentication::getAuthenInfo();
	
// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}
	
    $act_id = Yii::$app->getRequest()->getQueryParam('act_id');
    $work_id = Yii::$app->getRequest()->getQueryParam('work_id');
    $year = Yii::$app->getRequest()->getQueryParam('year');
    $month = Yii::$app->getRequest()->getQueryParam('month');
    $province = Yii::$app->getRequest()->getQueryParam('province');
    
// 	$where_work = "NP_WORK_GROUP_ID="."'".$work_id."'";
	//print_r($arrAct);
?>
<style>
	.form-group {
		margin-bottom: 15px;
	}
</style>
<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
	<div class="panel panel-primary">
	  <div class="panel-body">
	  		<div class="form-group">
	        	<div class="col-md-2 control-label">
					<label>ประจำปี</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="NP_ACTIVITY_FORM1_PROVINCE.YEAR">
                <?php Utils::getOptionsYears($year); ?>
           			 </select>
				</div>
				<div class="col-md-2 control-label">
					<label>เดือน</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="NP_ACTIVITY_FORM1_PROVINCE.MONTH">
                <?php Utils::getOptionsMonth($month); ?>
            		</select>
				</div>
			</div>
	        <div class="form-group">
	        	<?= Html::label('จังหวัด', 'NP_ACTIVITY_FORM1_PROVINCE.PROVINCE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
			        	echo Utils::getDDLProvince_AutoComplete(null, null, 'NP_ACTIVITY_FORM1_PROVINCE.PROVINCE_CODE',
							'PROVINCE_CODE',
							'PROVINCE_NAME_TH',
							$province,
							$user_info,
							['onchange' => 'Common.jqgrid.onFilter()']
							); 
					?>
				</div>
        </div> 
        <div class="form-group">
	        	<?= Html::label('รายงานการปฎิบัติงาน', 'NP_ACTIVITY_FORM1_PROVINCE.NP_WORK_GROUP_ACTIVITY_ID', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-8">
		            <?php
		            
			            echo Utils::getDropDownList(
			            		'NP_WORK_GROUP_ACTIVITY', 	/*Model Name*/
			            		'NP_ACTIVITY_FORM1_PROVINCE.NP_WORK_GROUP_ACTIVITY_ID', 	/*Declare Name*/
			            		'NP_WORK_GROUP_ACTIVITY_ID',	/*Value Field*/
			            		'ACTIVITY_SUBJECT',				/*Text Field*/
			            		['NP_WORK_GROUP_ID'=>$work_id],	/*Where Cause*/
			            		$act_id,						/*Defaul Option Value*/
			            		['onchange' => 'Common.jqgrid.onFilter()']	/*More Element Attribute*/
			            );
					?>
				</div>
			</div>
    </div>
 </div>
<?= Html::endForm(); ?>
<?php
	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['npactivityform1province/gridview']);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'NP_ACTIVITY_FORM1_PROVINCE.NP_ACTIVITY_FORM1_PROVINCE_ID'});

	function getFilterParam(){
		var frmsearch = jQuery('#frmsearch');
		var url = '&act_id='+frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.NP_WORK_GROUP_ACTIVITY_ID"]').val();
		
		url += '&work_id=<?=$work_id?>';
		url += '&year='+frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.YEAR"]').val();
		url += '&month='+frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.MONTH"]').val();
		url += '&province='+frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.PROVINCE_CODE"]').val();
		
		return url;
	}

	// TODO: go to create
	function gotoView2(t){
		var url='<?=Url::to(['npactivityform1province/add'])?>';
		url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));

		window.location=url;
	}

	// TODO: got to edit
	function gotoView3(){
		var url='<?=Url::to(['npactivityform1province/add'])?>';
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));

		window.location=url;
	}

	function getUrlView3() {
		var url='<?=Url::to(['npactivityform1province/add'])?>';
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));

		return url;
	}

	function getEditLink(cellValue, options, rowObject) {
		var status = cellValue;
		var year = rowObject[3];
		var month = rowObject[4];
        var className = 'glyphicon glyphicon-ban-circle';
        var fnClick = 'gotoView2(this);';

        if ( isCurrentMonth(year, month) ) {
        	if (status == 'A' || status == 'S') {
	            className += 'glyphicon glyphicon-pencil';
	            fnClick += 'gotoView2(this);';
	        }
        } else {
        	if (status == 'S') {
	            className += 'glyphicon glyphicon-pencil';
	            fnClick += 'gotoView2(this);';
	        }
        }

        return '<span style="margin:auto;cursor:pointer;" onclick="'+fnClick+'" class="'+className+'" data-status="'+status+'"></span>';
    }

    function isCurrentMonth(year, month) {
    	if (year == '<?= $currentYear; ?>' && month == '<?= $currentMonth; ?>') {
    		return true;
    	} else {
    		return false;
    	}
    }

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: function() {
			Common.jqgrid.onFilter();
		},
		height: 425,
		width: jQuery('div.content').width()-20,
	   	colNames:['ที่',
				   	'หัวข้อ',
				   	'วันที่เริ่มต้น',
				   	'ปี',
				   	'รหัสเดือน',
				   	'เดือน',
				   	'จังหวัด',
				   	'วิธีการดำเนินงาน',
				   	'ผลการดำเนินงาน',
				   	'จำนวนบุคลากร',
				   	'แก้ไข'],
	   	colModel:[
	  	   		{name:'id',index:'NP_ACTIVITY_FORM1_PROVINCE_ID', align:'center', width:40, sorttype:"int", sortable:true, editable: false},
		   		{name:'subject',index:'SUBJECT', width:150, sortable:true, editable: false},
		   		{name:'s_date',index:'START_DATE' ,width:60, sortable:true,editable: false},
		   		{name:'year',index:'YEAR', width:60, sortable:true,editable: false},
		   		{name:'month',index:'MONTH', width:60, editable: false, hidden:true},
		   		{name:'monthStr',index:'MONTH_STR', width:60, sortable:true,editable: false},
		   		{name:'province', index:'PROVINCE_NAME_TH', width:200, sortable:true, editable: false},
		   		{name:'s_detial',index:'SUBJECT_DETAIL', width:140, sortable:false,editable: false},
		   		{name:'s_result',index:'SUBJECT_RESULT', width:140, sortable:true, editable: false},
		   		{name:'p_num', index:'PARTICIPANTS_NUM', width:100, editable: false},
		   		{name:'edit', index:'edit', width:50, editable:false, sortable:false, align:'center', formatter: getEditLink},
	   	],
	   	onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){},
		multiselect: true,
		editurl: gridurl_1,
		caption: '<?=$title; ?>',
		rowList:[10,20,30],
		pager: '#pagered',
		viewrecords: true,
		scrollOffset: 3,
		loadComplete: function(data) {
			var frmsearch = jQuery('#frmsearch');
			var filterYear = frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.YEAR"]').val();
			var filterMonth = frmsearch.find('[name="NP_ACTIVITY_FORM1_PROVINCE.MONTH"]').val();

			if ( isCurrentMonth(filterYear, filterMonth) ) {
				$('.panel-add-button').show();
				$('#del_rowed5').show();
			} else {
				if (data.add_status == 'S') {
					$('.panel-add-button').show();
					$('#del_rowed5').show();
				} else {
					$('.panel-add-button').hide();
					$('#del_rowed5').hide();
				}
			}
		},
		rowNum:rtparams.rows,
		sortname: rtparams.sidx,
		sortorder: rtparams.sord,
		page: rtparams.page,
	//var rtparams = {_search: false, rows: 10, page: 1, sidx: "SEQ", sord: "asc", filters: "", searchField: "", searchOper: "", searchString: ""};
	});


	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		edit: false,
		add: false,
		del: true,
		refresh: false
	},
	{},
	{
		height: 280,
		reloadAfterSubmit: true
	},
	Common.jqgridOptions.navGridDelete,
	{
		multipleSearch: true,
		multipleGroup: false
	})
	.navButtonAdd('#pagered', Common.jqgrid.getAddButton(getUrlView3));
</script>