<template>
    <div>
        <van-nav-bar
            title="早参"
            left-text="返回"
            right-text="捐赠"
            left-arrow
        />
        <div id="main" style="height:400px;"></div>
    </div>
</template>

<script>
import * as echarts from 'echarts';
export default {
    data() {
        return {
            activeNames: ['1'],
        };
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
};
</script>
