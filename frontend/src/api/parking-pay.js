import axios from '@/libs/api.request'

export const getData = () => {
  return axios.request({
    url: 'parking-pay/get',
    method: 'get'
  })
}

export const getRecordList = ({ currentPage, pageSize }) => {
  return axios.request({
    url: 'parking-pay/get-record-list?currentPage=' + currentPage + '&pageSize=' + pageSize,
    method: 'get'
  })
}
