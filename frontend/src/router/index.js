import { createRouter, createWebHistory, VueRouter } from 'vue-router'
import Signup from '../views/Signup.vue'
import Login from '../views/Login.vue'
import NotFound from '../views/NotFound.vue'
import Home from '../views/Home.vue'
import Profile from '../views/Profile.vue'
import Admin from '../views/Admin.vue'


const routes = [
  {
    path: '/',
    name: 'signup',
    component: Signup
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/home',
    name: 'home',
    component: Home
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin
  },
  {
    path: '/:catchAll(.*)',
    name: 'notFound',
    component: NotFound
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
