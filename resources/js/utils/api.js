import request from './request'


export function getStockInfo(params) {
    return request({
        url: '/stocks/info',
        method: 'get',
        params,
    })
}
export function operateStock(data) {
    return request({
        url: '/stocks/operate',
        method: 'post',
        data,
    })
}

export function getHoldings() {
    return request({
        url: '/stocks/holdings',
        method: 'get',
    })
}
