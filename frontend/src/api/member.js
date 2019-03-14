import axios from '@/libs/api.request'

export const getData = () => {
  return axios.request({
    url: 'member/get',
    method: 'get'
  })
}

export const getRecordList = ({ currentPage, pageSize }) => {
  return axios.request({
    url: 'member/get-record-list?currentPage=' + currentPage + '&pageSize=' + pageSize,
    method: 'get'
  })
}
