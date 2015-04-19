<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\BaseArrayHelper;
	use yii\bootstrap\ActiveForm;
	use app\models\WA_PROVINCE;
	use app\models\WA_AMPHOE;
	use yii\web\Utils;
	use yii\web\Authentication;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	$user_info = Authentication::getAuthenInfo();
	
	$is_province_user = !empty($user_info->LDAP_ORG_CODE)&&$user_info->LDAP_ORG_CODE=='NPPROVINCE'?true:false;

	$province = Yii::$app->getRequest()->getQueryParam('province');
	$amphoe = Yii::$app->getRequest()->getQueryParam('amphoe');
?>
<style>
	.form-group {
		margin-bottom: 15px;
	}
	.cell-highlight {
		background-color: pink;
	}
</style>
<!-- ################################################################################### -->
<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
	<div class="panel panel-primary">
	  <div class="panel-body">
	  		<div class="form-group">
	        	<div class="col-md-2 control-label">
					<label>ประจำปี</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="V_NP_SUMMARY_AMPHOE.YEAR">
                <?php Utils::getOptionsYears(); ?>
           			 </select>
				</div>
				<div class="col-md-2 control-label">
					<label>เดือน</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="V_NP_SUMMARY_AMPHOE.MONTH">
                <?php Utils::getOptionsMonth(); ?>
            		</select>
				</div>
			</div>
	        <div class="form-group">
	        	<?= Html::label('จังหวัด', 'V_NP_SUMMARY_AMPHOE.PROVINCE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
// 		            $province = '';
// 		            $amphoe = '';
		            echo Utils::getDDLProvince_AutoComplete(null, null, 'V_NP_SUMMARY_AMPHOE.PROVINCE_CODE',
		            		'PROVINCE_CODE',
		            		'PROVINCE_NAME_TH',
		            		$province,
		            		$user_info,
		            		['onchange' => 'Common.ajax.onChangeProvince(this, \'V_NP_SUMMARY_AMPHOE.AMPHOE_CODE\', \''.$amphoe.'\')']
		            );
					?>
				</div>
	        	<?= Html::label('อำเภอ', 'V_NP_SUMMARY_AMPHOE.AMPHOE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
		            echo Utils::getDDLAmphoe_AutoComplete(null, null, 'V_NP_SUMMARY_AMPHOE.AMPHOE_CODE',
		            		'AMPHOE_CODE',
		            		'AMPHOE_NAME_TH',
		            		$amphoe,
		            		$user_info,
		            		$province,
		            		['onchange' => 'Common.jqgrid.onFilter()']
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
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['vnpsummaryamphoe/gridview']);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'YEAR'});

	function updateStatus(obj) {
		<?php if($is_province_user){echo 'return false;';}else{?>
		var url = "<?=Url::to(['vnpsummaryamphoe/ajax-update-status']);?>";
        var $rowObj = $(obj).closest('tr[role="row"]');
        var status = $rowObj.find('td[aria-describedby="rowed5_status"]').html();
        var provinceCode = $rowObj.find('td[aria-describedby="rowed5_province_code"]').html();
        var amphoeCode = $rowObj.find('td[aria-describedby="rowed5_amphoe_code"]').html();
        var month = $rowObj.find('td[aria-describedby="rowed5_month"]').html();
        var year = $rowObj.find('td[aria-describedby="rowed5_year"]').html();
        var msgConfirm = '';
        if (status == 'S') {
			msgConfirm = 'ยืนยัน การยกเลิกบันทึกย้อนหลัง';
        } else {
			msgConfirm = 'ยืนยัน การบันทึกย้อนหลัง';
        }

		BootstrapDialog.confirm(msgConfirm,
		function(result) {
			if (result) {
				$('.loading').show();
				$.ajax({
					type: 'POST',
					url: url,
					dataType: 'json',
					data: {status:status,
							province_code:provinceCode,
							amphoe_code:amphoeCode,
							month:month,
							year:year}
				})
				.done(function(data, textStatus, jqXHR) {
					if (data.result == '1') {
						var $tdStatus = $rowObj.find('td[aria-describedby="rowed5_status"]');
						var $tdStatusStr = $rowObj.find('td[aria-describedby="rowed5_status_str"]');
						
						BootstrapDialog.alert('บันทึกข้อมูลสำเร็จ');

						$tdStatus.attr('title', data.status);
						$tdStatus.html(data.status);

		                $tdStatusStr.attr('title', data.status_str);
		                $tdStatusStr.html(data.status_str);
		                $(obj).closest('td').html(getEditLink(data.status, null, ['', year, month]));
					} else {
						BootstrapDialog.alert('เกิดข้อผิดพลาด');
					}
				})
				.fail(function(data, textStatus, errorThrown) {
					console.log(errorThrown);
		            BootstrapDialog.alert('เกิดข้อผิดพลาด');
				})
				.always(function(data, textStatus, jqxhr_error) {
					$('.loading').hide();
				});
			}
		});
		<?php }?>
	}

	function onChangeProvince(t){
	    var province_id=t.value;
	    var data = {province : province_id};
	    if(province_id!=''){
	        jQuery.post('<?=Url::to(["common/getddlampore"]);?>', data, function(data){
	            //on ajax success.
	            //console.log(data);
	            var jElm = jQuery('#frmsearch').find('select[name="V_NP_SUMMARY_AMPHOE.AMPHOE_CODE"]');
	            jElm.find('option').remove();
	            jElm.append('<option value="">กรุณาเลือก</option>');
	            jQuery.each(data, function(i, row){
	                jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
	            });
	            jElm.change();
	        }, 'json');
	    }
	}

	function getEditLink(cellValue, options, rowObject) {
		var status = cellValue;
		var year = rowObject[1];
		var month = rowObject[2];
		var filterYear = $('select[name="V_NP_SUMMARY_AMPHOE.YEAR"]').val();
		var filterMonth = $('select[name="V_NP_SUMMARY_AMPHOE.MONTH"]').val();
        var className = 'glyphicon glyphicon-ban-circle';
        var fnClick = '';

        // filters are current year
        if ('<?= $currentYear ?>' == filterYear
			&& '<?= $currentMonth ?>' == filterMonth) {
			if (status == 'A' || status == 'S') {
				className += 'glyphicon glyphicon-pencil';
				fnClick += 'updateStatus(this);';
			} else {
				className += 'glyphicon glyphicon-remove';
				fnClick += 'updateStatus(this);';
			}
        } else {
			if (status == 'S') {
				className += 'glyphicon glyphicon-pencil';
				fnClick += 'updateStatus(this);';
			} else {
				className += 'glyphicon glyphicon-remove';
				fnClick += 'updateStatus(this);';
			}
        }

        return '<span style="<?=($is_province_user?'cursor:default;color:lightgray;':'margin:auto;cursor:pointer;')?>" onclick="'+fnClick+'" class="'+className+'<?=($is_province_user?' disabled':'')?>" data-status="'+status+'"></span>';
    }

    function getCustomCountField(rowId, val, rawObject, cm, rdata) {
		var className = '';

		if (cm.name == 'count_form1' && val == 9) {
			className = 'cell-highlight';
		} else if (cm.name == 'count_form2_1' && val == 3) {
			className = 'cell-highlight';
		} else if (cm.name == 'count_form2_2' && val == 3) {
			className = 'cell-highlight';
		} else if (cm.name == 'count_sat' && val == 1) {
			className = 'cell-highlight';
		}

		return "class="+className;
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
				   	'รหัสจังหวัด',
				   	'จังหวัด',
				   	'รหัสอำเภอ',
				   	'อำเภอ',
				   	'ผลการปฏิบัติงาน',
				   	'ภายใน',
				   	'ภายนอก',
				   	'แบบสำรวจ',
				   	'status',
				   	'สถานะ',
				   	'การแก้ไข'],
	   	colModel:[
	  	   		{name:'id',index:'seq', width:60, sortable:false, editable: false, align:'center'},
		   		{name:'year',index:'YEAR', width:80, sortable:true, sorttype:'int', editable: false},
		   		{name:'month',index:'MONTH', width:50, editable:false, hidden:true, editrules:{edithidden:false}},
		   		{name:'month_str',index:'MONTH_STR' ,width:100, sortable:true, editable: false},
		   		{name:'province_code', index:'PROVINCE_CODE', width:50, editable:false, hidden:true, editrules:{edithidden:false}},
		   		{name:'province_name_th', index:'PROVINCE_NAME_TH', width:120, sortable:true, editable: false},
		   		{name:'amphoe_code',index:'AMPHOE_CODE', width:50, editable:false, hidden:true, editrules:{edithidden:false}},
		   		{name:'amphoe_name_th',index:'AMPHOE_NAME_TH', width:120, sortable:true, editable: false},
		   		{name:'count_form1', index:'COUNT_FORM1', width:100, sortable:true, sorttype:'int', editable: false, align:'center', cellattr: getCustomCountField},
		   		{name:'count_form2_1', index:'COUNT_FORM2_1', width:100, sortable:true, sorttype:'int', editable: false, align:'center', cellattr: getCustomCountField},
		   		{name:'count_form2_2', index:'COUNT_FORM2_2', width:100, sortable:true, sorttype:'int', editable: false, align:'center', cellattr: getCustomCountField},
		   		{name:'count_sat', index:'COUNT_SAT', width:80, sortable:true, sorttype:'int', editable:false, align:'center', cellattr: getCustomCountField},
		   		{name:'status',index:'STATUS', hidden:true},
		   		{name:'status_str',index:'STATUS_STR', width:80, sortable:true, editable: false, align:'center'},
		   		{name:'edit', index:'edit', width:80, editable:false, sortable:false, align:'center', formatter: getEditLink},
	   	],
	   	onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
			
		},
		multiselect: false,
		editurl: gridurl_1,
		caption: "NP006",
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: Common.jqgrid.onGridCompleted,
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
			{startColumnName:'count_form1', numberOfColumns:4, titleText: '<p class="text-center">จำนวนการรายงาน</p>'}
		]
	});

	jQuery("#rowed5").jqGrid('navGrid', '#pagered', {
		edit: false,
		add: false,
		del: false,
			refresh: false
	}, 
	{}, 
	{
		height: 280,
		reloadAfterSubmit: true
	}, 
	{
		reloadAfterSubmit: false
	}, 
	{
		multipleSearch: true,
		multipleGroup: false
	});
</script>