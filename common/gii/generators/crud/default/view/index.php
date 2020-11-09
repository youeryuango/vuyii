<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
$modelClass = Inflector::camel2id(StringHelper::basename($generator->modelClass));
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$index_arr[] = "{type: 'checkbox', fixed: 'left'}";
$tableSchema = $generator->getTableSchema();
foreach ($tableSchema->columns as $column){
    if(!empty($generator->listFields)){
        if(in_array($column->name,$generator->listFields)){
            $index_arr[]="                    {field:'".$column->name."', align: 'center', title:'".$column->comment."', sort: true}";
        }
    }else{
        $index_arr[]="                    {field:'".$column->name."', align: 'center', title:'".$column->comment."', sort: true}";
    }
}
$index_arr[] = "                    {fixed: 'right',align: 'center', toolbar: '#".Inflector::camel2id(StringHelper::basename($generator->modelClass))."-table-bar', title: '操作', minWidth: 150}";
$index_json = implode(",\n",$index_arr);
$primary_key = $generator->getPrimayKey()[0];
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString($generator->title.'列表') ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <!-- 关闭Tab时顶部标题 -->
    <div class="layui-body-header">
        <span class="layui-body-header-title"><?="<?="?> Html::encode($this->title) ?></span>
        <span class="layui-breadcrumb pull-right">
            <a href="<?="<?="?> Url::to(['/site/home']) ?>">首页</a>
            <a><cite><?="<?="?> Html::encode($this->title) ?></cite></a>
        </span>
    </div>

    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <div class="layui-form toolbar">
                    <div class="layui-form-item">
                        <?= "<?php " ?> $form = ActiveForm::begin([
                            'options'=>['style'=>'display:inline;'],
                            'fieldConfig'=>[
                                'template' => '<div class="layui-inline">{label}<div class="layui-input-inline">{input}</div></div>',
                                'labelOptions' => ['class' => 'layui-form-label'],
                                'inputOptions' => ['class'=>'layui-input'],
                                'options'=>['tag'=>false]
                            ]
                        ])?>

                        <?= $generator->generateSearchField();?>
                        <?= "<?php " ?> ActiveForm::end(); ?>
                        <hr class="layui-bg-gray">
                        <div class="layui-show">
                            <button class="layui-btn layui-btn-sm icon-btn" id="btnSearch" lay-submit lay-filter="LAY-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button class="layui-btn layui-btn-sm icon-btn" id="btnAdd">
                                <i class="layui-icon">&#xe654;</i>添加
                            </button>
                        </div>
                    </div>
                </div>
                <table class="layui-table" id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table" lay-filter="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table"></table>
            </div>
        </div>

    </div>

    <div class="hidden_value">
        <input name="_csrf" type="hidden" id="_csrf" value="<?="<?="?> Yii::$app->request->csrfToken ?>">
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table-bar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="details">查看</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['layer', 'form', 'table', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var table = layui.table;
        var admin = layui.admin;
        var laydate = layui.laydate;
        var _csrf = $('#_csrf').val();

        // 渲染表格
        table.render({
            elem: '#<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table',
            url: "<?="<?="?>Url::to(['/<?= $modelClass ?>/index'])?>",
            method:'get',
            limit:20,
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                <?=$index_json?>

            ]]
        });


        //监听搜索
        form.on('submit(LAY-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search)',function(data){
            var field = data.field;
            //执行重载
            table.reload('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table', {
                where: field
            });
        });

        //监听工具条
        table.on('tool(<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table)', function (obj) {
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值

            if (layEvent === 'edit') { //修改
                showEditModel(data)
            } else if (layEvent === 'del') { //删除
                delModel(data,obj);
            } else if (layEvent === 'details'){
                viewModel(data);
            }
        });


        // 添加按钮点击事件
        $('#btnAdd').click(function () {
            showEditModel();
        });

        function delModel(table_data,obj) {
            layer.confirm('真的要删除该记录吗？', function(index){
                obj.del(); //删除对应行（tr）的DOM结构
                layer.close(index);
                //向服务端发送删除指令
                var url = "<?="<?="?>Url::to(['/<?= $modelClass ?>/delete?id='])?>"+table_data.<?=$primary_key?>;
                admin.req(url,{'<?=Yii::$app->request->csrfParam?>':_csrf},function (data) {
                    if(data.code == 0){
                        layer.msg("删除成功！", {icon: 1});
                    }else{
                        layer.msg(data.msg, {icon: 2});
                    }
                },'post');

                table.reload('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table');

            });

        }

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok', false);

            top.layui.admin.open({
                type: 2,
                title: data ? '修改<?=$generator->title?>' : '添加<?=$generator->title?>',
                maxmin: true,
                resize: true,
                area: ['800px', '600px'],
                content: data ? "<?="<?="?>Url::to(['/<?= $modelClass ?>/update?id='])?>"+data.<?=$primary_key?> : "<?='<?=' ?>Url::to(['/<?= $modelClass ?>/create'])?>",
                end: function () {
                    admin.getTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok') && table.reload('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-table');  // 成功刷新表格
                }
            });
        }

        function viewModel(data) {
            top.layui.admin.open({
                type:2,
                title:'查看',
                content:"<?='<?=' ?>Url::to(['/<?= $modelClass ?>/view?id='])?>"+data.<?=$primary_key?>,
                maxmin: true,
                resize: true,
                area: ['800px', '600px'],
                btn: ['关闭']

            });
        }

        // 时间范围
        laydate.render({
            elem: '#edtDate',
            type: 'date',
            range: true,
            theme: 'molv'
        });

        $(document).keydown(function (e) {
            if (e.keyCode == 13) {
                $("#btnSearch").trigger("click");
            }
        });
    });
</script>

