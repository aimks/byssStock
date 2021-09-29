<template>
    <div>
        <van-divider content-position="left">资产曲线图</van-divider>
        <div id="assetsChart" style="height:400px;background-color: #ffffff;"></div>
        <van-divider content-position="left"></van-divider>
        <van-tabs>
            <van-tab title="当前持仓">
                <van-list
                    v-model="holdings.loading"
                    :finished="holdings.finished"
                    @load="onLoadHoldings"
                >
                    <van-cell
                        v-for="item in holdings.list"
                        :key="item.id"
                        :title="item.name + ' : ' + item.code"
                        :value="item.buy_at"
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
            </van-tab>
            <van-tab title="操作记录">
                <van-list
                    v-model="records.loading"
                    :finished="records.finished"
                    @load="onLoadRecords"
                >
                    <van-cell
                        v-for="item in records.list"
                        :key="item.id"
                        :title="item.name + ' : ' + item.code"
                        :value="item.operate_at"
                    >
                        <template #label>
                            <van-row>
                                <van-col span="8" style="height: 36px;line-height: 36px;">
                                    <van-tag v-if="item.type=='buy'" type="success">买入</van-tag>
                                    <van-tag v-else type="warning">卖出</van-tag>
                                </van-col>
                                <van-col span="8">价格<br>{{item.close_price}}</van-col>
                                <van-col span="8">数量<br>{{item.amount}}</van-col>
                            </van-row>
                        </template>
                    </van-cell>
                </van-list>
                <van-pagination
                    v-model="records.search.page"
                    :total-items="records.total"
                    :items-per-page="records.search.pageSize"
                    @change="onLoadRecords"
                />
            </van-tab>
            <van-tab title="收益排行">
                <van-list
                    v-model="profits.loading"
                    :finished="profits.finished"
                    @load="onLoadProfits"
                >
                    <van-cell
                        v-for="item in profits.list"
                        :key="item.id"
                        :title="item.name + ' : ' + item.code"
                    >
                        <template #label>
                            <van-row>
                                <van-col span="12">买入时间<br>{{item.buy_at}}</van-col>
                                <van-col span="12">卖出时间<br>{{item.sell_at}}</van-col>
                            </van-row>
                        </template>
                        <template #default>
                            <van-tag v-if="item.profit > 0" type="success">{{item.profit}}</van-tag>
                            <van-tag v-else type="danger">{{item.profit}}</van-tag>
                            <van-row style="font-size: 12px;line-height: 18px;text-align:left;margin-top: 4px;">
                                <van-col span="8">买入价<br>{{item.buy_price}}</van-col>
                                <van-col span="8">卖出价<br>{{item.sell_price}}</van-col>
                                <van-col span="8">数量<br>{{item.amount}}</van-col>
                            </van-row>
                        </template>
                    </van-cell>
                </van-list>
                <van-pagination
                    v-model="profits.search.page"
                    :total-items="profits.total"
                    :items-per-page="profits.search.pageSize"
                    @change="onLoadProfits"
                />
            </van-tab>
        </van-tabs>
    </div>
</template>

<script>
import * as echarts from 'echarts';
import {getHoldings,getAssetsChart,getRecords,getProfits} from "../utils/api";
export default {
    data() {
        return {
            holdings: {
                list: [],
                loading: false,
                finished: false,
            },
            records: {
                total: 0,
                list: [],
                search: {
                    page: 0,
                    pageSize: 10,
                },
                loading: false,
                finished: false,
            },
            profits: {
                total: 0,
                list: [],
                search: {
                    page: 0,
                    pageSize: 10,
                },
                loading: false,
                finished: false,
            },
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
            this.holdings.loading = true;
            const {
                data: {
                    list
                }
            } = await getHoldings();
            this.holdings.list = list
            this.holdings.loading = false;
            this.holdings.finished = true;
        },
        async onLoadRecords() {
            this.holdings.loading  = true;
            const {
                data: {
                    total,
                    list,
                }
            } = await getRecords(this.records.search);
            this.records.total = total
            this.records.list = list
            this.records.loading = false;
            this.records.finished = true;
        },
        async onLoadProfits() {
            this.profits.loading = true;
            const {
                data: {
                    total,
                    list,
                }
            } = await getProfits(this.profits.search);
            this.profits.total = total
            this.profits.list = list
            this.profits.loading = false;
            this.profits.finished = true;
        },
    },
}
</script>
