<template>
  <div v-if="hasRouter">
    <Card>
      <tables ref="tables" editable searchable search-place="top" v-model="tableData" :columns="columns" @on-delete="handleDelete" />
      <Page :total="totalRecord" :current="currentPage" :page-size="pageSize" style="margin-top:20px ;" show-total show-elevator show-sizer
        @on-change="changePage" @on-page-size-change="changePageSize"/>
    </Card>
  </div>
</template>

<script>
import Tables from '_c/tables'
import { getRecordList } from '@/api/parking-pay'
export default {
  name: 'parking-pay',
  components: {
    Tables
  },
  data () {
    return {
      columns: [
        { title: '车场Id', key: 'parkId' },
        { title: '地点', key: 'parkName' },
        { title: '车牌号', key: 'plateNo' },
        { title: '入场时间', key: 'inTime' },
        { title: '出场时间', key: 'outTime' },
        { title: '停放时长', key: 'parkLong' }
      ],
      tableData: [],
      hasRouter: false,
      currentPage: 1,
      pageSize: 20,
      totalRecord: ''
    }
  },
  methods: {
    secondToDate: function (second) {
      var time = second
      if (time !== null && time !== '') {
        if (time > 60 && time < 60 * 60) {
          time = parseInt(time / 60.0) + '分钟' + parseInt((parseFloat(time / 60.0) - parseInt(time / 60.0)) * 60) + '秒'
        } else if (time >= 60 * 60 && time < 60 * 60 * 24) {
          time = parseInt(time / 3600.0) + '小时' + parseInt((parseFloat(time / 3600.0) -
          parseInt(time / 3600.0)) * 60) + '分钟' +
          parseInt((parseFloat((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) -
          parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60)) * 60) + '秒'
        } else if (time >= 60 * 60 * 24) {
          time = parseInt(time / 3600.0 / 24) + '天' + parseInt((parseFloat(time / 3600.0 / 24) -
          parseInt(time / 3600.0 / 24)) * 24) + '小时' + parseInt((parseFloat(time / 3600.0) -
          parseInt(time / 3600.0)) * 60) + '分钟' +
          parseInt((parseFloat((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) -
          parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60)) * 60) + '秒'
        } else {
          time = parseInt(time) + '秒'
        }
      }

      return time
    },
    handleDelete (params) {
      console.log(params)
    },
    changePage (currentPage) {
      this.currentPage = currentPage
      this.getTableData(this.currentPage, this.pageSize)
    },
    changePageSize (pageSize) {
      this.pageSize = pageSize
      this.getTableData(this.currentPage, this.pageSize)
    },
    getTableData (currentPage, pageSize) {
      getRecordList({ currentPage, pageSize }).then(res => {
        if (res.code === 200) {
          for (let item of res.data.record) {
            item.parkLong = this.secondToDate(item.parkLong)
          }
          this.totalRecord = res.data.count
          this.tableData = res.data.record
        } else {
          this.tableData = []
        }
      })
    }
  },
  mounted () {
    const _this = this
    _this.getTableData(this.currentPage, this.pageSize)
    if (this.$store.state.user.userName === 'admin') {
      this.hasRouter = true
    }
    for (let item of this.$store.state.user.accountRouter) {
      if (item === '停车缴费') {
        _this.hasRouter = true
      }
    }
    if (!this.hasRouter) {
      this.$Message.error('您没有该页的访问权限，请选择其他页面或者联系管理员增加权限')
    }
  }
}
</script>

<style>
</style>
