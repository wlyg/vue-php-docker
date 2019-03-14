import axios from '@/libs/api.request'

export const createProject = ({ formItem, coverImg, infoImg, houseImg, panoramaImg, designerImg }) => {
  const data = {
    formItem,
    coverImg,
    infoImg,
    houseImg,
    panoramaImg,
    designerImg
  }
  return axios.request({
    url: 'project/create-project',
    data,
    method: 'post'
  })
}

export const editProject = ({ formItem, coverImg, infoImg, houseImg, panoramaImg, designerImg }) => {
  const data = {
    formItem,
    coverImg,
    infoImg,
    houseImg,
    panoramaImg,
    designerImg
  }
  return axios.request({
    url: 'project/edit-project',
    data,
    method: 'post'
  })
}

export const codeAndView = ({ data }) => {
  return axios.request({
    url: 'project/code-and-view',
    data,
    method: 'post'
  })
}

export const getProject = ({ page, perPage, searchKey }) => {
  const data = {
    'page': page,
    'per-page': perPage,
    'searchKey': searchKey
  }
  return axios.request({
    url: 'project/get-project',
    data,
    method: 'get'
  })
}

export const deleteProject = ({ data }) => {
  return axios.request({
    url: 'project/delete-project',
    data,
    method: 'post'
  })
}

export const saveAdvertisement = ({ adImg }) => {
  const data = {
    adImg
  }
  return axios.request({
    url: 'project/save-advertisement',
    data,
    method: 'post'
  })
}

export const getAdvertisement = () => {
  return axios.request({
    url: 'project/get-advertisement',
    method: 'get'
  })
}

export const savePicture = ({ file }) => {
  const data = {
    file
  }
  return axios.request({
    url: 'project/save-picture',
    data,
    method: 'post'
  })
}

export const getWeixinConfig = () => {
  return axios.request({
    url: 'config/get-config',
    method: 'get'
  })
}

export const saveWeixinConfig = ({ appid, secret }) => {
  const data = {
    'appid': appid,
    'secret': secret
  }
  return axios.request({
    url: 'config/save-config',
    data,
    method: 'post'
  })
}
