import { createRouter, createWebHistory } from "vue-router";

import Bible from "../views/Bible.vue";
import Commentaries from "../views/Commentaries.vue";
import Books from "../views/Books.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      component: Bible,
    },
    {
      path: "/commentaries",
      component: Commentaries,
    },
    // {
    //   path: '/books',
    //   component: Books
    // }
  ],
});

export default router;
