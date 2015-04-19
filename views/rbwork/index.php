<?php
	use app\assets\AppAsset;
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;
    use yii\web\Utils;

	AppAsset::register($this);

// 	if (\Yii::$app->user->isGuest) {
// 		\Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
// 	}
?>
<?= Html::beginForm('', 'post', ['id' => 'frmsearch', 'class' => 'form-horizontal form-filtering']); ?>    
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-1 control-label">ประจำปี</label>
                <div class="col-md-3">
                    <select class="form-control filter-year">
                        <?php Utils::getOptionsYears($currentYear); ?>
                    </select>
                    <span class="loading">กำลังโหลดข้อมูล...</span>
                </div>
            </div>
        </div>
    </div>
<?= Html::endForm(); ?>
<div id="projListContainer" style="padding: 0 10px;">
</div>
<!-- ################## -->
<script type="text/javascript">
    $(function() {
        $('.loading').hide();

        renderProjectList(<?= $projList; ?>, '#projListContainer');

        $('.filter-year').change(function(e) {
            getRbProjLauns($(this).val());
        });
    });

    function getRbProjLauns(year) {
        var url = "<?=Url::to(['rbwork/ajax-get-proj-list']);?>";

        $('.loading').show();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            data:{year:year}
        })
        .done(function(data, textStatus, jqXHR) {
            renderProjectList(data, '#projListContainer');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            alert('เกิดข้อผิดพลาด');
        })
        .always(function(data, textStatus, jqxhr_error) {
            $('.loading').hide();
        });
    }

    function renderProjectList(data, container) {
        var html = '';

        $.each(data, function(i, e) {
            var rbProjLaunId = e.RB_PROJ_LAUN_ID;
            var rbProjId = e.RB_PROJ_ID;
            var budgetYear = e.BUDGET_YEAR;

            html += '<div class="panel panel-primary">';      
            html += '<div class="panel-heading">';
            html += '<h3 class="panel-title">'+e.RB_PROJ_NAME_TH+'</h3>';
            html += '</div>';
            html += '<div class="panel-body">';
            html += '<div class="form-group">'; 
            $.each(e.RB_SUB_PROJS, function(i, e) {
                html += '<label class="col-md-8">';
                html += '<a href="<?= Url::to(['rbprojlaunres/']); ?>'
                        +'&budget_year='+budgetYear
                        +'&rb_proj_id='+rbProjId
                        +'&rb_proj_laun_id='+rbProjLaunId
                        +'&rb_sub_proj_id='+e.RB_SUB_PROJ_ID+'">'
                        +e.SUB_PROJ_NAME_TH
                        +'</a>';
                html += '</label>';
            });
            html += '</div>';
            html += '</div>';
            html += '</div>'; 
        });
        
        $(container).html(html);                                                 
    }
</script>