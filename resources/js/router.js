import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './components/Home.vue';
import Article from './components/Article.vue';
import Comment from './components/Comment.vue';
import User from './components/User.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/article',
            name: 'article',
            component: Article
        },
        {
            path: '/comment',
            name: 'comment',
            component: Comment
        },
        {
            path: '/user',
            name: 'user',
            component: User
        },
    ]
});

export default router;
