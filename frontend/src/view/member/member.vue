<template>
  <div v-if="hasRouter">
    <Card>
      <tables ref="tables" editable searchable search-place="top" v-model="tableData" :columns="columns" />
      <Page :total="totalRecord" :current="currentPage" :page-size="pageSize" style="margin-top:20px ;" show-total show-elevator show-sizer
        @on-change="changePage" @on-page-size-change="changePageSize"/>
    </Card>
    <Modal v-model="showInfo">
      <img :src="info.avatar" style="width: 100px;height: 100px; border-radius: 50%; margin-left: 30px; margin-top: 30px">
      <div style="display: inline-block ; margin-left: 20px">
        <div style="width 200px; font-size:25px">{{info.uName}}</div>
        <div style="color: #9ea7b4;">{{info.sex}} · {{info.age}}岁 · {{info.phone}}</div>
      </div>

      <Form :label-width="80" style="width: 80%; margin-top: 30px">
        <FormItem label="姓名" style="margin-top: 15px">
          <div>{{info.trueName}}</div>
        </FormItem>
        <FormItem label="邮箱" style="margin-top: 15px">
          <div>{{info.email}}</div>
        </FormItem>
        <FormItem label="部门" style="margin-top: 15px">
          <div>{{info.position}}</div>
        </FormItem>
        <FormItem label="公司" style="margin-top: 15px">
          <div>{{info.cpName}}</div>
        </FormItem>
      </Form>
    </Modal>
  </div>

</template>

<script>
import Tables from '_c/tables'
import { getRecordList } from '@/api/member'
export default {
  name: 'member',
  components: {
    Tables
  },
  data () {
    return {
      columns: [
        { title: 'id', key: 'uid' },
        { title: '昵称', key: 'uName' },
        { title: '电话', key: 'phone' },
        { title: '注册时间', key: 'createdAt' },
        { title: '公司名', key: 'cpName' },
        { title: '部门', key: 'position' },
        { title: '地址', key: 'cpAddress' },
        {
          title: '操作',
          key: 'action',
          width: 150,
          align: 'center',
          render: (h, params) => {
            return h('div', [
              h('Button', {
                props: {
                  type: 'primary',
                  size: 'small'
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    this.handlShow(params.index)
                  }
                }
              }, '详情')
            ])
          }
        }
      ],
      tableData: [],
      showInfo: false,
      info: '',
      hasRouter: false,
      currentPage: 1,
      pageSize: 20,
      totalRecord: ''
    }
  },
  methods: {
    handlShow (index) {
      this.info = this.tableData[index]
      this.showInfo = true
      // this.$Modal.info({
      //     content: `Name：${this.tableData[index].name}<br>Age：${this.tableData[index].age}<br>Address：${this.tableData[index].cpaddress}`
      // })
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
      if (item === '会员信息') {
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
