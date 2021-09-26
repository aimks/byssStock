<template>
    <div>
        <van-divider content-position="left">功能 TodoList</van-divider>
        <van-cell-group>
            <van-cell title="1. 历史买卖列表查询" />
            <van-cell title="2. 历史持仓列表查询" />
            <van-cell title="3. 股票市值持仓占比饼图" />
            <van-cell title="4. 历史买卖股票收益排名" />
            <van-cell title="5. 收益率折线图" />
            <van-cell title="6. 收益历史最大回撤率" />
            <van-cell title="7. 早参数据采集和展示" />
            <van-cell title="8. 公众号文章采集和展示" />
            <van-cell title="9. 公众号评论采集和展示（个人认为评论比较精彩）" />
            <van-cell title="10. 更多功能敬请期待....." />
        </van-cell-group>
        <van-divider content-position="left">硬件 TodoList</van-divider>
        <van-cell-group>
            <van-cell title="1. 服务器购买" />
            <van-cell title="2. 域名购买" />
            <van-cell title="3. 域名备案（国外服务器不需要）" />
        </van-cell-group>
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
    },
};
</script>
