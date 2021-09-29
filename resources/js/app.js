
import Vue from 'vue';
import VueCookies from 'vue-cookies'
import App from './App.vue';
import router from './router';
import 'vant/lib/index.css'
import {
    Form,
    Field,
    Radio,
    RadioGroup,
    Calendar,
    PasswordInput,
    Divider,
    Button,
    NoticeBar,
    Cell,
    CellGroup,
    List,
    Tag,
    Col,
    Row,
    Tab,
    Tabs,
    Pagination,
} from 'vant';

import {Notify} from 'vant';

Vue.use(Form);
Vue.use(Field);
Vue.use(PasswordInput);
Vue.use(Radio);
Vue.use(RadioGroup);
Vue.use(Calendar);
Vue.use(Divider);
Vue.use(Button);
Vue.use(NoticeBar);
Vue.use(Cell);
Vue.use(CellGroup);
Vue.use(List);
Vue.use(Tag);
Vue.use(Col);
Vue.use(Row);
Vue.use(Tab);
Vue.use(Tabs);
Vue.use(Pagination);

Vue.use(Notify);

Vue.use(VueCookies);

const app = new Vue({
    router,
    el: '#app',
    render: h => h(App)
});
