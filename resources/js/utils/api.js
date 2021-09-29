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

export function getAssetsChart() {
    return request({
        url: '/stocks/assets/chart',
        method: 'get',
    })
}

export function getRecords(params) {
    return request({
        url: '/stocks/assets/records',
        method: 'get',
        params,
    })
}

export function getProfits(params) {
    return request({
        url: '/stocks/assets/profits',
        method: 'get',
        params,
    })
}
