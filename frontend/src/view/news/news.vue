<template>
  <div v-if="hasRouter">
    <Card>
      <p slot="title">
        <Icon type="ios-arrow-dropup-circle"></Icon>
        置顶新闻
      </p>
      <Button slot="extra" type="primary" @click="handleCreateTopNews">创建置顶新闻</Button>
      <div>
        <div style="width: 190px; display: inline-block;" v-for="(item, index) in topData" :key="index">
          <Card style="width: 187px;">
            <p style="font-size:16px;font-weight:600;color: gray;width:170px; height: 30px; overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">{{item.title}}</p>
            <Poptip :transfer="true" trigger="hover" width='400' style="z-index:9999">
              <div class="box">
                <img v-if="item.img[0]" :src="item.img[0].url" />
              </div>
              <div slot="content" style="width:365px;">
                <p style="font-size:16px;font-weight:600;color: gray;width:365px; white-space:normal">{{item.title}}</p>
                <p style="color: gray;width:365px;white-space:normal">{{item.newsUrl}}</p>
                <img style="width:365px" v-if="item.img[0]" :src="item.img[0].url" />
              </div>
            </Poptip>
            <Button style="margin-top:20px;margin-left:60px" type="primary" size="small" @click="handleEditTop(item)">修改</Button>
            <Poptip confirm title="您确认删除这条内容吗？" @on-ok="handleDeleteTop(item)">
              <Button style="margin-top:20px;margin-left:10px" type="error" size="small">删除</Button>
            </Poptip>

          </Card>
        </div>
      </div>
    </Card>
    <Card style="margin-top:10px;">
      <p slot="title">
        <Icon type="ios-paper"></Icon>
        新闻
      </p>
      <Button slot="extra" type="primary" @click="newsModalVisible = true">创建新闻</Button>
      <div>
        <div style="width: 190px; display: inline-block;" v-for="(item, index) in newsData" :key="index">
          <Card style="width: 187px;">
            <p style="font-size:16px;font-weight:600;color: gray;width:170px; height: 30px; overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">{{item.title}}</p>
            <Poptip :transfer="true" trigger="hover" width='400' style="z-index:9999">
              <div class="box">
                <img v-if="item.img[0]" :src="item.img[0].url" />
              </div>
              <div slot="content" style="width:365px;">
                <p style="font-size:16px;font-weight:600;color: gray;width:365px; white-space:normal">{{item.title}}</p>
                <p style="color: gray;width:365px;white-space:normal">{{item.newsUrl}}</p>
                <img style="width:365px" v-if="item.img[0]" :src="item.img[0].url" />
              </div>
            </Poptip>
            <Button style="margin-top:20px;margin-left:60px" type="primary" size="small" @click="handleEditNews(item)">修改</Button>
            <Poptip confirm title="您确认删除这条内容吗？" @on-ok="handleDeleteNews(item)">
              <Button style="margin-top:20px;margin-left:10px" type="error" size="small">删除</Button>
            </Poptip>
          </Card>
        </div>
      </div>
      <Page :total="newsCount" :current="page" style="margin-top:20px ;" :page-size="10" @on-change="handleGetPageNews" />
    </Card>
    <Modal v-model="newsModalVisible" title="创建新闻" :footer-hide='true' @on-cancel="handleCancel">
      <Form :label-width="80">
        <FormItem label="标题">
          <Input style="width: 350px" :maxlength="50" v-model="newsForm.title" placeholder="新闻标题，限50字"></Input>
        </FormItem>
        <FormItem label="上传封面">
          <div class="demo-upload-list" v-for="(item, index) in img" :key="index">
            <img :src="item.url">
            <div class="demo-upload-list-cover">
              <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
              <Icon type="ios-trash-outline" @click.native="handleRemoveImg(item)"></Icon>
            </div>
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="img" :format="['jpg','jpeg','png']" :before-upload="handleBeforeUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*640像素，大小不超过1M，格式为jpg、png的图片，最多上传一张，请在提示上传成功后再进行其他操作</div>
        </FormItem>
        <FormItem label="新闻链接">
          <Input style="width: 350px" v-model="newsForm.newsUrl"></Input>
        </FormItem>
      </Form>
      <Button size="large" style="margin-left: 330px" @click="handleCancel()">取消</Button>
      <Button type="primary" size="large" style="margin-left: 20px" @click="handleSaveNews()">保存</Button>
    </Modal>
    <Modal v-model="topNewsModalVisible" title="创建置顶新闻" :footer-hide='true' @on-cancel="handleCancel">
      <Form :label-width="80">
        <FormItem label="标题">
          <Input style="width: 350px" :maxlength="50" v-model="topNewsForm.title" placeholder="新闻标题，限50字"></Input>
        </FormItem>
        <FormItem label="上传封面">
          <div class="demo-upload-list" v-for="(item, index) in img" :key="index">
            <img :src="item.url">
            <div class="demo-upload-list-cover">
              <Icon type="ios-eye-outline" @click.native="handleView(item)"></Icon>
              <Icon type="ios-trash-outline" @click.native="handleRemoveImg(item)"></Icon>
            </div>
          </div>
          <Upload ref="upload" :show-upload-list="false" :default-file-list="img" :format="['jpg','jpeg','png']" :before-upload="handleBeforeUpload" multiple type="drag" action="//jsonplaceholder.typicode.com/posts/" style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
              <Icon type="ios-camera" size="20"></Icon>
            </div>
          </Upload>
          <div class="upload-picture-tip">建议800*427像素，大小不超过1M，格式为jpg、png的图片，最多上传一张，请在提示上传成功后再进行其他操作</div>
        </FormItem>
        <FormItem label="新闻链接">
          <Input style="width: 350px" v-model="topNewsForm.newsUrl"></Input>
        </FormItem>
      </Form>
      <Button size="large" style="margin-left: 330px" @click="handleCancel()">取消</Button>
      <Button type="primary" size="large" style="margin-left: 20px" @click="handleSaveTopNews()">保存</Button>
    </Modal>
    <Modal :footer-hide='true' title="查看图片" v-model="visible">
      <img :src="imgUrl" v-if="visible" style="width: 100%">
    </Modal>
  </div>
