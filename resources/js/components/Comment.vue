<script src="../../../../maka/platV5-cms/src/api/request.js"></script>
<template>
    <div>
        <van-nav-bar
            title="评论"
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
import {Toast} from 'vant';
import {getTest} from "../utils/api";

export default {
    data() {
        return {
            activeNames: ['1'],
        };
    },
    methods: {
        async onClickLeft() {
            // const {
            //     data: {
            //         test,
            //     }
            // } = await getTest();
            getTest().then(f=>{

            })

            // console.log(test)
        },
        onClickRight() {
            Toast('按钮2');
        },
    },
};
</script>
