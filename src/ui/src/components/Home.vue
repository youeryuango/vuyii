<template>
    <div class="g-container">
<!--        <el-button type="info" @click="logout">logout</el-button>-->
        <el-container>
            <el-header>
                <el-menu
                        class="el-menu-demo"
                        mode="horizontal"
                        background-color="#545c64"
                        text-color="#fff"
                        active-text-color="#ffd04b">
                    <el-submenu index="2">
                        <template slot="title">我的工作台</template>
                        <el-menu-item index="2-1">选项1</el-menu-item>
                        <el-menu-item index="2-2">选项2</el-menu-item>
                        <el-menu-item index="2-3">选项3</el-menu-item>
                        <el-submenu index="2-4">
                            <template slot="title">选项4</template>
                            <el-menu-item index="2-4-1">选项1</el-menu-item>
                            <el-menu-item index="2-4-2">选项2</el-menu-item>
                            <el-menu-item index="2-4-3">选项3</el-menu-item>
                        </el-submenu>
                    </el-submenu>
                    <el-menu-item index="3" disabled>消息中心</el-menu-item>
                    <el-menu-item index="4"><a href="https://www.ele.me" target="_blank">订单管理</a></el-menu-item>
                </el-menu>
            </el-header>
            <el-container>
                <el-aside :span="20">
                    <el-col>
                        <el-menu
                                default-active="2"
                                class="el-menu-vertical-demo"
                                background-color="#545c64"
                                text-color="#fff"
                                active-text-color="#ffd04b"
                                router
                                unique-opened
                        >
                            <el-submenu :index="item.path" v-for="item in menuList" :key="item.id">
                                <template slot="title">
                                    <i class="el-icon-location"></i>
                                    <span>{{item.title}}</span>
                                </template>
                                <el-menu-item :index="subItem.path" v-for="subItem in item.child" :key="subItem.id">{{subItem.title}}</el-menu-item>
                            </el-submenu>
                        </el-menu>
                    </el-col>
                </el-aside>
                <el-main>
                    <router-view></router-view>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    export default {
        name: "Home",
        data(){
            return {
                menuList: [
                    {
                        'id': 1,
                        'title': '用户管理',
                        'path': '/users',
                        'child': [
                            {'id': 2, 'title': '用户列表', 'path': '/user-list'}
                        ]
                    },
                    {
                        'id': 3,
                        'title': '内容管理',
                        'path': '/posts',
                        'child': [
                            {'id': 4, 'title': '内容列表', 'path': '/post-list'}
                        ]
                    },
                ]
            }
        },
        created(){
          this.getMenuList()
        },
        methods:{
            logout(){
                window.sessionStorage.clear();
                this.$router.push('/login');
            },
            async getMenuList(){
                const {data: res} = await this.$http.get('menus')
                this.menuList =  res.data
                console.log(res)
            }
        }
    }
</script>

<style lang="less" scoped>
</style>