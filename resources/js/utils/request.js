import axios from 'axios'
import {Toast} from 'vant';

/**
 * @description axios初始化
 */
const instance = axios.create({
    // 设置baseUr地址,如果通过proxy跨域可直接填写base地址
    baseURL: '/api',
    // 定义统一的请求头部
    headers: {
        "Content-Type": "application/json;charset=UTF-8"
    },
    // 配置请求超时时间
    timeout: 10000,
    // 如果用的JSONP，可以配置此参数带上cookie凭证，如果是代理和CORS不用设置
    withCredentials: true
});
/**
 * @description axios请求拦截器
 */
instance.interceptors.request.use(
    (config) => {
    // 自定义header，可添加项目token
    config.headers.token = 'token';
    //文件类,文件类不需要stringify
    if (config.data instanceof FormData) {
        config.headers['Content-Type'] = 'multipart/form-data'
    }
    return config;
}, (error) => {
    Toast.fail('加载超时！');
    return Promise.reject(error)
});
/**
 * @description axios响应拦截器
 */
instance.interceptors.response.use(
    (response) => {
    // 获取接口返回结果
    const res = response.data;
    // 成功返回
    if (res.code === 200) {
        return Promise.resolve(res);
    } else {
        Toast.fail(res.msg);
        return Promise.reject(res);
    }
}, (error)=>{
    Toast.fail('网络请求异常！');
    return Promise.reject(error);
});


export default instance;
