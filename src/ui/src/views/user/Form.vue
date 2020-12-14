<template>
    <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="账户" prop="account">
                <el-input v-model="form.account"></el-input>
            </el-form-item>
            <el-form-item label="姓名" prop="username">
                <el-input v-model="form.username"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
                <el-input type="password" v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="角色" prop="roles">
                <el-select placeholder="请选择Status" clearable v-model="form.roles" multiple collapse-tags>
                    <el-option v-for="item in rolesMap"
                               :key="item.id"
                               :label="item.name"
                               :value="item.id">
                    </el-option>
                </el-select>
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
          account: '',
          username: '',
          password: '',
          email: '',
          roles: []
        },
        rolesMap: [],
        rules:{
          account:[
            { required: true, message: '请输入登录账户', trigger: 'blur' },
            { min: 6, max: 12, message: '长度在 6 到 20 个字符', trigger: 'blur' }
          ],
          username:[
            { required: true, message: '请输入用户名称', trigger: 'blur' },
          ],
          email:[
            { required: true, message: '请输入用户邮箱', trigger: 'blur' },
            { validator: Validate.validateEMail}
          ],
        }
      }
    },
    created(){
        this.renderRoles();
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
      /**
       * 渲染角色表单
       * @returns {Promise<ElMessageComponent>}
       */
      async renderRoles(){
        let resp = await this.$http.get('/sys-role/get-roles')
        if (resp.data.code !== this.$global.SUCCESS_CODE) return this.$message.error(resp.data.msg)
        this.rolesMap = resp.data.data.roles;
      }
    },
    mounted() {
    }
  }
</script>

<style scoped>

</style>