<template>
    <div>
        <van-divider content-position="left">管理员权限</van-divider>
        <van-form @submit="onSubmitPassword">
            <van-field
                v-model="password"
                type="password"
                label="密钥"
                :rules="[{ required: true, message: '请填写密码' }]"
            >
                <template #button>
                    <van-button size="small" type="primary">{{hasPermission ? '关闭' : '开启'}}</van-button>
                </template>
            </van-field>
        </van-form>
        <template v-if="hasPermission">
            <van-divider content-position="left">股票操作</van-divider>
            <van-form @submit="onSubmitStock">
                <van-field label="操作">
                    <template #input>
                        <van-radio-group v-model="stock.type" direction="horizontal">
                            <van-radio name="buy">买入</van-radio>
                            <van-radio name="sell">卖出</van-radio>
                        </van-radio-group>
                    </template>
                </van-field>
                <van-field
                    readonly
                    clickable
                    :value="stock.operate_at"
                    label="操作时间"
                    @click="showCalendar = true"
                    :rules="[{ required: true, message: '请选择操作日期' }]"
                />
                <van-calendar v-model="showCalendar" :min-date="new Date(2021, 0, 1)" :max-date="new Date()" @confirm="onConfirmCalendar" />
                <van-field
                    v-model="stock.code"
                    label="股票代码"
                    :rules="[{ required: true, message: '请输入股票代码' }]"
                >
                    <template #button>
                        <van-button size="small" type="primary" native-type="button" :disabled="stock.code == ''" @click="getStockInfo">获取股票信息</van-button>
                    </template>
                </van-field>
                <van-field
                    v-model="stock.name"
                    label="股票名称"
                    disabled
                    :rules="[{ required: true, message: '请先获取股票信息' }]"
                />
                <van-field
                    v-model="stock.close_price"
                    type="number"
                    label="收盘价"
                    disabled
                />
                <van-field
                    v-if="stock.type=='buy'"
                    v-model="stock.amount"
                    type="digit"
                    label="股票数量"
                    disabled
                />
                <div style="margin: 16px;">
                    <van-button round block type="info" native-type="submit">提交</van-button>
                </div>
            </van-form>
        </template>
    </div>
</template>

<script>
import {getStockInfo, operateStock} from "../utils/api";
export default {
    data() {
        return {
            password: '',
            hasPermission: false,
            showCalendar: false,
            stock: {
                type: 'buy',
                operate_at: '',
                code: '',
                name: '',
                close_price: 0,
                amount: 0,
            }
        };
    },
    watch: {
        'stock.code': function () {
            this.stock.name = '';
            this.stock.close_price = 0;
            this.stock.amount = 0;
        },
    },
    mounted() {
        this.password = this.$cookies.get('password');
        this.hasPermission = this.$cookies.get('hasPermission') === 'true';
    },
    methods: {
        onSubmitPassword() {
            if (this.hasPermission) {
                this.hasPermission = false;
                this.$cookies.set('hasPermission', this.hasPermission);
                this.$notify({ type: 'primary', message: '管理员权限关闭，输入密码重新开启' });
                return;
            }
            if (this.password == 'byss0422') {
                this.hasPermission = true;
                this.$cookies.config('3m');
                this.$cookies.set('password', this.password);
                this.$cookies.set('hasPermission', this.hasPermission);
                this.$notify({ type: 'primary', message: '密码正确，管理员权限开启' });
                return;
            }
            this.$notify({ type: 'danger', message: '密码错误，没有操作权限' });
        },
        async onSubmitStock() {
            await operateStock(this.stock);
            this.$notify({ type: 'success', message: '操作成功' });
        },
        onConfirmCalendar(date) {
            this.showCalendar = false;
            this.stock.operate_at = `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
        },
        async getStockInfo()
        {
            const {
                data: {
                    item
                }
            } = await getStockInfo({code: this.stock.code})
            this.stock.name = item.name
            this.stock.close_price = item.close_price
            this.stock.amount = item.amount
        }
    },
};
</script>
