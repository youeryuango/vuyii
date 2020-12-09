<template>
    <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
                            <el-form-item label="ID" prop="id">
                    <el-input v-model="form.id"></el-input>
                </el-form-item>,
                <el-form-item label="标题" prop="title">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>,
                <el-form-item label="简介" prop="desc">
                    <el-input v-model="form.desc"></el-input>
                </el-form-item>,
                <el-form-item label="关键词" prop="keywords">
                    <el-input v-model="form.keywords"></el-input>
                </el-form-item>,
                <el-form-item label="内容" prop="content">
                    <el-input v-model="form.content"></el-input>
                </el-form-item>,
                <el-form-item label="选择类型编号">
                    <el-select placeholder="请选择类型编号" clearable v-model="form.category_id">
                        <el-option></el-option>
                    </el-select>
                </el-form-item>,
                <el-form-item label="选择状态 0禁用 1启用">
                    <el-select placeholder="请选择状态 0禁用 1启用" clearable v-model="form.status">
                        <el-option></el-option>
                    </el-select>
                </el-form-item>,
                <el-form-item label="基础阅读量" prop="base_view_num">
                    <el-input v-model="form.base_view_num"></el-input>
                </el-form-item>,
                <el-form-item label="实际阅读量" prop="actual_view_num">
                    <el-input v-model="form.actual_view_num"></el-input>
                </el-form-item>,
                <el-form-item label="选择创建人编号">
                    <el-select placeholder="请选择创建人编号" clearable v-model="form.user_id">
                        <el-option></el-option>
                    </el-select>
                </el-form-item>,
                <el-form-item label="选择创建时间">
                    <el-date-picker
                            v-model="form.create_time"
                            value=""
                            type="date"
                            placeholder="选择日期">
                    </el-date-picker>
                </el-form-item>
            <el-form-item>
                <el-button @click="changeParentDialog">取 消</el-button>
                <el-button type="primary" @click="submitForm" :disabled="ifDisabled">确 定</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import * as Validate from '../../utils/validate'
    export default {
        name: 'Form',
        data(){
            return {
                dialogFormVisible: false,
                ifDisabled: false,
                form: {
                    id: '',
                    title: '',
                    desc: '',
                    keywords: '',
                    content: '',
                    category_id: '',
                    status: '',
                    base_view_num: '',
                    actual_view_num: '',
                    user_id: '',
                    create_time: ''
                },
                rules:{
                    // account:[
                    //     { required: true, message: '请输入登录账户', trigger: 'blur' },
                    //     { min: 6, max: 12, message: '长度在 6 到 20 个字符', trigger: 'blur' }
                    // ],
                    // username:[
                    //     { required: true, message: '请输入用户名称', trigger: 'blur' },
                    // ],
                    // email:[
                    //     { required: true, message: '请输入用户邮箱', trigger: 'blur' },
                    //     { validator: Validate.validateEMail}
                    // ]
                }
            }
        },
        methods:{
            submitForm(){
                this.ifDisabled = true;
                this.$refs.form.validate(valid => {
                    if (!valid) return;
                    this.$emit('provideChildFormData', this.form)
                })
            },
            changeParentDialog(){
                this.$emit('changeParentDialog');
            },
            resetForm(){
                this.$refs.form.resetFields();
            },
            handleAvatarSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>