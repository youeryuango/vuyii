import Vue from 'vue'
import App from './App.vue'
import router from './router'
import './plugins/element.js'
import './assets/css/global.css'
import axios from 'axios'
Vue.prototype.$http = axios
// 配置请求根路径
axios.defaults.baseURL = ''
// 拦截器
axios.interceptors.request.use(config => {
  console.log(config)
  // 在每个请求的 header 中加入 token
  config.headers.Authorization = window.sessionStorage.getItem('token')
  return config
})
Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')