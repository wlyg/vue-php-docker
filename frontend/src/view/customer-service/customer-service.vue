<template>
  <div v-if="hasRouter">
    <Tabs>
      <TabPane label="上班时间" icon="md-time">
        <Card style="width:70%; margin-left:30px;margin-top:30px">
          <p slot="title">
            <Icon type="md-call"></Icon>
            客服电话
          </p>
          <ul>
            <div style="font-size: 13px;color: #515a6e; margin:10px 50px">
              <!-- 项目咨询电话:
                <Input style="display: inline-block; width: 150px; margin-left:10px;margin-right:70px " v-model="phone" placeholder="请输入客服电话"></Input> -->
              门禁客服电话:
              <Input style="display: inline-block; width: 150px;margin-left:10px;" v-model="entrancePhone" placeholder="请输入客服电话"></Input>
            </div>
          </ul>
        </Card>
        <Card style="width:70%; margin-left:30px;margin-top:30px">
          <p slot="title">
            <Icon type="md-calendar"></Icon>
            常规设置
          </p>
          <ul>
            <Form :model="routineItem" label-position="left" :label-width="200">
              <FormItem label="周一" style="width: 80%;">
                <div class='upload-picture-tip' v-show="!monRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.mon" v-show="!monRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="monRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周二" style="width: 80%">
                <div class='upload-picture-tip' v-show="!tuesRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.tues" v-show="!tuesRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="tuesRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周三" style="width: 80%">
                <div class='upload-picture-tip' v-show="!wedRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.wed" v-show="!wedRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="wedRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周四" style="width: 80%">
                <div class='upload-picture-tip' v-show="!thurRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.thur" v-show="!thurRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="thurRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周五" style="width: 80%">
                <div class='upload-picture-tip' v-show="!friRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.fri" v-show="!friRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="friRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周六" style="width: 80%">
                <div class='upload-picture-tip' v-show="!satRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.sat" v-show="!satRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="satRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
              <Divider style="margin-bottom: 10px; margin-top: -10px" />
              <FormItem label="周天" style="width: 80%">
                <div class='upload-picture-tip' v-show="!sunRest" style="margin-left: 32px">上下班时间</div>
                <TimePicker v-model="routineItem.sun" v-show="!sunRest" format="HH:mm" type="timerange" placement="bottom-start" placeholder="请选择上下班时间" style="width: 168px;"></TimePicker>
                <Checkbox v-model="sunRest" style="position: absolute; right:-60px">休息</Checkbox>
              </FormItem>
            </Form>
          </ul>
        </Card>
        <Card style="width:70%; margin-left:30px;margin-top:30px">
          <p slot="title">
            <Icon type="md-clock"></Icon>
            特殊时间设置
          </p>
          <ul>
            <Form ref="formDynamic" :model="formDynamic" :label-width="0" style="width: 80%">
              <FormItem v-for="(item, index) in formDynamic.items" v-if="item.status" :key="index" :prop="'items.' + index + '.value'">
                <Row>
                  <DatePicker type="date" :options="disabledDateOptions" format="yyyy-MM-dd" v-model="item.date" placeholder="请选择日期" style="width: 200px"></DatePicker>
                  <div class='upload-picture-tip' v-show="!item.isRest" style="margin-left: 30px">上下班时间</div>
                  <TimePicker format="HH:mm" type="timerange" v-show="!item.isRest" v-model="item.time" placement="bottom-end" placeholder="请选择上下班时间" style="width: 168px"></TimePicker>
                  <Checkbox v-model="item.isRest" style="position: absolute; right:-60px">休息</Checkbox>
                  <Button @click="handleRemove(index)" style="position: absolute; right:-130px">删除</Button>

                </Row>
              </FormItem>
              <FormItem>
                <Row>
                  <Col span="12">
                  <Button type="dashed" long @click="handleAdd" icon="md-add">添加</Button>
                  </Col>
                </Row>
              </FormItem>
            </Form>
          </ul>
        </Card>
        <Button type="primary" size="large" style="margin-top:50px;margin-left: 550px" @click="handleSave()">保存</Button>
        <Button size="large" style="margin-top:50px;margin-left: 50px" @click="handleCancel()">取消</Button>
      </TabPane>
      <TabPane label="电话回访" icon="md-chatboxes">
        <Card>
          <tables ref="tables" style="margin-top: 30px ;right: 30px" editable searchable search-place="top" v-model="tableData" :columns="columns" />
          <Page :total="totalCount" :current="page" style="margin-top:20px ;" :page-size="20" @on-change="handleIndex" />
        </Card>
      </TabPane>
    </Tabs>
    <Modal v-model="modalVisible" @on-ok="handleSubmit" @on-cancel="handleCancel">
      <Form :label-width="80">
        <FormItem label="状态">
          <Select v-model="status" style="width:200px">
            <Option v-for="item in statusList" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </FormItem>
        <FormItem label="备注">
          <Input style="width: 350px" v-model="remark" type="textarea"></Input>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
