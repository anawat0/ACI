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

	$user_info = Authentication::getAuthenInfo();

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}

	JqGridWidget::widget();

	$year = Yii::$app->getRequest()->getQueryParam('year');
	$month = Yii::$app->getRequest()->getQueryParam('month');
	$province = Yii::$app->getRequest()->getQueryParam('province');
	$amphoe = Yii::$app->getRequest()->getQueryParam('amphoe');
?>
<style>
	.form-group {
		margin-bottom: 15px;
	}
</style>
<!-- ################################################################################### -->
<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
	<div class="panel panel-primary">
	  <div class="panel-body">
	  		<div class="form-group">
				<label class="col-md-2 control-label">ประจำปี</label>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()" name="BUDGET_YEAR" id="filterBudgetYear">
               			<?php Utils::getOptionsYears($budgetYear); ?>
           			 </select>
				</div>
				<label class="col-md-2 control-label">เดือน</label>
				<div class="col-md-3">
					<select class="form-control" onchange="Common.jqgrid.onFilter()"
						name="MONTH" id="filterMonth">
                		<?php Utils::getOptionsMonth(); ?>
            		</select>
				</div>
			</div>
	        <div class="form-group">
	        	<?= Html::label('จังหวัด', 'RB_PROJ_LAUN_RES.PROVINCE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
		            
		            echo Utils::getDDLProvince_AutoComplete(null, null, 'RB_PROJ_LAUN_RES.PROVINCE_CODE',
		            		'PROVINCE_CODE',
		            		'PROVINCE_NAME_TH',
		            		$province,
		            		$user_info,
		            		['onchange' => 'Common.ajax.onChangeProvince(this, \'RB_PROJ_LAUN_RES.AMPHOE_CODE\', \''.$amphoe.'\')']
		            	);
		            
			        	/*$provinces = BaseArrayHelper::merge($firstOptionDDL, 
		        											BaseArrayHelper::map($waProvinces, 
		        																'PROVINCE_CODE', 
		        																'PROVINCE_NAME_TH'));
			        	
			        	echo Html::dropDownList('RB_PROJ_LAUN_RES.PROVINCE_CODE',
			        								null,
			        								$provinces,
													[
														'id' => 'RB_PROJ_LAUN_RES.PROVINCE_CODE',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter();onChangeProvince(this);'
													]); */
					?>
				</div>
	        	<?= Html::label('อำเภอ', 'RB_PROJ_LAUN_RES.AMPHOE_CODE', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-3">
		            <?php
		            
		            echo Utils::getDDLAmphoe_AutoComplete(null, null, 'RB_PROJ_LAUN_RES.AMPHOE_CODE',
		            		'AMPHOE_CODE',
		            		'AMPHOE_NAME_TH',
		            		$amphoe,
		            		$user_info,
		            		$province,
		            		['onchange' => 'Common.jqgrid.onFilter()']
		            	);
			        	/*echo Html::dropDownList('RB_PROJ_LAUN_RES.AMPHOE_CODE',
			        								null,
			        								$firstOptionDDL,
													[
														'id' => 'RB_PROJ_LAUN_RES.AMPHOE_CODE',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter();'
													]); */
					?>
				</div>
        	</div>
        	<div class="form-group">
	        	<?= Html::label('โครงการ', 'RB_PROJ_LAUN_RES.RB_PROJ_LAUN_ID', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-8">
		            <?php
			        	$projLaunList = BaseArrayHelper::merge($firstOptionDDL, 
		        											BaseArrayHelper::map($rbProjLauns, 
		        																'RB_PROJ_LAUN_ID', 
			        															'rbProj.PROJ_NAME_TH'));
			        	
			        	echo Html::dropDownList('RB_PROJ_LAUN_RES.RB_PROJ_LAUN_ID',
			        								$rbProjLaunId,
			        								$projLaunList,
													[
														'id' => 'rbProjLaunList',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter();onProjLaunChange(this);'
													]); 
					?>
					<span class="loading">กำลังโหลดข้อมูล...</span>
				</div>
			</div>
			<div class="form-group">
	        	<?= Html::label('กิจกรรม', 'RB_PROJ_LAUN_RES.RB_SUB_PROJ_ID', ['class' => 'col-md-2 control-label']); ?>
	        	<div class="col-md-6">
		            <?php
		            	$subProjList = BaseArrayHelper::merge($firstOptionDDL, 
		        											BaseArrayHelper::map($rbSubProjs, 
		        																'RB_SUB_PROJ_ID', 
			        															'SUB_PROJ_NAME_TH'));

			        	echo Html::dropDownList('RB_PROJ_LAUN_RES.RB_SUB_PROJ_ID',
			        								$rbSubProjId,
			        								$subProjList,
													[
														'id' => 'rbSubProjList',
														'class' => 'form-control',
														'onchange' => 'Common.jqgrid.onFilter();'
													]); 
					?>
				</div>
        	</div>
    	</div>
	 </div>
<?= Html::endForm(); ?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['rbprojlaunres/gridview']);?>";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'RB_PROJ_LAUN_RES_ID'});

	function gotoAdd(obj) {
		var url = "<?=Url::to(['rbprojlaunres/add'])?>";
		url += '&id=' + $(obj).closest('tr[role="row"]').attr('id');
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		window.location = url;
	}

	function getFilterParam() {
		var $frmsearch = $('#frmsearch');
		var url = '';
		url += '&budget_year='+$frmsearch.find('[name="BUDGET_YEAR"]').val();
		url += '&month='+$frmsearch.find('[name="MONTH"]').val();
		url += '&province_code='+$frmsearch.find('[name="RB_PROJ_LAUN_RES.PROVINCE_CODE"]').val();
		url += '&amphoe_code='+$frmsearch.find('[name="RB_PROJ_LAUN_RES.AMPHOE_CODE"]').val();
		url += '&rb_proj_id=<?= $rbProjId; ?>';
		url += '&rb_proj_laun_id='+$frmsearch.find('[name="RB_PROJ_LAUN_RES.RB_PROJ_LAUN_ID"]').val();
		url += '&rb_sub_proj_id='+$frmsearch.find('[name="RB_PROJ_LAUN_RES.RB_SUB_PROJ_ID"]').val();
		
		return url;
	}

	function getUrlForAdd(){
		var url = "<?=Url::to(['rbprojlaunres/add'])?>";
		url += getFilterParam();
		url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
		
		return url;
	}

	function onChangeProvince(t){
	    var province_id=t.value;
	    var data = {province : province_id};
	    if(province_id!=''){
	        jQuery.post('<?=Url::to(["common/getddlampore"]);?>', data, function(data){
	            var jElm = jQuery('#frmsearch').find('select[name="RB_PROJ_LAUN_RES.AMPHOE_CODE"]');
	            jElm.find('option').remove();
	            jElm.append('<option value="">กรุณาเลือก</option>');
	            jQuery.each(data, function(i, row){
	                jElm.append('<option value="'+row.AMPHOE_CODE+'">'+row.AMPHOE_NAME_TH+'</option>');
	            });
	            jElm.change();
	        }, 'json');
	    }
	}

	function onProjLaunChange(obj) {
        var url = "<?=Url::to(['rbprojlaunres/ajax-get-sub-proj-list']);?>";
        var rbProjLaunId = $(obj).val();

        $('.loading').show();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            data:{rb_proj_laun_id:rbProjLaunId}
        })
        .done(function(data, textStatus, jqXHR) {
        	rederRbSubProjList(data);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            alert('เกิดข้อผิดพลาด');
        })
        .always(function(data, textStatus, jqxhr_error) {
            $('.loading').hide();
        });
    }

    function rederRbSubProjList(data) {
		var $rbSubProjList = $('#rbSubProjList');
		var html = '<option value="">กรุณาเลือก</option>';

		$.each(data, function(i, e) {
			html += '<option value="'+e.RB_SUB_PROJ_ID+'">'+e.SUB_PROJ_NAME_TH+'</option>';
		});

		$rbSubProjList.html(html);
    }

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: function() {
			Common.jqgrid.onFilter();
		},
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับที่', 
	   				'ชื่อกิจกรรม', 
	   				'จังหวัด', 
	   				'อำเภอ', 
	   				'สถานที่', 
	   				'หน่วยงาน', 
	   				'งบประมาณ', 
	   				'จำนวนผู้เข้าร่วม', 
	   				'ลักษณะ',
	   				'ปีงบประมาณ',
	   				'เดือน',
	   				'สถานะ',
	   				'แก้ไข'],
	   	colModel:[
	  	   		{name:'seq',index:'seq', width:60, sortable:false, editable: false, align:'center'},
		   		{name:'act_name_th',index:'ACT_NAME_TH', width:100, sortable:true, sorttype:'int', editable:false},
		   		{name:'province_code',index:'PROVINCE_CODE' ,width:100, sortable:true, editable:false},
		   		{name:'amphoe_code', index:'AMPHOE_CODE', width:100, sortable:true, editable:false},
		   		{name:'place', index:'PLACE', width:120, sortable:true, editable: false},
		   		{name:'org_respon_name',index:'ORG_RESPON_NAME', width:100, sortable:true, editable:false},
		   		{name:'budget',index:'BUDGET', width:80, sortable:true, editable: false},
		   		{name:'sum_people', index:'SUM_PEOPLE', width:80, sortable:true, sorttype:'int', editable: false},
		   		{name:'op_flag', index:'OP_FLAG', width:60, sortable:true, sorttype:'int', editable: false},
		   		{name:'budget_year', index:'BUDGET_YEAR', hidden:true},
		   		{name:'month', index:'MONTH', hidden:true},
		   		{name:'status', index:'STATUS', hidden:true},
		   		{name:'edit', index:'edit', width:60, editable:false, sortable:false, align:'center', formatter: function (cellvalue, options, rowObject) {
																								            return Common.jqgrid.getEditLink({
																								            									year: rowObject[9],
																								            									month: rowObject[10],
																								            									status: rowObject[11],
																								            									functionName: 'gotoAdd(this)'
																								            									});
																								        }},
	   	],
	   	onSelectRow: function(id){},
		onCellSelect: function(id, iCol, cellcontent){
			
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: "แสดงผลกำรดำเนินกำรประจำเดือน สนก.",
	   	rowList:[10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: function() {
	    	var frmsearch = jQuery('#frmsearch');
			var filterYear = frmsearch.find('#filterBudgetYear').val();
			var filterMonth = frmsearch.find('#filterMonth').val();

	    	if (Common.utils.isCurrentMonth(filterYear, filterMonth, true)) {
	    		$('.panel-add-button').show();
	    		$('#del_rowed5').show();
	    	} else {
	    		$('.panel-add-button').hide();
	    		$('#del_rowed5').hide();
	    	}
	    },
	   	rowNum:rtparams.rows,
	   	sortname: rtparams.sidx,
	    sortorder: rtparams.sord,
	    page: rtparams.page,
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
	}).navButtonAdd('#pagered', Common.jqgrid.getAddButton(getUrlForAdd));

	$(function() {
		$('.loading').hide();
	});
</script>