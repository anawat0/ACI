<?php
	use app\assets\AppAsset;
	use himiklab\jqgrid\JqGridWidget;
	use yii\helpers\Url;
	use yii\helpers\Json;
	use yii\web\Utils;

	AppAsset::register($this);

	JqGridWidget::widget();
?>
<?= $this->render('/layouts/_add_button'); ?>
<table id="rowed5"></table>
<div id="pagered"></div>
<!-- ########################################################################################### -->
<script type="text/javascript">
	var gridurl_1 = "<?=Url::to(['sbprojlaun/gridview']);?>";// "/browallia/web/index.php?r=sbprojlaun/gridview";
	var rtparams = Common.jqgrid.getDefaultPostData('<?=$rtparams?>', {sidx: 'SB_PROJ_LAUN_ID'});
	var listSbProj = <?php echo $listSbProj; ?>;
	var listSbSubProj = null;

	function gotoSbProjLaunTarget(t){
		var sbProjLaunId = jQuery(t).closest('tr[role="row"]').attr('id');
		var url = '';

		// TODO: check edit mode
		if (!isNaN(sbProjLaunId)) {
			url += "<?=Url::to(['sbprojlauntarget/']);?>";
			url += '&sb_proj_laun_id=' + sbProjLaunId;
			url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
			
			window.location = url;
		}
		
	}

	function gotoSbProjLaunAct(t){
		var sbProjLaunId = jQuery(t).closest('tr[role="row"]').attr('id');
		var url = '';

		// TODO: check edit mode
		if (!isNaN(sbProjLaunId)) {
			url += "<?=Url::to(['sbprojlaunact/', 'sb_proj_laun_id' => '']);?>";
			url += '&sb_proj_laun_id=' + sbProjLaunId;
			url += '&rtparams='+Common.Base64.encode(JSON.stringify(Common.jqgrid.getPostData('#rowed5')));
			
			window.location = url;
		}
	}

	function listSbProjChange(e) {
		var $currentElement = $(e.target);
		var id = $currentElement.closest('tr').attr('id');
		var idElementSbSubProJId = id + '_' + 'sb_sub_proj';

		// in edit table
		// if ( !isNaN(id) ) {	
		// 	idElementSbSubProJId = id + '_' + idElementSbSubProJId;	
		// } 

		createListSbSubProj($currentElement.val(), idElementSbSubProJId);
	}

	// TODO: set value to XX_sb_sub_proj_id
	function listSbSubProjChange(e) {
		var $currentElement = $(e.target);
		var id = $currentElement.closest('tr').attr('id');
		var idElementSbSubProJId = id + '_' + 'sb_sub_proj_id';

		// in edit table
		// if ( !isNaN(id) ) {	
		// 	idElementSbSubProJId = id + '_' + idElementSbSubProJId;	
		// } 

		$('#' + idElementSbSubProJId).val($currentElement.val());
	}

	function createListSbSubProj(sbProjId, idContainer, select) {
		// Resovle url in ajax http://stackoverflow.com/questions/26685913/yii2-ajax-request-not-working
		var url = "<?=Url::to(['sbprojlaun/get-list-sb-sub-proj']);?>"; // '/browallia/web/index.php?r=sbprojlaun/get-list-sb-sub-proj';

		$('.loading').show();
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: { sb_proj_id:sbProjId }
		})
		.done(function(data) {
			var html = '<option value="">--- กรุณาเลือก ---</option>';

			$.each(data, function(key, value) {
				html += '<option value="' + value['SB_SUB_PROJ_ID'] + '">' + value['SUB_PROJ_NAME_TH'] + '</option>';
			});

			$('#' + idContainer).html(html);
			if ( select ) {
				$('#' + idContainer).val(select);
			}

			$('.loading').hide();
		});
	}

	jQuery("#rowed5").jqGrid({
		url: gridurl_1+"&oper=request",
		datatype: "json",
		height: 450,
		width: jQuery('div.content').width()-20,
	   	colNames:['ลำดับ', 
				   	'แผนงาน/โครงการหลัก *', 
				   	'รหัสแผนงาน/โครงการย่อย *', 
				   	'แผนงาน/โครงการย่อย *', 
				   	'ประจำปี *', 
				   	'ผู้รับผิดชอบ *', 
				   	'งบประมาณ *', 
				   	'สถานะ', 
				   	'กลุ่มเป้าหมาย', 
				   	'รูปแบบกิจกรรม',
				   	'Action'],
	   	colModel:[
	   		{name:'id', index:'SB_PROJ_LAUN_ID', width:50, align:'center', sorttype:"int", sortable:true, editable: false},
	   		{name:'sb_proj_id', index:'SB_PROJ_ID', width:200, sortable:true, editable: true, edittype:'select', editoptions:{width:"200", value:listSbProj, dataEvents:[{type:'change', fn:listSbProjChange}]}},
	   		{name:'sb_sub_proj_id', index:'SB_SUB_PROJ_ID', width:50, editable:true, hidden:true},
	   		{name:'sb_sub_proj', index:'SB_SUB_PROJ', width:200, sortable:true, editable: true, edittype:'select', editoptions:{width:"200", value:{'':'--- กรุณาเลือก ---'}, dataEvents:[{type:'change', fn:listSbSubProjChange}]}},
	   		{name:'budget_year', index:'BUDGET_YEAR', width:100, sortable:true,editable: true, edittype:"select", editoptions:{width:"100", value:<?= Json::encode(Utils::getArrYears(false)); ?>}},
	   		{name:'project_responsible', index:'PROJECT_RESPONSIBLE', width:100, sortable:true, editable: true, edittype:"select", editoptions:{width:"100", value:{1:'นาย ก.', 2:'นาย ข.'}}},
	   		{name:'budget', index:'BUDGET', width:150, sorttype:"int", sortable:true, editable: true, edittype:'text'},
	   		{name:'status', index:'STATUS', width:100, sortable:true, editable: true, align:'center', edittype:"checkbox",editoptions:{value:"ใช้งาน:ไม่ใช้งาน"}, formatter: Common.jqgrid.chkFormatter},
	   		{name:'sub', index:'sub', width:115, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoSbProjLaunTarget(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
	   		{name:'sub', index:'sub', width:115, editable:false, sortable:false, align:'center', formatter: function(){return '<span style="margin:auto;cursor:pointer;" onclick="gotoSbProjLaunAct(this);" class="ui-icon ui-icon-circle-zoomout"></span>'; }},
	   		{name:'action', index:'action', width:80, editable:false, sortable:false, align:'center', formatter:Common.jqgrid.getBtnAction},
	   	],
		onSelectRow: function(rowid, status, e){
		},
		onCellSelect: function(id, iCol, cellcontent){
		},
		multiselect: true,
		editurl: gridurl_1,
		caption: 'SB002 แผนงาน-โครงการ ที่กำลังดำเนินการ',
	   	rowNum: 10,
	   	rowList: [10,20,30],
	   	pager: '#pagered',
	    viewrecords: true,
	    scrollOffset: 3,
	    gridComplete: function(){
	    	var pager = jQuery(this).getGridParam("pager");

		    Common.jqgrid.setEditMode(pager, false);
		    jQuery(this).find(">tbody>tr.jqgrow:odd").addClass("myAltRowClassEven");
	        jQuery(this).find(">tbody>tr.jqgrow:even").addClass("myAltRowClassOdd");
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
		height: 300,
		width: 500,
		reloadAfterSubmit: true
	}, 
	Common.jqgridOptions.navGridDelete,
	{
		multipleSearch: true,
		multipleGroup: true
	});

	var myEditOptions = {
        aftersavefunc: Common.jqgrid.aftersavefunc
    };
	jQuery("#rowed5").jqGrid('inlineNav',"#pagered", 
	{
		edit:true, save:true, cancel:true,
		onbeforeeditfunc: function() {
	    },
	    onbeforeaddfunc: function() {
	    },
	    onafteraddfunc: function() {
	    },
	    addParams: {
			addRowParams: myEditOptions
	    },
	    editParams: myEditOptions
	});
</script>