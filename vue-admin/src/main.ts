import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

//axios.defaults.baseURL = 'http://localhost:8006/api/admin/'
// storing the jwt token into cookie
axios.defaults.withCredentials = true

// manual storing the token to localstorage
//axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`

//axios.defaults.headers['Access-Control-Allow-Headers'] = 'X-CSRF-TOKEN, Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length';
// axios.defaults.headers['Access-Control-Allow-Origin'] = '*';
// axios.defaults.headers['Content-type'] = 'application/json'
// axios.defaults.headers['Access-Control-Allow-Methods'] = 'GET, POST, PATCH, PUT, DELETE, OPTIONS';

createApp(App).use(store).use(router).mount('#app')
