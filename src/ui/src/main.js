import Vue from 'vue'
import App from './App.vue'
import router from './router'
import './plugins/element.js'
import './assets/css/global.css'
import axios from 'axios'
import qs from 'qs'
Vue.prototype.$http = axios
// 配置请求根路径
axios.defaults.baseURL = '/api'
// 拦截器
axios.interceptors.request.use(config => {
  // 在每个请求的 header 中加入 token
  config.headers.Authorization = window.sessionStorage.getItem('token');
  if (config.method === "post"){
    config.data = qs.stringify(config.data);
    config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
  }
  return config;
})

axios.interceptors.response.use(
  response => {
    return response
  },
  error => {
    if (error.response) {
      switch (error.response.status) {
        case 401:
          // 返回 401 清除token信息并跳转到登录页面
          router.replace({
            path: '/login'
          })
          location.reload()
      }
    }
    return Promise.reject(error.response.data)   // 返回接口返回的错误信息
  })

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')