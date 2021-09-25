<template>
    <div>
        <van-nav-bar
            title="首页"
        />
        <van-cell center title="标题">
            <template #right-icon>
                <van-switch v-model="checked" size="24" />
            </template>
        </van-cell>
    </div>
</template>

<script>
export default {
    data() {
        return {
            checked: true,
        }
    },
}
</script>
<style>
.my-swipe .van-swipe-item {
    color: #fff;
    font-size: 20px;
    line-height: 150px;
    text-align: center;
    background-color: #39a9ed;
}
</style>
