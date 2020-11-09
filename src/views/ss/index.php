<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\user\search\UserAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员账户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-index">
    <!-- 关闭Tab时顶部标题 -->
    <div class="layui-body-header">
        <span class="layui-body-header-title"><?= Html::encode($this->title) ?></span>
        <span class="layui-breadcrumb pull-right">
            <a href="<?= Url::to(['/site/home']) ?>">首页</a>
            <a><cite><?= Html::encode($this->title) ?></cite></a>
        </span>
    </div>

    <!-- 正文开始 -->
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <div class="layui-form toolbar">
                    <div class="layui-form-item">
                        <?php  $form = ActiveForm::begin([
                            'options'=>['style'=>'display:inline;'],
                            'fieldConfig'=>[
                                'template' => '<div class="layui-inline">{label}<div class="layui-input-inline">{input}</div></div>',
                                'labelOptions' => ['class' => 'layui-form-label'],
                                'inputOptions' => ['class'=>'layui-input'],
                                'options'=>['tag'=>false]
                            ]
                        ])?>

                                                <?php  ActiveForm::end(); ?>
                        <hr class="layui-bg-gray">
                        <div class="layui-show">
                            <button class="layui-btn layui-btn-sm icon-btn" id="btnSearch" lay-submit lay-filter="LAY-user-admin-search">
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                            <button class="layui-btn layui-btn-sm icon-btn" id="btnAdd">
                                <i class="layui-icon">&#xe654;</i>添加
                            </button>
                        </div>
                    </div>
                </div>
                <table class="layui-table" id="user-admin-table" lay-filter="user-admin-table"></table>
            </div>
        </div>

    </div>

    <div class="hidden_value">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    </div>
</div>

<!-- 表格操作列 -->
<script type="text/html" id="user-admin-table-bar">
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
            elem: '#user-admin-table',
            url: "<?=Url::to(['/user-admin/index'])?>",
            method:'get',
            limit:20,
            where:{'init_data':'get-request'}, //用于判断是否为get的请求
            page: true,
            cellMinWidth: 100,
            cols: [[
                {type: 'checkbox', fixed: 'left'},
                    {field:'id', align: 'center', title:'', sort: true},
                    {field:'username', align: 'center', title:'用户名', sort: true},
                    {field:'auth_key', align: 'center', title:'授权 key', sort: true},
                    {field:'verification_token', align: 'center', title:'', sort: true},
                    {field:'password_hash', align: 'center', title:'密码 Hash', sort: true},
                    {field:'email', align: 'center', title:'邮箱', sort: true},
                    {field:'status', align: 'center', title:'状态 0禁用 1启用', sort: true},
                    {field:'create_time', align: 'center', title:'创建时间', sort: true},
                    {fixed: 'right',align: 'center', toolbar: '#user-admin-table-bar', title: '操作', minWidth: 150}
            ]]
        });


        //监听搜索
        form.on('submit(LAY-user-admin-search)',function(data){
            var field = data.field;
            //执行重载
            table.reload('user-admin-table', {
                where: field
            });
        });

        //监听工具条
        table.on('tool(user-admin-table)', function (obj) {
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
                var url = "<?=Url::to(['/user-admin/delete?id='])?>"+table_data.id;
                admin.req(url,{'_csrf-src':_csrf},function (data) {
                    if(data.code == 0){
                        layer.msg("删除成功！", {icon: 1});
                    }else{
                        layer.msg(data.msg, {icon: 2});
                    }
                },'post');

                table.reload('user-admin-table');

            });

        }

        //修改and添加
        function showEditModel(data) {
            admin.putTempData('t-user-admin-form-ok', false);

            top.layui.admin.open({
                type: 2,
                title: data ? '修改管理员账户' : '添加管理员账户',
                maxmin: true,
                resize: true,
                area: ['800px', '600px'],
                content: data ? "<?=Url::to(['/user-admin/update?id='])?>"+data.id : "<?=Url::to(['/user-admin/create'])?>",
                end: function () {
                    admin.getTempData('t-user-admin-form-ok') && table.reload('user-admin-table');  // 成功刷新表格
                }
            });
        }

        function viewModel(data) {
            top.layui.admin.open({
                type:2,
                title:'查看',
                content:"<?=Url::to(['/user-admin/view?id='])?>"+data.id,
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

