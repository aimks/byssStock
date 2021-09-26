<template>
    <div>
        <van-divider content-position="left">资产曲线图</van-divider>
        <div id="assetsChart" style="height:400px;background-color: #ffffff;"></div>
        <van-divider content-position="left">当前持仓</van-divider>
        <van-list
            v-model="loading"
            :finished="finished"
            @load="onLoadHoldings"
        >
            <van-cell
                v-for="item in list"
                :key="item.id"
                :title="item.name + ' : ' + item.code"
                :value="item.buy_at.slice(0, 10)"
            >
                <template #label>
                    <van-row>
                        <van-col span="8">收盘价<br>{{item.close_price}}</van-col>
                        <van-col span="8">数量<br>{{item.amount}}</van-col>
                        <van-col span="8">市值<br>{{item.market_value}}</van-col>
                    </van-row>
                </template>
            </van-cell>
        </van-list>
    </div>
</template>

<script>
import * as echarts from 'echarts';
import {getHoldings,getAssetsChart} from "../utils/api";
export default {
    data() {
        return {
            list: [],
            loading: false,
            finished: false,
        }
    },
    mounted() {
        this.draw()
    },
    methods: {
        async draw() {
            const myChart = echarts.init(document.getElementById('assetsChart'));
            window.onresize = function() {
                myChart.resize();
            };
            // 指定图表的配置项和数据
            const {
                data: {
                    item
                }
            } = await getAssetsChart();
            const option = item;
            myChart.setOption(option);
        },
        async onLoadHoldings() {
            this.loading = true;
            const {
                data: {
                    list
                }
            } = await getHoldings();
            this.list = list
            this.finished = true;
        },
    },
}
</script>
