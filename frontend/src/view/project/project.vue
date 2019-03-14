<template>
  <div v-if="hasRouter">
    <Card>
      <Poptip style="z-index: 999; margin-left: 157px; margin-top: 59px; position: absolute;"
        trigger="hover" word-wrap width="200" placement="right" content="先根据第一个字符，以先数字后字母的规则（0,1,2...9, a,b,c…z）进行排列；如果第一个字符一样，那么根据第二个、第三个乃至后面的字符按照相同规则进行排列。如果字符数不一样但前部分都相同（如001 和 0011），则字符数短的优先。">
        <Icon type="md-information-circle" />
      </Poptip>
      <Button type="primary" @click="handleClickConfig()" style="margin-top：200px; float: right">公众号配置</Button>
      <Button type="primary" @click="handleClick()" style="margin-top：200px; float: right; margin-right: 10px;">推荐页头图设置</Button>
      <Button type="primary" @click="modalVisible = true" style="margin-top：200px; float: right; margin-right: 10px;">创建项目</Button>
      <tables ref="tables" style="margin-top: 30px" editable searchable search-place="top" v-model="tableData" :columns="columns" @on-delete="handleDelete" />
    </Card>
    <Modal v-model="modalVisible" title="创建项目" @on-cancel="handleCancel" :footer-hide='true'>
      <Form :model="formItem" :label-width="80">
        <div style="font-size: 14px;margin-bottom: 8px;">必填信息</div>
        <FormItem label="项目编号">
          <Input v-model="formItem.code" placeholder="请输入项目编号"></Input>
          <div class="upload-picture-tip">项目根据编号排序，建议使用字母或数字</div>
        </FormItem>
        <FormItem label="项目名称">
          <Input v-model="formItem.name" :maxlength='12' placeholder="请输入项目名称,建议不超过12个字符"></Input>
        </FormItem>
        <FormItem label="项目类型">
          <Input v-model="formItem.type" placeholder="请输入项目类型,如商品房、商铺等"></Input>
          <div class="upload-picture-tip">不同类型用逗号隔开</div>
        </FormItem>
        <FormItem label="客服电话">
          <Input v-model="formItem.phone" placeholder="请输入电话号码"></Input>
        </FormItem>
        <FormItem label="地址">
          <Input id="address" v-model="formItem.location" :maxlength='20' placeholder="请输入详细地址，如:武汉市洪山区....建议不超过20个字符"></Input>
          <Input v-model="formItem.latitude" placeholder="请输入纬度，如 30.474080" style="width: 48%;margin-right: 2%;margin-top: 1%;"></Input>
          <Input v-model="formItem.longitude" placeholder="请输入经度，如 114.405640" style="width: 50%;margin-top: 1%;"></Input>
          <div class="upload-picture-tip">建议输入详细地址，可点击地图更改坐标</div>
          <Poptip placement="right" width="650">
            <Button type="primary" @click="handleGetByLocation" size='small'>从地图获取坐标</Button>
            <div class="api" slot="content">
              <div id="container">
              </div>
            </div>
          </Poptip>
        </FormItem>
        <FormItem label="均价">
          <Input v-model="formItem.averagePrice" :maxlength='30' placeholder="请输入均价，建议不超过30个字符"></Input>
        </FormItem>
        <FormItem label="全景看房">
          <Input v-model="formItem.connect" placeholder="链接，如 http://......."></Input>
        </FormItem>
        <FormItem label="全景看房图">
          <div class="demo-upload-list" v-for="(item, index) in panoramaImg" :key="index">
            <img :src="item.url">
            <div class="demo-upload-list-cover">
              <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
              <Icon type="ios-trash-outline" @click.native="handleRemovePanoramaImg(item)"></Icon>
            </div>
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="panoramaImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforePanoramaUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*382像素，大小不超过1M，格式为jpg、png的图片，最多上传一张，请在提示上传成功后再进行其他操作</div>
        </FormItem>
        <FormItem label="推荐列图片">
          <div class="demo-upload-list" v-for="(item, index) in coverImg" :key="index">
            <img :src="item.url">
            <div class="demo-upload-list-cover">
              <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
              <Icon type="ios-trash-outline" @click.native="handleRemoveCoverImg(item)"></Icon>
            </div>
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="coverImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforeCoverUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*427像素，大小不超过1M，格式为jpg、png的图片，最多上传一张，请在提示上传成功后再进行其他操作</div>

        </FormItem>
        <FormItem label="详情页图片">
          <div class="demo-upload-list" v-for="(item, index) in infoImg" :key="index">
            <img :src="item.url">
            <div class="demo-upload-list-cover">
              <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
              <Icon type="ios-trash-outline" @click.native="handleRemoveInfoImg(item)"></Icon>
            </div>
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="infoImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforeInfoUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*427像素，大小不超过1M，格式为jpg、png的图片，数量不限，请在提示上传成功后再进行其他操作</div>
        </FormItem>
        <FormItem label="户型图">
          <div v-for="(item, index) in houseImg" :key="index">
            <div class="demo-upload-list">
              <img :src="item.url">
              <div class="demo-upload-list-cover">
                <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
                <Icon type="ios-trash-outline" @click.native="handleRemoveHouseImg(item)"></Icon>
              </div>
            </div>
            <Input v-model="item.houseTag" :maxlength='15' placeholder="户型标签，限15字" style="width: 200px; margin-top:-55px;" clearable />
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="houseImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforeHouseUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*800像素，大小不超过1M，格式为jpg、png的图片，数量不限，请在提示上传成功后再进行其他操作</div>
        </FormItem>
        <FormItem label="项目特色">
          <RadioGroup v-model="special" type="button">
            <Radio label="交通"></Radio>
            <Radio label="户型"></Radio>
            <Radio label="设施"></Radio>
            <Radio label="配套"></Radio>
          </RadioGroup>
          <Input v-show="special == '交通'" :maxlength='200' v-model="formItem.traffic" type="textarea" :rows="3" style="word-break: break-all;" placeholder="项目交通情况，限200字" clearable />
          <Input v-show="special == '户型'" :maxlength='200' v-model="formItem.houseType" type="textarea" :rows="3" style="word-break: break-all;" placeholder="户型介绍，限200字" clearable />
          <Input v-show="special == '设施'" :maxlength='200' v-model="formItem.facilities" type="textarea" :rows="3" style="word-break: break-all;" placeholder="设施介绍，限200字" clearable />
          <Input v-show="special == '配套'" :maxlength='200' v-model="formItem.periphery" type="textarea" :rows="3" style="word-break: break-all;" placeholder="配套介绍，限200字" clearable />
          <div class="upload-picture-tip">交通、户型、设施、配套至少要填一个</div>
        </FormItem>
        <div style="font-size: 14px;margin-bottom: 8px;">选填信息</div>
        <FormItem label="概况">
          <Input v-model="formItem.characteristic" :maxlength='300' type="textarea" :rows="4" placeholder="项目概况，限300字" style="word-break: break-all;" clearable />
        </FormItem>
        <FormItem label="获奖">
          <Input v-model="formItem.award" type="textarea" :maxlength='200' :rows="3" placeholder="获奖信息，限200字" style="word-break: break-all;" clearable />
        </FormItem>
        <FormItem label="项目详情">
          <RadioGroup v-model="detail" type="button">
            <Radio label="产权年限"></Radio>
            <Radio label="交房时间"></Radio>
            <Radio label="车位配比"></Radio>
            <Radio label="绿化率"></Radio>
          </RadioGroup>
          <Input v-show="detail == '产权年限'" :maxlength='15' v-model="formItem.fixedYear" placeholder="产权年限，限15字" clearable />
          <Input v-show="detail == '交房时间'" :maxlength='15' v-model="formItem.makeHouse" placeholder="交房时间，限15字" clearable />
          <Input v-show="detail == '车位配比'" :maxlength='15' v-model="formItem.parking" placeholder="车位配比，限15字" clearable />
          <Input v-show="detail == '绿化率'" :maxlength='15' v-model="formItem.green" placeholder="绿化率，限15字" clearable />
        </FormItem>
        <FormItem label="设计师团队">
          <div v-for="(item, index) in designerImg" :key="index">
            <div class="demo-upload-list">
              <img :src="item.url">
              <div class="demo-upload-list-cover">
                <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
                <Icon type="ios-trash-outline" @click.native="handleRemoveDesignerImg(item)"></Icon>
              </div>
            </div>
            <Input v-model="item.designerName" :maxlength='15' placeholder="设计师姓名" style="width: 200px; margin-top:-55px;" clearable />
            <Input v-model="item.info" type="textarea" :maxlength='100' :rows="2" placeholder="设计师简介，限100字" style="margin-bottom:10px;word-break: break-all;" clearable />
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="designerImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforeDesignerUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*800像素，大小不超过1M，格式为jpg、png的图片，数量不限，请在提示上传成功后再进行其他操作</div>
        </FormItem>
      </Form>
      <Button size="large" style="margin-left: 330px" @click="handleCancel()">取消</Button>
      <Button type="primary" size="large" style="margin-left: 20px" @click="handleSubmit()">保存</Button>
    </Modal>
    <Modal v-model="addModalVisible" title="推荐页头图设置" @on-cancel="handleCancel" :footer-hide='true'>
      <Form :model="formItem" :label-width="50">
        <FormItem label="">
          <div v-for="(item, index) in adImg" :key="index">
            <div class="demo-upload-list">
              <img :src="item.url">
              <div class="demo-upload-list-cover">
                <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
                <Icon type="ios-trash-outline" @click.native="handleRemoveAdImg(item)"></Icon>
              </div>
            </div>
            <Input v-model="item.adUrl" placeholder="链接，如 http://......." style="width: 300px; margin-top:-55px;" clearable />
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="adImg" :format="['jpg','jpeg','png']" :before-upload="handleBeforeAdUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
        </FormItem>
      </Form>
      <div class="upload-picture-tip">建议800*427像素，大小不超过1M，格式为jpg、png的图片，数量不限，请在提示上传成功后再进行其他操作</div>
      <Button size="large" style="margin-left: 330px" @click="handleCancel()">取消</Button>
      <Button type="primary" size="large" style="margin-left: 20px" @click="handleAdSubmit()">保存</Button>
    </Modal>
    <Modal v-model="weixinModalVisible" title="公众号开发信息" :footer-hide='true' @on-cancel="handleCancel">
      <Form :label-width="105">
        <FormItem label="开发者ID(AppID)">
          <Input style="width: 350px" :maxlength="50" v-model="appid" clearable></Input>
        </FormItem>
        <FormItem label="开发者密码(AppSecret)">
          <Input style="width: 350px" type="password" :maxlength="50" v-model="secret" clearable></Input>
        </FormItem>
      </Form>
      <Button size="large" style="margin-left: 330px" @click="handleCancel()">取消</Button>
      <Button type="primary" size="large" style="margin-left: 20px" @click="handleSaveConfig()">保存</Button>
    </Modal>
    <Modal :footer-hide='true' title="查看图片" v-model="visible">
      <img :src="url" v-if="visible" style="width: 100%">
    </Modal>
  </div>
