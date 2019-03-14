import axios from '@/libs/api.request'

export const saveTime = ({ routineItem, specialItem, phone, entrancePhone }) => {
  const data = {
    routineItem,
    specialItem,
    phone,
    entrancePhone
  }
  return axios.request({
    url: 'customer-service/save',
    data,
    method: 'post'
  })
}

export const getTime = () => {
  return axios.request({
    url: 'customer-service/get',
    method: 'get'
  })
}

export const getLeavePhone = ({ page }) => {
  return axios.request({
    url: 'leave-phone/get?page='+ page +'&per-page=20',
    method: 'get'
  })
}

export const editLeavePhone = ({ id, status, remark }) => {
  const data = {
    id,
    status,
    remark
  }
  return axios.request({
    url: 'leave-phone/edit',
    data,
    method: 'post'
  })
}