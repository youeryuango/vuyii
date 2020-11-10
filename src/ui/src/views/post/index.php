<template>
    <div>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: 'home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>用户管理</el-breadcrumb-item>
            <el-breadcrumb-item>用户列表</el-breadcrumb-item>
        </el-breadcrumb>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <el-row :gutter="20">
                    <el-form ref="filerForm"  label-width="80px" :inline="true">
                                        <el-form-item prop="title">
                    <el-input v-model="selectForm.title"></el-input>
                </el-form-item>
                    
                        <el-form-item label="选择status">
                            <el-select v-model="value" placeholder="请选择status" :value="value">
                                <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    
                        <el-form-item label="选择create_time">
                          <div class="block">
                            <span class="demonstration">默认</span>
                            <el-date-picker
                              v-model="value1"
                              type="date"
                              placeholder="选择日期">
                            </el-date-picker>
                          </div>
                        </el-form-item>
                    
                        <el-form-item label="选择user_id">
                            <el-select v-model="value" placeholder="请选择user_id" :value="value">
                                <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    
			
                        <el-form-item label="选择主题">
                            <el-select v-model="value" placeholder="请选择主题" :value="value">
                                <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-form>
                </el-row>
                <el-row>
                    <el-button type="primary" icon="el-icon-search" size="mini">搜索</el-button>
                    <el-button type="primary" icon="el-icon-circle-plus-outline" size="mini" @click="dialogFormVisible = true">新增</el-button>
                </el-row>
                <el-dialog  :visible.sync="dialogFormVisible">
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="姓名">
                            <el-input v-model="form.username"></el-input>
                        </el-form-item>
                        <el-form-item label="密码">
                            <el-input type="password" v-model="form.password"></el-input>
                        </el-form-item>
                        <el-form-item label="性别">
                            <el-radio-group v-model="form.gender">
                                <el-radio label="男"></el-radio>
                                <el-radio label="女"></el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="头像">
                            <el-upload
                                class="avatar-uploader"
                                action="https://jsonplaceholder.typicode.com/posts/"
                                :show-file-list="false"
                                :on-success="handleAvatarSuccess"
                                :before-upload="beforeAvatarUpload">
                                <img v-if="imageUrl" :src="imageUrl" class="user-avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                    </el-form>
                    <div slot="footer" class="dialog-footer">
                        <el-button @click="dialogFormVisible = false">取 消</el-button>
                        <el-button type="primary" @click="onSubmit">确 定</el-button>
                    </div>
                </el-dialog>
            </div>
            <el-table
                :data="tableData"
                stripe
                style="width: 100%"
                :default-sort = "{prop: 'id', order: 'descending'}">

                                <el-table-column
                    sortable
                    align="center"
                    prop="id"
                    label=""
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="title"
                    label="标题"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="desc"
                    label="简介"
                    width="180">
                </el-table-column>,
                <el-table-column
                        align="center"
                        prop="status"
                        label="状态">
                        <template  slot-scope="scope">
                            <el-switch
                                @change="changeStatus(scope.row)"
                                v-model="scope.row.status"
                                active-color="#13ce66"
                                inactive-color="#ff4949">
                            </el-switch>
                        </template>
                    </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="content"
                    label="内容"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="create_time"
                    label="创建时间"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="user_id"
                    label="创建人"
                    width="180">
                </el-table-column>
                <el-table-column
                    align="center"
                    label="操作">
                    <template slot-scope="scope">
                        <el-row>
                            <el-tooltip class="item" effect="dark" content="修改" placement="top">
                                <el-button type="primary" icon="el-icon-edit" size="small" @click="update(scope.row)"></el-button>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                <el-button type="danger" icon="el-icon-delete" size="small" @click="delete(scope.row)"></el-button>
                            </el-tooltip>
                        </el-row>
                    </template>
                </el-table-column>
            </el-table>
            <div class="block">
                <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="1"
                    :page-sizes="[100, 200, 300, 400]"
                    :page-size="100"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="400">
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
    export default {
        name: "List",
        data(){
            return {
                options: [{
                    value: '选项1',
                    label: 'a'
                }, {
                    value: '选项2',
                    label: 'b'
                }, {
                    value: '选项3',
                    label: 'c'
                }, {
                    value: '选项4',
                    label: 'd'
                }, {
                    value: '选项5',
                    label: 'e'
                }],
                value: '',
                tableData: [
                    {'id': '3', 'avatar': 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1604659215655&di=f70a6d161d8f3fe643a6512f4ae59399&imgtype=0&src=http%3A%2F%2Ffjmingfeng.com%2Fimg%2F1%2F9549023851%2F51%2F349e611701cd0726964adf0082b600d2%2F9050751951%2F4075712993.jpg', 'name': '张文杰', 'gender': '男', 'status': true},
                    {'id': '4', 'avatar': 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1604659215655&di=f70a6d161d8f3fe643a6512f4ae59399&imgtype=0&src=http%3A%2F%2Ffjmingfeng.com%2Fimg%2F1%2F9549023851%2F51%2F349e611701cd0726964adf0082b600d2%2F9050751951%2F4075712993.jpg', 'name': '李二', 'gender': '女', 'status': true},
                    {'id': '5', 'avatar': 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1604659215655&di=f70a6d161d8f3fe643a6512f4ae59399&imgtype=0&src=http%3A%2F%2Ffjmingfeng.com%2Fimg%2F1%2F9549023851%2F51%2F349e611701cd0726964adf0082b600d2%2F9050751951%2F4075712993.jpg', 'name': '王五', 'gender': '男', 'status': false},
                    {'id': '6', 'avatar': 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1604659215655&di=f70a6d161d8f3fe643a6512f4ae59399&imgtype=0&src=http%3A%2F%2Ffjmingfeng.com%2Fimg%2F1%2F9549023851%2F51%2F349e611701cd0726964adf0082b600d2%2F9050751951%2F4075712993.jpg', 'name': '小静', 'gender': '女', 'status': false},
                    {'id': '7', 'avatar': 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1604659215655&di=f70a6d161d8f3fe643a6512f4ae59399&imgtype=0&src=http%3A%2F%2Ffjmingfeng.com%2Fimg%2F1%2F9549023851%2F51%2F349e611701cd0726964adf0082b600d2%2F9050751951%2F4075712993.jpg', 'name': '赵四', 'gender': '男', 'status': true},
                ],

                dialogTableVisible: false,
                dialogFormVisible: false,
                form: {
                    username: '',
                    password: '',
                    avatar: '',
                    gender: '',
                },
                formLabelWidth: '120px',
                imageUrl: ''
            }
        },
        methods:{
            changeStatus(obj){
                console.log(obj.status)
                this.$message.success('操作成功！');
            },
            update(obj){
                console.log(obj)
            },
            handleSizeChange()
            {

            },
            handleCurrentChange()
            {

            },
            onSubmit()
            {

            },
            handleAvatarSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            }
        }
    }
</script>

<style scoped lang="less">
</style>
