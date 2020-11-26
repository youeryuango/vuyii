<template>
    <div class="login_container">
        <div class="login_box">
            <div class="login_title">
                <h2>后台管理系统</h2>
            </div>
            <el-form class="login_form" :model="LoginForm" :rules="rules" ref="loginForm">
                <el-form-item prop="username">
                    <el-input v-model="LoginForm.username" prefix-icon="el-icon-user"></el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input  v-model="LoginForm.password" type="password" prefix-icon="el-icon-star-on"></el-input>
                </el-form-item>
                <el-form-item class="btns">
                    <el-button type="primary" @click="submit">提交</el-button>
                    <el-button type="info" @click="resetForm">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data(){
            return {
                LoginForm:{
                    username:'',
                    password:''
                },
                rules:{
                    username:[
                        { required: true, message: '请输入用户名称', trigger: 'blur' },
                    ],
                    password:[
                        { required: true, message: '请输入用户密码', trigger: 'blur' },
                        { min: 6, max: 12, message: '长度在 6 到 12 个字符', trigger: 'blur' }
                    ],
                }
            }
        },
        methods: {
            resetForm(){
                this.$refs.loginForm.resetFields()
            },
            submit(){
                this.$refs.loginForm.validate(async valid => {
                    if (!valid) return;
                    const resp = await this.$http.post('/auth/login', this.LoginForm);
                    if (resp.data.code !== 10000) return this.$message.error(resp.data.msg);
                    this.$message.success('登录成功！');
                    window.sessionStorage.setItem('token', 'Bearer ' + resp.data.data.token);
                    this.$router.push('/home');
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    .login_container{
        background-color: #2b4b6b;
        height: 100%;
    }
    .login_box{
        height: 350px;
        width: 450px;
        background-color: #fff;
        border-radius: 5px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
    .login_title{
        color: #333;
        display: flex;
        justify-content: center;
        h2{
            font-weight: 200;
        }
    }
    .login_form{
        position: absolute;
        bottom: 15%;
        width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
    }
    .btns{
        display: flex;
        justify-content: flex-end;
    }
</style>