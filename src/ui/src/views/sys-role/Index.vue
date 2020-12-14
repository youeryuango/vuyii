<template>
    <div>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: 'home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>用户管理</el-breadcrumb-item>
            <el-breadcrumb-item>用户列表</el-breadcrumb-item>
        </el-breadcrumb>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <el-row>
                    <el-form class="filer-form"
                             ref="filerForm"
                             label-width="130px"
                             label-position="left"
                             :inline="true">
                        <el-form-item prop="name" label="请输入角色名称">
                            <el-input v-model="selectArgs.name"></el-input>
                        </el-form-item>
                    
			
                    </el-form>
                </el-row>
                <el-row>
                    <el-button type="primary"
                               icon="el-icon-search"
                               size="mini"
                               @click="handleSearch">搜索
                    </el-button>
                    <el-button type="primary"
                               icon="el-icon-circle-plus-outline"
                               size="mini"
                               @click="dialogFormVisible = true">新增
                    </el-button>
                </el-row>
                <el-dialog :visible.sync="dialogFormVisible" show-close :before-close="closeDialog">
                    <Form ref="Form" @provideChildFormData="handleChildFormData" @changeParentDialog="closeDialog"></Form>
                </el-dialog>
            </div>
            <el-table
                    :data="tableData"
                    stripe
                    style="width: 100%"
                    :default-sort="{prop: 'id', order: 'descending'}"
                    v-loading="loading">

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
                    prop="name"
                    label="角色名称"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="sort"
                    label="排序"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="tips"
                    label="备注"
                    width="180">
                </el-table-column>
                <el-table-column
                        align="center"
                        label="操作">
                    <template slot-scope="scope">
                        <el-row>
                            <el-tooltip class="item" effect="dark" content="修改" placement="top">
                                <el-button type="primary"
                                           icon="el-icon-edit"
                                           size="small"
                                           @click="update(scope.row)">
                                </el-button>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                <el-button type="danger"
                                           icon="el-icon-delete"
                                           size="small"
                                           @click="del(scope.row)">
                                </el-button>
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
                        :page-sizes="[20, 60, 80, 100]"
                        :page-size="20"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="totalCount">
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
    import Form from './Form'
    export default {
        name: "List",
        components:{
            Form
        },
        created() {
            this.requestData()
        },
        data() {
            return {
                selectArgs: {
                    name: ''
                },
                loading: false,
                tableData: [],
                totalCount: 0,
                pageArgs: {
                    pageSize: 20,
                    currPage: 1
                },
                dialogFormVisible: false,
                FormData: {},
                preUpdateId: null,
            }
        },
        watch: {
            dialogFormVisible: {
                handler(newVal) {
                    this.$refs.Form.ifDisabled = false;
                    if (!newVal) this.preUpdateId = null;
                },
                deep: true
            }
        },
        methods: {
            /**
             * 请求列表数据
             **/
            async requestData() {
                this.loading = true
                let pageArgs = {
                    limit: this.pageArgs.pageSize,
                    page: this.pageArgs.currPage
                }
                let paramsAssign = Object.assign(pageArgs, this.selectArgs)
                let condition = {
                    params: paramsAssign
                }
                let resp = await this.$http.get('/sys-role/index', condition)
                if (resp.data.code !== this.$global.SUCCESS_CODE) return this.$message.error(resp.data.msg)
                this.tableData = resp.data.data.list
                this.totalCount = resp.data.data.count
                this.loading = false
            },
            /**
             * 更改记录状态
             **/
            async changeStatus(obj) {
                let condition = {
                    status: obj.status === this.$global.STATUS_TRUE ? this.$global.STATUS_TRUE : this.$global.STATUS_FALSE
                }
                let resp = await this.$http.put('/sys-role/update?id=' + obj.id, condition)
                if (resp.data.code !== this.$global.SUCCESS_CODE) {
                    this.$message.error(resp.data.msg)
                } else {
                    this.$message.success('修改状态成功！')
                }
                return this.requestData()
            },
            /**
             * 把记录值传递到子组件进行绑定
             */
            update(obj) {
                this.dialogFormVisible = true;
                this.preUpdateId = obj.id;
                this.$nextTick(() => {
                    this.$refs.Form.form = {
                        name: obj.name,
                    sort: obj.sort,
                    tips: obj.tips
                    };
                })
            },
            /**
             * 数据更新操作
             */
            async updateData() {
                if (this.preUpdateId === null) return;
                let resp = await this.$http.put('/sys-role/update?id=' + this.preUpdateId, this.FormData)
                if (resp.data.code !== this.$global.SUCCESS_CODE) {
                    this.$refs.Form.ifDisabled = false;
                    this.$message.error(resp.data.msg);
                } else {
                    this.$message.success('修改记录成功！');
                    this.closeDialog();
                }
                return this.requestData();
            },
            /**
             * 删除记录
             * */
            del(obj) {
                this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete('/sys-role/delete?id=' + obj.id).then(resp => {
                        if (resp.data.code !== this.$global.SUCCESS_CODE) {
                            this.$message.error(resp.data.msg)
                        } else {
                            this.$message.success('删除记录成功！')
                        }
                        return this.requestData()
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除！'
                    })
                })
            },
            /**
             * 创建一条新的用户记录
             */
            async createRecord() {
                let resp = await this.$http.post('/sys-role/create', this.FormData);
                if (resp.data.code !== this.$global.SUCCESS_CODE) {
                    this.$refs.Form.ifDisabled = false;
                    this.$message.error(resp.data.msg);
                } else {
                    this.$message.success('新增用户成功！');
                    this.closeDialog();
                }
                return this.requestData();
            },
            /**
             * 监听分页条数变化
             * @param val
             */
            handleSizeChange(val) {
                this.pageArgs.pageSize = val
                this.requestData();
            },
            /**
             * 监听当前页变化
             * @param val
             */
            handleCurrentChange(val) {
                this.pageArgs.currPage = val
                this.requestData();
            },
            /**
             * 根据筛选条件搜索
             */
            handleSearch() {
                this.requestData();
            },
            /**
             * 获取子组件的表单数据
             * @param formData
             */
            handleChildFormData(formData) {
                this.FormData = formData;
                if (this.preUpdateId === null) {
                    this.createRecord();
                } else {
                    this.updateData();
                }
            },
            /**
             * 关闭子组件表单弹框
             */
            closeDialog() {
                this.dialogFormVisible = false;
                this.$refs.Form.resetForm();
            }
        }
    }
</script>

<style scoped lang="less">
</style>
