<template>
    <div>
        <van-nav-bar
            title="用户"
            left-text="返回"
            right-text="捐赠"
            left-arrow
            @click-left="onClickLeft"
            @click-right="onClickRight"
        />
        <van-collapse v-model="activeNames">
            <van-collapse-item title="标题1" name="1">内容</van-collapse-item>
            <van-collapse-item title="标题2" name="2">内容</van-collapse-item>
            <van-collapse-item title="标题3" name="3">内容</van-collapse-item>
        </van-collapse>
    </div>
</template>

<script>
export default {
    data() {
        return {
            activeNames: ['1'],
        };
    },
    methods: {
        onClickLeft() {
            this.$toast.success('返回');
            console.log($.version)
            axios.get('http://www.myxm.com/admin/login')
        },
        onClickRight() {
            this.$toast('按钮');
        },
    },
};
</script>
