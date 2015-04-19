<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
	use yii\web\Utils;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Modal;
	use yii\web\Authentication;

	AppAsset::register($this);

	$user_info = Authentication::getAuthenInfo();

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	JqGridWidget::widget();
?>
<style type="text/css">
.form-filtering .form-group {
	padding: 0 0 10px 0;
}
</style>
<!-- ######################################################################################### -->
<form id="frmsearch" class="form-horizontal form-filtering">
	<div class="panel panel-primary">
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-2 right">
					<label>ประจำปี</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						id="filterYear" name="YEAR">
		                <?php Utils::getOptionsYears(); ?>
		            </select>
				</div>
				<div class="col-md-1 right">
					<label>เดือน</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						id="filterMonth" name="MONTH">
		                <?php Utils::getOptionsMonth(); ?>
		            </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2 right">
					<label>จังหวัด</label>
				</div>
				<div class="col-md-3">
				<?php
				echo Utils::getDDLProvince_AutoComplete(null, null, 'PROVINCE_CODE',
							'PROVINCE_CODE',
							'PROVINCE_NAME_TH',
							$province,
							$user_info,
							['onchange' => 'Common.jqgrid.onFilter()']
							);
				?>
				</div>
			</div>
		</div>
	</div>
</form>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ################################################################################ -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['npsatsurveyprovince/gridview']);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx:'NP_SAT_SURVEY_PROVINCE_ID'});

	function gotoEdit(t){
	    var url = "<?=Url::to(['npsatsurveyprovince/form'])?>";
		url += '&id=' + jQuery(t).closest('tr[role="row"]').attr('id');
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	function gotoCreate() {
		var url = "<?=Url::to(['npsatsurveyprovince/form'])?>";
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	function getUrlCreate() {
		var url = "<?=Url::to(['npsatsurveyprovince/form'])?>";
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));

		return url;
	}

	function getFilterParam() {
		var frmsearch = jQuery('#frmsearch');
		var url = '&year='+frmsearch.find('#filterYear').val();
		url += '&month='+frmsearch.find('#filterMonth').val();
		url += '&province='+frmsearch.find('#PROVINCE_CODE').val();

		return url;
	}

	function getEditLink(cellValue, options, rowObject) {
		var status = cellValue;
		var year = rowObject[1];
		var month = rowObject[2];
        var className = 'glyphicon glyphicon-ban-circle';
        var fnClick = 'gotoEdit(this);';

        if ( isCurrentMonth(year, month) ) {
        	if (status == 'A' || status == 'S') {
	            className += 'glyphicon glyphicon-pencil';
	            //fnClick += 'gotoEdit(this);';
	        }
        } else {
        	if (status == 'S') {
	            className += 'glyphicon glyphicon-pencil';
	            //fnClick += 'gotoEdit(this);';
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
	   	colNames:['ลำดับที่',
	   				'ปี',
				   	'รหัสเดือน',
				   	'เดือน',
				   	'จังหวัด',
				   	'บันทึกเมื่อ',
				   	'ชาย',
				   	'หญิง',
				   	'3-5',
				   	'6-24',
				   	'25-34',
				   	'35-60',
				   	'>60',
				   	'รวม/คน',
				   	'แก้ไข'],
	   	colModel:[
	   		{name:'id',index:'NP_SAT_SURVEY_PROVINCE_ID', width:60, align:'center', sorttype:"int", editable: false},
	   		{name:'year',index:'YEAR', hidden:true},
	   		{name:'month',index:'MONTH', hidden:true},
	   		{name:'monthStr',index:'MONTH_STR', width:50, editable: false},
	   		{name:'province_code',index:'PROVINCE_CODE', width:80, editable:false},
	   		{name:'create_time',index:'CREATE_TIME', width:80, editable: false},
	   		{name:'num_male',index:'NUM_MALE',width:35, align:'center', editable: false, sorttype:"int"},
	        {name:'num_female',index:'NUM_FEMALE',width:35, align:'center', sorttype:"int", editable: false},
	        {name:'age_1',index:'AGE_1',width:35, align:'center', sorttype:"int", editable: false},
	        {name:'age_2',index:'AGE_2',width:35, align:'center', sorttype:"int", editable: false},
	        {name:'age_3',index:'AGE_3',width:35, align:'center', sorttype:"int", editable: false},
	        {name:'age_4',index:'AGE_4',width:35 , align:'center', sorttype:"int", editable: false},
	        {name:'age_5',index:'AGE_5',width:35 , align:'center', sorttype:"int", editable: false},
	        {name:'sum',index:'SUM',width:50, align:'center', sorttype:"int", editable: false},
	  		{name:'edit', index:'edit', width:50, editable:false, sortable:false, align:'center', formatter: getEditLink},
	   	],
		onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent) {},
		multiselect: true,
		editurl: gridurl_1,
		caption: '<?= $gridTitle; ?>',
		rowList:[10,20,30],
		pager: '#pagered',
		viewrecords: true,
		scrollOffset: 3,
		loadComplete: function(data){
			var frmsearch = jQuery('#frmsearch');
			var filterYear = frmsearch.find('#filterYear').val();
			var filterMonth = frmsearch.find('#filterMonth').val();

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

	// Rowspan Header
	jQuery("#rowed5").jqGrid('setGroupHeaders', {
		useColSpanStyle: true,
		groupHeaders:[
			{startColumnName: 'num_male', numberOfColumns: 2, titleText: '<p class="text-center">เพศ</p>'},
			{startColumnName: 'age_1', numberOfColumns: 5, titleText: '<p class="text-center">อายุ</p>'},
		]
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
	.navButtonAdd('#pagered', Common.jqgrid.getAddButton(getUrlCreate));
</script>