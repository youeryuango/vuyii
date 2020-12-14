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
                    <el-menu-item index="1">用户中心</el-menu-item>
                    <el-menu-item index="4">权限管理</el-menu-item>
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
                                unique-opened>
                            <template v-for="item in menuList">
                                <template v-if="item.child !== undefined">
                                    <el-submenu :index="item.path" :key="item.id">
                                        <template slot="title">
                                            <i :class="item.icon"></i>
                                            <span>{{item.title}}</span>
                                        </template>
                                        <el-menu-item :index="subItem.path" v-for="subItem in item.child" :key="subItem.id">{{subItem.title}}</el-menu-item>
                                    </el-submenu>
                                </template>
                                <template v-else>
                                    <el-menu-item :index="item.path" :key="item.id">
                                        <i :class="item.icon"></i>
                                        <span slot="title">{{item.title}}</span>
                                    </el-menu-item>
                                </template>
                            </template>
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
                        'icon': 'el-icon-user',
                        'child': [
                            {'id': 2, 'title': '用户列表', 'path': '/user-list'}
                        ]
                    },
                    {
                        'id': 3,
                        'title': '内容管理',
                        'path': '/posts',
                        'icon': 'el-icon-document',
                        'child': [
                            {'id': 4, 'title': '内容列表', 'path': '/post-list'}
                        ]
                    },
                    {
                        'id': 4,
                        'title': '分类管理',
                        'path': '/category-list',
                        'icon': 'el-icon-more-outline\n',
                    },
                    {
                        'id': 6,
                        'title': '权限管理',
                        'path': '/sys-role-list',
                        'icon': 'el-icon-more-outline\n',
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