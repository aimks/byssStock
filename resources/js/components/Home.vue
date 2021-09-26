<template>
    <div>
        <van-divider content-position="left">资产曲线图</van-divider>
        <div id="main" style="height:400px;background-color: #ffffff;"></div>
        <van-divider content-position="left">当前持仓</van-divider>
        <van-cell title="服务器购买">
            <template #label>
                <van-notice-bar
                    left-icon="volume-o"
                    wrapable
                    :scrollable="false"
                    text="这里准备把书生老师的所有早参PDF文件存进来，并且做一个列表展示，在线预览。"
                />
            </template>
        </van-cell>
    </div>
</template>

<script>
import * as echarts from 'echarts';
export default {
    data() {
        return {
            checked: true,
        }
    },
    mounted() {
        this.draw()
    },
    methods: {
        draw() {
            const myChart = echarts.init(document.getElementById('main'));
            window.onresize = function() {
                myChart.resize();
            };
            // 指定图表的配置项和数据
            const option = {
                xAxis: {
                    data: ['2017-10-24', '2017-10-25', '2017-10-26', '2017-10-27']
                },
                yAxis: {},
                series: [
                    {
                        type: 'candlestick',
                        data: [
                            [20, 34, 10, 38],
                            [40, 35, 30, 50],
                            [31, 38, 33, 44],
                            [38, 15, 5, 42]
                        ]
                    }
                ]
            };
            myChart.setOption(option);
        }
    },
}
</script>