import Tables from '_c/tables'
import { saveTime, getTime, getLeavePhone, editLeavePhone } from '@/api/customer-service'
export default {
  name: 'customer-service',
  components: {
    Tables
  },
  data () {
    return {
      disabledDateOptions: {
        disabledDate (date) {
          return date && date.valueOf() < Date.now() - 86400000
        }
      },
      routineItem: {
        mon: '',
        tues: '',
        wed: '',
        thur: '',
        fri: '',
        sat: '',
        sun: ''
      },
      monRest: false,
      tuesRest: false,
      wedRest: false,
      thurRest: false,
      friRest: false,
      satRest: false,
      sunRest: false,
      index: 1,
      page: 1,
      formDynamic: {
        items: []
      },
      phone: '',
      entrancePhone: '',
      totalCount: 0,
      columns: [
        { title: '电话', key: 'phone' },
        { title: '项目名', key: 'projectName' },
        { title: '时间', key: 'leaveTime' },
        {
          title: '状态',
          key: 'statusName',
          filters: [
            {
              label: '待解决',
              value: 1
            },
            {
              label: '已解决',
              value: 2
            }
          ],
          filterMultiple: false,
          filterMethod (value, row) {
            if (value === 1) {
              return row.statusName === '待解决'
            } else if (value === 2) {
              return row.statusName === '已解决'
            }
          }
        },
        { title: '备注', key: 'remark', tooltip: true },
        {
          title: '操作',
          key: 'action',
          width: 150,
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
                    this.handleEdit(params.index)
                  }
                }
              }, '修改')
            ])
          }
        }
      ],
      tableData: [],
      modalVisible: false,
      status: '',
      remark: '',
      id: '',
      statusList: [
        {
          value: 'toBeSolved',
          label: '待解决'
        },
        {
          value: 'resolved',
          label: '已解决'
        }
      ],
      hasRouter: false
    }
  },
  methods: {
    handleSave () {
      const routineItem = this.routineItem
      const phone = this.phone
      const entrancePhone = this.entrancePhone
      const specialItem = []
      for (let item of this.formDynamic.items) {
        if (item.status === 1) {
          if (item.isRest) {
            if (item.date === '') {
              this.$Message.error('保存失败，请完善特殊时间设置')
              return
            }
          } else {
            if (item.time[0] === '' || item.time[1] === '' || item.date === '') {
              this.$Message.error('保存失败，请完善特殊时间设置')
              return
            }
          }
          specialItem.push({
            date: item.date.valueOf(),
            time: item.isRest ? false : item.time
          })
        }
      }
      if (this.monRest) {
        routineItem.mon = false
      }
      if (this.tuesRest) {
        routineItem.tues = false
      }
      if (this.wedRest) {
        routineItem.wed = false
      }
      if (this.thurRest) {
        routineItem.thur = false
      }
      if (this.friRest) {
        routineItem.fri = false
      }
      if (this.satRest) {
        routineItem.sat = false
      }
      if (this.sunRest) {
        routineItem.sun = false
      }
      saveTime({ routineItem, specialItem, phone, entrancePhone }).then(res => {
        if (res.message === 'ok') {
          this.$Message.success('保存成功')
        } else {
          this.$Message.error('保存失败，请稍后再试')
        }
      })
    },
    handleEdit (index) {
      this.status = this.tableData[index].status
      this.remark = this.tableData[index].remark
      this.id = this.tableData[index]._id
      this.modalVisible = true
    },
    handleSubmit () {
      const id = this.id
      const status = (this.status === 'resolved') ? 'resolved' : 'toBeSolved'
      const remark = this.remark
      editLeavePhone({ id, status, remark }).then(res => {
        if (res.message === 'success') {
          this.$Message.success('修改成功')
          this.handleIndex(this.page)
        } else {
          this.$Message.error('修改失败，请稍后再试')
        }
      })
    },
    handleIndex (value) {
      this.page = value
      const page = value
      const _this = this
      getLeavePhone({ page }).then(res => {
        _this.totalCount = res.data.totalCount
        for (let item of res.data.items) {
          item.statusName = (item.status === 'resolved') ? '已解决' : '待解决'
        }
        _this.tableData = res.data.items
      })
      document.body.scrollTop = 0
      document.documentElement.scrollTop = 0
    },
    handleCancel () {
      const _this = this
      getTime().then(res => {
        if (res.message === 'success') {
          _this.phone = res.data.phone
          _this.entrancePhone = res.data.entrancePhone
          if (res.data.routineItem.mon) {
            _this.monRest = false
            _this.routineItem.mon = res.data.routineItem.mon
          } else {
            _this.monRest = true
          }
          if (res.data.routineItem.tues) {
            _this.tuesRest = false
            _this.routineItem.tues = res.data.routineItem.tues
          } else {
            _this.tuesRest = true
          }
          if (res.data.routineItem.wed) {
            _this.wedRest = false
            _this.routineItem.wed = res.data.routineItem.wed
          } else {
            _this.wedRest = true
          }
          if (res.data.routineItem.thur) {
            _this.thurRest = false
            _this.routineItem.thur = res.data.routineItem.thur
          } else {
            _this.thurRest = true
          }
          if (res.data.routineItem.fri) {
            _this.friRest = false
            _this.routineItem.fri = res.data.routineItem.fri
          } else {
            _this.friRest = true
          }
          if (res.data.routineItem.sat) {
            _this.satRest = false
            _this.routineItem.sat = res.data.routineItem.sat
          } else {
            _this.satRest = true
          }
          if (res.data.routineItem.sun) {
            _this.sunRest = false
            _this.routineItem.sun = res.data.routineItem.sun
          } else {
            _this.sunRest = true
          }
          _this.formDynamic = {
            items: []
          }
          for (let item of res.data.specialItem) {
            var itemDate = new Date()
            itemDate.setTime(item.date)
            _this.formDynamic.items.push({
              isRest: !item.time,
              date: itemDate,
              time: !item.time ? '' : item.time,
              index: 1,
              status: 1
            })
          }
        } else {
          _this.routineItem = {}
          _this.formDynamic = {
            items: [
              {
                isRest: false,
                date: '',
                time: '',
                index: 1,
                status: 1
              }
            ]
          }
        }
      })
    },
    handleAdd () {
      this.index++
      this.formDynamic.items.push({
        value: '',
        index: this.index,
        status: 1
      })
    },
    handleRemove (index) {
      this.formDynamic.items[index].status = 0
    }
  },
  mounted () {
    this.handleIndex(this.page)
    this.handleCancel()
    const _this = this
    if (this.$store.state.user.userName === 'admin') {
      this.hasRouter = true
    }
    for (let item of this.$store.state.user.accountRouter) {
      if (item === '客服管理') {
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
.upload-picture-tip {
  color: #9ea7b4;
  font-size: 13px;
  display: inline-block;
  margin-right: 20px;
}
</style>
