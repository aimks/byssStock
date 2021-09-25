
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import 'vant/lib/index.css'
import {
    Cell,
    CellGroup,
    Switch,
    NavBar,
    Collapse,
    CollapseItem,
} from 'vant';

import {Toast} from 'vant';

Vue.use(Cell);
Vue.use(CellGroup);
Vue.use(Switch);
Vue.use(NavBar);
Vue.use(Collapse);
Vue.use(CollapseItem);
Vue.use(Toast);

const app = new Vue({
    router,
    el: '#app',
    render: h => h(App)
});
