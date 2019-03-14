import axios from '@/libs/api.request'

export const saveNews = ({ data }) => {
  return axios.request({
    url: 'news/save-news',
    data,
    method: 'post'
  })
}
export const editNews = ({ data }) => {
  return axios.request({
    url: 'news/edit-news',
    data,
    method: 'post'
  })
}

export const deleteNews = ({ data }) => {
  return axios.request({
    url: 'news/delete-news',
    data,
    method: 'post'
  })
}

export const saveTopNews = ({ data }) => {
  return axios.request({
    url: 'news/save-top-news',
    data,
    method: 'post'
  })
}

export const editTopNews = ({ data }) => {
  return axios.request({
    url: 'news/edit-top-news',
    data,
    method: 'post'
  })
}

export const deleteTop = ({ data }) => {
  return axios.request({
    url: 'news/delete-top-news',
    data,
    method: 'post'
  })
}

export const getTop = () => {
  return axios.request({
    url: 'news/view-top',
    method: 'get'
  })
}

export const getNews = ({ page }) => {
  return axios.request({
    url: 'news/news?page=' + page + '&per-page=10',
    method: 'get'
  })
}