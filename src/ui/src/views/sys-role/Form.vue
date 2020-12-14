<template>
    <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
                <el-form-item label="角色名称" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <el-input v-model="form.sort"></el-input>
                </el-form-item>
                <el-form-item label="备注" prop="tips">
                    <el-input v-model="form.tips"></el-input>
                </el-form-item>            <el-form-item>
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
                    name: '',
                    sort: '',
                    tips: ''
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