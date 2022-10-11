import { createRouter, createWebHistory } from 'vue-router'

import Bible from '../views/Bible.vue'
import Commentaries from '../views/Commentaries.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/bible',
      component: Bible
    },
    {
      path: '/commentaries',
      component: Commentaries
    }
  ]
})

export default router
