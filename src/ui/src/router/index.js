import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from "../components/Login"
import Home from "../components/Home"
import UserList from "../views/user/Index"
import UserForm from "../views/user/Form"
// import PostList from "../components/post/List"

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path:'/login',
    component: Login
  },
  {
    path:'/home',
    component: Home,
    redirect: '/user-list',
    children:[
      {
        path:'/user-list',
        component: UserList,
        children:[
          {
            path:'/user-create',
            component: UserForm,
          }
        ]
      },
      // {
      //   path:'/post-list',
      //   component: PostList,
      // },
    ]
  }
];

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  if (to.path === '/login') return next();
  const tokenStr = window.sessionStorage.getItem('token');
  if (!tokenStr) return next('/login');
  next();
});
export default router