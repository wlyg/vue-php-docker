import Main from '@/view/main'
import parentView from '@/components/parent-view'

/**
 * iview-admin中meta除了原生参数外可配置的参数:
 * meta: {
 *  hideInMenu: (false) 设为true后在左侧菜单不会显示该页面选项
 *  notCache: (false) 设为true后页面不会缓存
 *  access: (null) 可访问该页面的权限数组，当前路由设置的权限会影响子路由
 *  icon: (-) 该页面在左侧菜单、面包屑和标签导航处显示的图标，如果是自定义图标，需要在图标名称前加下划线'_'
 * }
 */

export default [
  {
    path: '/login',
    name: 'login',
    meta: {
      title: 'Login - 登录',
      hideInMenu: true
    },
    component: () => import('@/view/login/login.vue')
  },
  {
    path: '/',
    name: '_home',
    redirect: '/home',
    component: Main,
    meta: {
      hideInMenu: true,
      notCache: true
    },
    children: [
      {
        path: '/home',
        name: 'home',
        meta: {
          hideInMenu: true,
          title: '首页',
          notCache: true
        },
        component: () => import('@/view/single-page/home')
      }
    ]
  },

  {
    path: '/project',
    name: 'project',
    meta: {
      hide: true
    },
    component: Main,
    children: [
      {
        path: 'project',
        name: 'project',
        meta: {
          icon: 'md-home',
          title: '项目管理'
        },
        component: () => import('@/view/project/project.vue')
      }
    ]
  },
  {
    path: '/news',
    name: 'news',
    meta: {
      hide: true
    },
    component: Main,
    children: [
      {
        path: 'news',
        name: 'news',
        meta: {
          icon: 'md-paper',
          title: '品牌新闻'
        },
        component: () => import('@/view/news/news.vue')
      }
    ]
  },
  {
    path: '/customer-service',
    name: 'customer-service',
    meta: {
      hide: true
    },
    component: Main,
    children: [
      {
        path: 'customer-service',
        name: 'customer-service',
        meta: {
          icon: 'md-contacts',
          title: '客服管理'
        },
        component: () => import('@/view/customer-service/customer-service.vue')
      }
    ]
  },
  {
    path: '/parking-pay',
    name: 'parking-pay',
    meta: {
      hide: true
    },
    component: Main,
    children: [
      {
        path: 'parking-pay',
        name: 'parking-pay',
        meta: {
          icon: 'md-car',
          title: '停车缴费'
        },
        component: () => import('@/view/parking-pay/parking-pay.vue')
      }
    ]
  },
  {
    path: '/member',
    name: 'member',
    meta: {
      hide: true
    },
    component: Main,
    children: [
      {
        path: 'member',
        name: 'member',
        meta: {
          icon: 'md-contact',
          title: '会员信息'
        },
        component: () => import('@/view/member/member.vue')
      }
    ]
  },
  {
    path: '/argu',
    name: 'argu',
    meta: {
      hideInMenu: true
    },
    component: Main,
    children: [
      {
        path: 'params/:id',
        name: 'params',
        meta: {
          icon: 'md-flower',
          title: '动态路由',
          notCache: true
        },
        component: () => import('@/view/argu-page/params.vue')
      },
      {
        path: 'query',
        name: 'query',
        meta: {
          icon: 'md-flower',
          title: '带参路由',
          notCache: true
        },
        component: () => import('@/view/argu-page/query.vue')
      }
    ]
  },
  {
    path: '/401',
    name: 'error_401',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/401.vue')
  },
  {
    path: '/500',
    name: 'error_500',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/500.vue')
  },
  {
    path: '*',
    name: 'error_404',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/404.vue')
  }
]