</template>

<script>
import Tables from '_c/tables'
import { savePicture } from '@/api/project'
import { saveNews, editNews, deleteNews, saveTopNews, editTopNews, getTop, deleteTop, getNews } from '@/api/news'
export default {
  name: 'customer-service',
  components: {
    Tables
  },
  data () {
    return {
      topData: [],
      newsData: [],
      newsForm: {
        title: '',
        newsUrl: ''
      },
      topNewsForm: {
        title: '',
        newsUrl: ''
      },
      newsModalVisible: false,
      topNewsModalVisible: false,
      visible: false,
      imgUrl: '',
      topCount: 0,
      newsCount: 0,
      page: 1,
      img: [],
      hasRouter: false
    }
  },
  methods: {
    handleCreateTopNews () {
      if (this.topCount < 5) {
        this.topNewsModalVisible = true
      } else {
        this.$Message.warning('置顶新闻至多有五个')
      }
    },
    handleView (item) {
      this.imgUrl = item.url
      this.visible = true
    },
    handleRemoveImg (file) {
      this.img.splice(this.img.indexOf(file), 1)
    },
    handleBeforeUpload (file) {
      const check = this.img.length < 1
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
          _this.img.push(file)
        }
      }
      return check
    },
    handleSaveNews () {
      var tip = ''
      if (this.newsForm.title === '') {
        tip = '创建/修改失败，请输入新闻标题'
      } else if (this.img.length === 0) {
        tip = '创建/修改失败，请上传新闻封面'
      } else if (this.newsForm.newsUrl === '') {
        tip = '创建/修改失败，请输入新闻链接'
      }
      if (tip !== '') {
        this.$Message.error(tip)
      } else {
        if (this.newsForm._id) {
          const data = {
            id: this.newsForm._id,
            title: this.newsForm.title,
            img: this.img,
            newsUrl: this.newsForm.newsUrl
          }
          editNews({ data }).then(res => {
            if (res.message === 'success') {
              this.$Message.success('修改成功')
              this.newsModalVisible = false
              this.handleCancel()
            } else {
              this.$Message.error('修改失败，请稍后再试')
            }
          })
        } else {
          const data = {
            title: this.newsForm.title,
            img: this.img,
            newsUrl: this.newsForm.newsUrl
          }
          saveNews({ data }).then(res => {
            if (res.message === 'success') {
              this.$Message.success('创建成功')
              this.newsModalVisible = false
              this.handleCancel()
            } else {
              this.$Message.error('创建失败，请稍后再试')
            }
          })
        }
      }
    },
    handleSaveTopNews () {
      var tip = ''
      if (this.topNewsForm.title === '') {
        tip = '创建/修改失败，请输入新闻标题'
      } else if (this.img.length === 0) {
        tip = '创建/修改失败，请上传新闻封面'
      } else if (this.topNewsForm.newsUrl === '') {
        tip = '创建/修改失败，请输入新闻链接'
      }
      if (tip !== '') {
        this.$Message.error(tip)
      } else {
        if (this.topNewsForm._id) {
          const data = {
            id: this.topNewsForm._id,
            title: this.topNewsForm.title,
            img: this.img,
            newsUrl: this.topNewsForm.newsUrl
          }
          editTopNews({ data }).then(res => {
            if (res.message === 'success') {
              this.$Message.success('修改成功')
              this.topNewsModalVisible = false
              this.handleCancel()
            } else {
              this.$Message.error('修改失败，请稍后再试')
            }
          })
        } else {
          const data = {
            title: this.topNewsForm.title,
            img: this.img,
            newsUrl: this.topNewsForm.newsUrl
          }
          saveTopNews({ data }).then(res => {
            if (res.message === 'success') {
              this.$Message.success('创建成功')
              this.topNewsModalVisible = false
              this.handleCancel()
            } else {
              this.$Message.error('创建失败，请稍后再试')
            }
          })
        }
      }
    },
    handleGetTop () {
      getTop().then(res => {
        this.topData = res.data
        this.topCount = res.count
        if (this.topCount === 0) {
          this.$Message.warning('暂无置顶新闻，请创建至少一个置顶新闻')
          this.topNewsModalVisible = true
        }
      })
    },
    handleGetPageNews (value) {
      this.page = value
      const page = value
      getNews({ page }).then(res => {
        this.newsData = res.data.items
        this.newsCount = res.data.totalCount
      })
    },
    handleEditTop (item) {
      this.topNewsModalVisible = true
      this.topNewsForm = item
      this.img = [].concat(item.img)
    },
    handleEditNews (item) {
      this.newsModalVisible = true
      this.newsForm = item
      this.img = [].concat(item.img)
    },
    handleDeleteTop (item) {
      const data = {
        id: item._id
      }
      deleteTop({ data }).then(res => {
        if (res.code === 200) {
          this.$Message.success('删除成功')
        } else {
          this.$Message.error('删除失败，请稍后再试')
        }
      })
      this.handleCancel()
    },
    handleDeleteNews (item) {
      const data = {
        id: item._id
      }
      deleteNews({ data }).then(res => {
        if (res.code === 200) {
          this.$Message.success('删除成功')
        } else {
          this.$Message.error('删除失败，请稍后再试')
        }
      })
      this.handleCancel()
    },
    handleCancel () {
      this.newsForm = {
        title: '',
        newsUrl: ''
      }
      this.topNewsForm = {
        title: '',
        newsUrl: ''
      }
      this.img = []
      this.newsModalVisible = false
      this.topNewsModalVisible = false
      setTimeout(() => {
        this.handleGetTop()
        this.handleGetPageNews(this.page)
      }, 200)
    }
  },
  mounted () {
    this.handleGetTop()
    this.handleGetPageNews(1)
    const _this = this
    if (this.$store.state.user.userName === 'admin') {
      this.hasRouter = true
    }
    for (let item of this.$store.state.user.accountRouter) {
      if (item === '品牌新闻') {
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
.box {
  width: 160px;
  height: 120px;
  text-align: center;
  font-size: 0;
}
.box:before {
  display: inline-block;
  content: "";
  width: 0;
  vertical-align: middle;
  height: 100%;
}
.box img {
  vertical-align: middle;
  max-width: 100%;
  max-height: 100%;
}
</style>
