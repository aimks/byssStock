<template>
    <div>
        <router-view style="margin-bottom: 50px;"></router-view>
        <van-tabbar route>
            <van-tabbar-item replace to="/" icon="home-o">首页</van-tabbar-item>
            <van-tabbar-item replace to="/article" icon="label-o">早参</van-tabbar-item>
            <van-tabbar-item replace to="/comment" icon="comment-o">评论</van-tabbar-item>
            <van-tabbar-item replace to="/user" icon="friends-o" badge="新">我的</van-tabbar-item>
        </van-tabbar>
    </div>
</template>
<script>
import Vue from 'vue';
import { Tabbar, TabbarItem } from 'vant';
Vue.use(Tabbar);
Vue.use(TabbarItem);
export default {
    data(){
        return {}
    },
    components: {},
    computed: {},
    methods: {},
    mounted() {},
}
</script>
