import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: () => import('@/views/Home.vue')
  },
  {
    path: '/organizer/:organizerSlug/event/:eventSlug',
    component: () => import('@/views/View.vue')
  },
  {
    path: '/organizer/:organizerSlug/event/:eventSlug/registration',
    component: () => import('@/views/Register.vue')
  },
  {
    path: '/login',
    component: () => import('@/views/Login.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
