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
                    <el-form class="filer-form" ref="filerForm"  label-width="130px" label-position="left" :inline="true">
                                        <el-form-item prop="username" label="填写 Username">
                    <el-input v-model="selectArgs.username"></el-input>
                </el-form-item>
                    
                        <el-form-item label="选择Status">
                            <el-select  placeholder="请选择Status" v-model="selectArgs.status">
                                <el-option  v-for="item in statusMap"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value"
                                >
                                </el-option>
                            </el-select>
                        </el-form-item>
                    
                        <el-form-item label="选择CreateTime">
                            <el-date-picker
                                    v-model="selectArgs.createTime"
                                    value=""
                                    type="date"
                                    placeholder="选择日期">
                            </el-date-picker>
                        </el-form-item>
                    </el-form>
                </el-row>
                <el-row>
                    <el-button type="primary" icon="el-icon-search" size="mini" @click="handleSearch">搜索</el-button>
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
                    prop="username"
                    label="用户名"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="auth_key"
                    label="授权 key"
                    width="180">
                </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="email"
                    label="邮箱"
                    width="180">
                </el-table-column>,
                <el-table-column
                        align="center"
                        prop="status"
                        label="状态 0禁用 1启用">
                        <template  slot-scope="scope">
                            <el-switch
                                @change="changeStatus(scope.row)"
                                v-model="scope.row.status"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                                active-value="1"
                                inactive-value="0">
                            </el-switch>
                        </template>
                    </el-table-column>,
                <el-table-column
                    sortable
                    align="center"
                    prop="create_time"
                    label="创建时间"
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
                                <el-button type="danger" icon="el-icon-delete" size="small" @click="del(scope.row)"></el-button>
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
    export default {
        name: "List",
      created(){
          this.requestData();
      },
        data(){
            return {
                selectArgs:{
                  username:   '',
                  create_time: '',
                  status:     null,
                },
                statusMap: [{value: this.$global.STATUS_FALSE, label: '禁用'}, {value: this.$global.STATUS_TRUE, label: '启用'}],
                tableData: [],
                totalCount: 0,
                pageArgs:{
                  pageSize: 20,
                  currPage: 1,
                },
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
          /**
           * 请求列表数据
           **/
            async requestData(){
                let condition = {
                  params:{
                    limit: this.pageArgs.pageSize,
                    page: this.pageArgs.currPage,
                  }
                };
                let resp = await this.$http.get('/user-admin/index', condition);
                if (resp.data.code !== this.$global.SUCCESS_CODE) return this.$message.error(resp.data.msg);
                this.tableData  = resp.data.data.list;
                this.totalCount =  resp.data.data.count;
            },
          /**
           * 更改记录状态
           **/
          async changeStatus(obj){
            let condition = {
                status: obj.status ? this.$global.STATUS_FALSE : this.$global.STATUS_TRUE,
            };
            let resp = await this.$http.put('/user-admin/update?id=' + obj.id, condition);
            if (resp.data.code !== this.$global.SUCCESS_CODE) {
              this.$message.error(resp.data.msg);
            }else{
              this.$message.success('修改状态成功！');
            }
            return this.requestData();
            },
            update(obj){
                console.log(obj)
            },
          /**
           * 删除记录
           * */
           del(obj){
            this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {
              this.$http.delete('/user-admin/delete?id=' + obj.id).then(resp => {
                if (resp.data.code !== this.$global.SUCCESS_CODE) {
                  this.$message.error(resp.data.msg);
                }else{
                  this.$message.success('删除记录成功！');
                }
                return this.requestData();
              })
            }).catch(() => {
              this.$message({
                type: 'info',
                message: '已取消删除！'
              });
            });
          },
          /**
           * 监听分页条数变化
           * @param val
           */
            handleSizeChange(val)
            {
              this.pageArgs.pageSize = val;
              this.requestData();
            },
          /**
           * 监听当前页变化
           * @param val
           */
            handleCurrentChange(val)
            {
              this.pageArgs.currPage = val;
              this.requestData();
            },
          /**
           * 根据筛选条件搜索
           */
          async handleSearch(){
              let pageArgs = {
                limit: this.pageArgs.pageSize,
                page: this.pageArgs.currPage,
              };
              let paramsAssign = Object.assign(pageArgs, this.selectArgs);
            let condition = {
              params: paramsAssign
            };
            let resp = await this.$http.get('/user-admin/index', condition);
            if (resp.data.code !== this.$global.SUCCESS_CODE) return this.$message.error(resp.data.msg);
            this.tableData  = resp.data.data.list;
            this.totalCount =  resp.data.data.count;
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