</template>

<script>
import Tables from '_c/tables'
import TablesEdit from '../../components/tables/edit.vue'
import { tMap } from '@/api/tMap'
import { createProject, editProject, getProject, deleteProject, saveAdvertisement,
  getAdvertisement, savePicture, codeAndView, saveWeixinConfig, getWeixinConfig } from '@/api/project'
export default {
  name: 'project',
  components: {
    Tables,
    TablesEdit
  },
  data () {
    return {
      mapView: false,
      searchKey: '',
      page: 1,
      perPage: 10,
      visible: false,
      coverImg: [],
      panoramaImg: [],
      infoImg: [],
      houseImg: [],
      adImg: [],
      designerImg: [],
      special: '',
      detail: '',
      modalVisible: false,
      addModalVisible: false,
      weixinModalVisible: false,
      appid: '',
      secret: '',
      formItem: {
        id: '',
        code: '',
        name: '',
        type: '',
        phone: '',
        location: '',
        latitude: '',
        longitude: '',
        averagePrice: '',
        connect: '',
        characteristic: '',
        houseType: '',
        traffic: '',
        facilities: '',
        periphery: '',
        award: '',
        fixedYear: '',
        makeHouse: '',
        parking: '',
        green: ''
      },
      columns: [
        {
          title: '是否显示',
          key: 'action',
          width: 85,
          render: (h, params) => {
            return h('div', [
              h('i-switch', {
                props: {
                  size: 'small',
                  value: this.tableData[params.index].isPublished
                },
                style: {
                },
                on: {
                  'on-change': (value) => {
                    const data = {
                      'id': this.tableData[params.index]._id,
                      'isPublished': value,
                      'code': this.tableData[params.index].code
                    }
                    codeAndView({ data }).then(res => {
                      if (res.message === 'ok') {
                        this.$Message.success('修改成功')
                        this.getProject()
                      } else {
                        this.$Message.error('修改失败，请稍后再试')
                      }
                    })
                  }
                }
              }, '显示')
            ])
          }
        },
        {
          title: '项目编号',
          key: 'code',
          render: (h, params) => {
            return h(TablesEdit, {
              props: {
                params: params,
                value: this.tableData[params.index][params.column.key],
                edittingCellId: this.edittingCellId,
                editable: true
              },
              on: {
                'input': val => {
                  this.edittingText = val
                },
                'on-start-edit': (params) => {
                  this.edittingCellId = `editting-${params.index}-${params.column.key}`
                  this.$emit('on-start-edit', params)
                },
                'on-cancel-edit': (params) => {
                  this.edittingCellId = ''
                  this.$emit('on-cancel-edit', params)
                },
                'on-save-edit': (params) => {
                  if (this.edittingText === '' || !(/^[a-zA-Z0-9]+$/.test(this.edittingText))) {
                    this.$Message.error('修改失败，项目编号有误，请检查后再试')
                  } else {
                    const data = {
                      'id': this.tableData[params.index]._id,
                      'isPublished': this.tableData[params.index].isPublished,
                      'code': this.edittingText
                    }
                    codeAndView({ data }).then(res => {
                      if (res.message === 'ok') {
                        this.$Message.success('修改成功')
                        this.getProject()
                      } else {
                        this.$Message.error('修改失败，请稍后再试')
                      }
                    })
                  }
                  this.edittingCellId = ''
                }
              }
            })
          }
        },
        { title: '项目名', key: 'name' },
        { title: '类型', key: 'type' },
        { title: '地址', key: 'location' },
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
              }, '修改'),
              h('Poptip', {
                props: {
                  confirm: true,
                  title: '你确定要删除吗?'
                },
                on: {
                  'on-ok': () => {
                    const data = {
                      'id': this.tableData[params.index]._id
                    }
                    deleteProject({ data }).then(res => {
                      if (res.code === 200) {
                        this.$Message.success('删除成功')
                        this.getProject()
                      } else {
                        this.$Message.error('删除失败，请稍后再试')
                      }
                    })
                  }
                }
              },
              [
                h('i-button', {
                  props: {
                    type: 'error',
                    size: 'small'
                  }
                }, '删除')
              ])
            ])
          }
        }
      ],
      tableData: [],
      edittingCellId: '',
      hasRouter: false
    }
  },
  methods: {
    handleClickConfig () {
      this.weixinModalVisible = true
      const _this = this
      getWeixinConfig().then(res => {
        if (res.code === 200) {
          _this.appid = res.data.appid
          _this.secret = res.data.secret
        } else {
          _this.appid = ''
          _this.secret = ''
        }
      })
    },
    handleSaveConfig () {
      let appid = this.appid
      let secret = this.secret
      saveWeixinConfig({ appid, secret }).then(res => {
        if (res.code === 200) {
          this.$Message.success('保存成功')
          this.weixinModalVisible = false
        } else {
          this.$Message.error('保存失败，请稍后再试')
        }
      })
    },
    handleView (item) {
      this.url = item.url
      this.visible = true
    },
    handleRemovePanoramaImg (file) {
      this.panoramaImg.splice(this.panoramaImg.indexOf(file), 1)
    },
    handleRemoveCoverImg (file) {
      this.coverImg.splice(this.coverImg.indexOf(file), 1)
    },
    handleRemoveInfoImg (file) {
      this.infoImg.splice(this.infoImg.indexOf(file), 1)
    },
    handleRemoveHouseImg (file) {
      this.houseImg.splice(this.houseImg.indexOf(file), 1)
    },
    handleRemoveAdImg (file) {
      this.adImg.splice(this.adImg.indexOf(file), 1)
    },
    handleRemoveDesignerImg (file) {
      this.designerImg.splice(this.designerImg.indexOf(file), 1)
    },
    handleBeforePanoramaUpload (file) {
      const check = this.panoramaImg.length < 1
      const checkSize = (file.size / 1048576) < 1
      if (!check) {
        this.$Notice.warning({
          title: '只能上传一张图片'
        })
      } else if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.panoramaImg.push(file)
        }
      }
      return check
    },
    handleBeforeCoverUpload (file) {
      const check = this.coverImg.length < 1
      const checkSize = (file.size / 1048576) < 1
      if (!check) {
        this.$Notice.warning({
          title: '只能上传一张图片'
        })
      } else if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.coverImg.push(file)
        }
      }
      return check
    },
    handleBeforeInfoUpload (file) {
      const checkSize = (file.size / 1048576) < 1
      if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.infoImg.push(file)
        }
      }
      return checkSize
    },
    handleBeforeHouseUpload (file) {
      const checkSize = (file.size / 1048576) < 1
      if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.houseImg.push(file)
        }
      }
      return checkSize
    },
    handleBeforeAdUpload (file) {
      const checkSize = (file.size / 1048576) < 1
      if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.adImg.push(file)
        }
      }

      return checkSize
    },
    handleBeforeDesignerUpload (file) {
      const checkSize = (file.size / 1048576) < 1
      if (!checkSize) {
        this.$Notice.warning({
          title: '图片大小超过限制'
        })
      } else if (file.type !== 'image/jpeg' && file.type !== 'image/jpg' && file.type !== 'image/png') {
        this.$Notice.warning({
          title: '图片格式错误'
        })
      } else {
        let reader = new FileReader()
        reader.readAsDataURL(file)
        const _this = this
        reader.onloadend = function (e) {
          file.url = reader.result
          savePicture({ file }).then(res => {
            file.url = res.fileName
            _this.$Notice.success({
              title: '上传成功'
            })
          })
          _this.designerImg.push(file)
        }
      }

      return checkSize
    },
    handleClear (e) {
      if (e.target.value === '') this.insideTableData = this.value
    },
    handleCleanForm () {
      this.formItem.id = ''
      this.formItem.code = ''
      this.formItem.name = ''
      this.formItem.type = ''
      this.formItem.phone = ''
      this.formItem.location = ''
      this.formItem.longitude = ''
      this.formItem.latitude = ''
      this.formItem.averagePrice = ''
      this.formItem.connect = ''
      this.formItem.characteristic = ''
      this.formItem.houseType = ''
      this.formItem.traffic = ''
      this.formItem.facilities = ''
      this.formItem.periphery = ''
      this.formItem.award = ''
      this.formItem.fixedYear = ''
      this.formItem.makeHouse = ''
      this.formItem.parking = ''
      this.formItem.green = ''
      this.coverImg = []
      this.panoramaImg = []
      this.infoImg = []
      this.houseImg = []
      this.designerImg = []
    },
    handleDelete (index) {
      console.log(this.tableData[params])
    },
    handleEdit (index) {
      this.formItem.id = this.tableData[index]._id
      this.formItem.code = this.tableData[index].code
      this.formItem.name = this.tableData[index].name
      this.formItem.type = this.tableData[index].type
      this.formItem.phone = this.tableData[index].phone
      this.formItem.location = this.tableData[index].location
      this.formItem.latitude = this.tableData[index].position.latitude
      this.formItem.longitude = this.tableData[index].position.longitude
      this.formItem.averagePrice = this.tableData[index].averagePrice
      this.formItem.connect = this.tableData[index].connect
      this.formItem.characteristic = this.tableData[index].characteristic
      this.formItem.houseType = this.tableData[index].houseType
      this.formItem.traffic = this.tableData[index].traffic
      this.formItem.facilities = this.tableData[index].facilities
      this.formItem.periphery = this.tableData[index].periphery
      this.formItem.award = this.tableData[index].award
      this.formItem.fixedYear = this.tableData[index].fixedYear
      this.formItem.makeHouse = this.tableData[index].makeHouse
      this.formItem.parking = this.tableData[index].parking
      this.formItem.green = this.tableData[index].green
      this.coverImg = [].concat(this.tableData[index].coverImg)
      this.panoramaImg = [].concat(this.tableData[index].panoramaImg)
      this.infoImg = [].concat(this.tableData[index].infoImg)
      this.houseImg = [].concat(this.tableData[index].houseImg)
      this.designerImg = [].concat(this.tableData[index].designerImg)
      this.modalVisible = true
    },
    exportExcel () {
      this.$refs.tables.exportCsv({
        filename: `table-${(new Date()).valueOf()}.csv`
      })
    },
    handleClick () {
      this.addModalVisible = true
      const _this = this
      getAdvertisement().then(res => {
        if (res.message === 'success') {
          _this.adImg = res.data.adImg
        } else {
          _this.adImg = []
        }
      })
    },
    handleSubmit () {
      const formItem = this.formItem
      const coverImg = this.coverImg
      const panoramaImg = this.panoramaImg
      const infoImg = this.infoImg
      const houseImg = this.houseImg
      const designerImg = this.designerImg
      var tip = ''
      if (formItem.code === '' || !(/^[a-zA-Z0-9]+$/.test(formItem.code))) {
        tip = '创建/修改失败，项目编号有误，请检查后再试'
      } else if (formItem.name === '') {
        tip = '创建/修改失败，请输入项目名称'
      } else if (formItem.type === '') {
        tip = '创建/修改失败，请输入项目类型'
      } else if (formItem.phone === '') {
        tip = '创建/修改失败，请输入电话'
      } else if (formItem.location === '') {
        tip = '创建/修改失败，请输入项目地址'
      } else if (formItem.latitude === '' || formItem.longitude === '' || !(/^[0-9.]+$/.test(formItem.latitude)) || !(/^[0-9.]+$/.test(formItem.longitude))) {
        tip = '创建/修改失败，经纬度输入有误'
      } else if (formItem.averagePrice === '') {
        tip = '创建/修改失败，请输入均价'
      } else if (formItem.connect === '') {
        tip = '创建/修改失败，请输入全景看房地址'
      } else if (panoramaImg.length === 0) {
        tip = '创建/修改失败，请上传全景看房图'
      } else if (coverImg.length === 0) {
        tip = '创建/修改失败，请上传推荐列图片'
      } else if (infoImg.length === 0) {
        tip = '创建/修改失败，请上传详情页图片'
      } else if (houseImg.length === 0) {
        tip = '创建/修改失败，请上传户型图'
      } else if (formItem.traffic === '' && formItem.houseType === '' && formItem.facilities === '' && formItem.periphery === '') {
        tip = '创建/修改失败，交通、户型、设施、配套至少要填一个'
      } else if (designerImg.length !== 0) {
        for (let item of designerImg) {
          if (!item.designerName || !item.info || item.designerName === '' || item.info === '') {
            tip = '创建/修改失败，设计师信息不完整'
          }
        }
      }
      if (tip !== '') {
        this.$Message.error(tip)
      } else {
        if (formItem.id) {
          editProject({ formItem, coverImg, infoImg, houseImg, panoramaImg, designerImg }).then(res => {
            if (res.message === 'ok') {
              this.$Message.success('修改成功')
              this.getProject()
              this.handleCleanForm()
              this.modalVisible = false
            } else {
              this.$Message.error('修改失败，请稍后再试')
              this.handleCleanForm()
            }
          })
        } else {
          createProject({ formItem, coverImg, infoImg, houseImg, panoramaImg, designerImg }).then(res => {
            if (res.message === 'ok') {
              this.$Message.success('创建成功')
              this.getProject()
              this.handleCleanForm()
              this.modalVisible = false
            } else {
              this.$Message.warning('创建失败，请稍后再试')
              this.handleCleanForm()
            }
          })
        }
      }
    },
    handleAdSubmit () {
      if (this.adImg.length === 0) {
        this.$Message.error('至少保留一张图片')
      } else {
        const adImg = this.adImg
        saveAdvertisement({ adImg }).then(res => {
          if (res.message === 'ok') {
            this.$Message.success('保存成功')
            this.addModalVisible = false
          } else {
            this.$Message.error('保存失败，请稍后再试')
          }
        })
      }
      this.adImg = []
    },
    handleCancel () {
      this.modalVisible = false
      this.addModalVisible = false
      this.weixinModalVisible = false
      this.handleCleanForm()
      this.getProject()
      this.$Message.info('取消')
    },
    handleAdCancel () {
      this.adImg = []
      this.$Message.info('取消')
    },
    getProject () {
      const page = this.page
      const perPage = this.perPage
      const searchKey = this.searchKey
      getProject({ page, perPage, searchKey }).then(res => {
        this.tableData = res.data
      })
    },
    handleGetPosition () {
      window.open('https://lbs.qq.com/tool/getpoint/', '_blank', 'toolbar=yes, width=1300, height=900')
    },
    handleGetByLocation () {
      const address = this.formItem.location
      if (address !== '') {
        const _this = this
        tMap('SFEBZ-NT73F-GBEJV-NOC7V-FOP5E-GZFAR').then(qq => {
          var map = new qq.maps.Map(document.getElementById('container'), {
            center: new qq.maps.LatLng(30.555165634175708, 114.3182373046875),
            zoom: 10
          })
          qq.maps.event.addListener(map, 'click', function (event) {
            _this.formItem.latitude = event.latLng.getLat()
            _this.formItem.longitude = event.latLng.getLng()
          })
          var geocoder = new qq.maps.Geocoder({
            complete: function (result) {
              map.setCenter(result.detail.location)
              map.zoomTo(15)
              var marker = new qq.maps.Marker({
                map: map,
                position: result.detail.location
              })
              _this.formItem.latitude = marker.position.lat
              _this.formItem.longitude = marker.position.lng
            }
          })
          if (address !== '') {
            geocoder.getLocation(address)
          }
        })
      }
    }
  },
  mounted () {
    this.getProject()
    const _this = this
    tMap('SFEBZ-NT73F-GBEJV-NOC7V-FOP5E-GZFAR').then(qq => {
      var map = new qq.maps.Map(document.getElementById('container'), {
        center: new qq.maps.LatLng(30.555165634175708, 114.3182373046875),
        zoom: 10
      })
      qq.maps.event.addListener(map, 'click', function (event) {
        _this.formItem.latitude = event.latLng.getLat()
        _this.formItem.longitude = event.latLng.getLng()
      })
    })
    if (this.$store.state.user.userName === 'admin') {
      this.hasRouter = true
    }
    for (let item of this.$store.state.user.accountRouter) {
      if (item === '项目管理') {
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
.demo-upload-list {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  margin-right: 4px;
}
.demo-upload-list img {
  width: 100%;
  height: 100%;
}
.demo-upload-list-design {
  display: inline-block;
  width: 100%;
  height: 60px;
}
.demo-upload-list-design-img {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  margin-right: 4px;
}
.demo-upload-list-design-img img {
  width: 100%;
  height: 100%;
}
.demo-upload-list-cover {
  display: none;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
}
.demo-upload-list:hover .demo-upload-list-cover {
  display: block;
}
.demo-upload-list-cover i {
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  margin: 0 2px;
}
.upload-picture-tip {
  color: #9ea7b4;
  font-size: 11px;
}
#container {
  min-width: 600px;
  min-height: 600px;
}
</style>
