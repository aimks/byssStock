import request from './request'


export function getTest() {
    return request({
        url: '/test',
        method: 'get',
    })
}
