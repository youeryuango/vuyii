<?php

use common\gii\generators\crud\Generator;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
$model = $generator->modelClass;
$modelClass  = Inflector::camel2id(StringHelper::basename($model));
$formData = [];
$formStr  = '';
$formArgs = '';
$formArgsAry = [];
if(!empty($generator->formFields)){
    foreach ($generator->formFields as $formField) {
        $comment = (new $model)->getAttributeLabel($formField);
        if(in_array($formField, ['avatar', 'img', 'image', 'banner', 'photo', 'logo'])){
            $formData[] = <<<EOL
                <el-form-item label="{$comment}">
                    <el-upload
                            class="avatar-uploader"
                            action="https://jsonplaceholder.typicode.com/posts/"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="imageUrl" :src="form.{$formField}" class="user-avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
EOL;
        }elseif($generator->inputType[$formField] == Generator::TYPE_TEXT){
            $formData[] = <<<EOL
                <el-form-item label="{$comment}" prop="{$formField}">
                    <el-input v-model="form.{$formField}"></el-input>
                </el-form-item>
EOL;
        }elseif($generator->inputType[$formField] == Generator::TYPE_SELECT){
            $formData[] = <<<EOL
                <el-form-item label="选择{$comment}">
                    <el-select placeholder="请选择{$comment}" clearable v-model="form.{$formField}">
                        <el-option value=""></el-option>
                    </el-select>
                </el-form-item>
EOL;
        }elseif($generator->inputType[$formField] == Generator::TYPE_DATE){
            $formData[] = <<<EOL
                <el-form-item label="选择{$comment}">
                    <el-date-picker
                            v-model="form.{$formField}"
                            value=""
                            type="date"
                            placeholder="选择日期">
                    </el-date-picker>
                </el-form-item>
EOL;
        }

        if(empty($formArgsAry)) {
            $formArgsAry[] = $formField . ": ''";
        } else {
            $formArgsAry[] = '                    ' . $formField . ": ''";
        }
    }
    if(!empty($formData)) {
        $formStr = implode("\n", $formData);
    }
    if(!empty($formArgsAry)) {
        $formArgs = implode(",\n", $formArgsAry) . "\n";
    }
}
?>
<template>
    <div>
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <?=$formStr?>
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
                    <?=$formArgs?>
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