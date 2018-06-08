import wepy from 'wepy';
import {submitDiary, submitStar, queryDiary} from '../api/api';
import tips from '../utils/tips';

export default class testMixin extends wepy.mixin {
  data = {
    images: {
      star: ['../assets/star.png', '../assets/star-color.png']
    },
    isStared: false,
    id: null,
    name: null,
    finalContent: null
  };

  computed = {
    starImg: function () {
      return this.images.star[this.isStared ? 1 : 0];
    },
    // 是否已经上传过，控制 star 图标显示
    isSubmitted: function () {
      return !!this.id;
    }
  };

  methods = {
    star: async function () {
      this.isStared = !this.isStared;
      let id = this.id;
      let isStared = this.isStared;
      let result = await submitStar({
        data: {
          starId: id,
          isStar: !isStared
        }
      });
    },
    del: function () {
      wx.showModal({
        title: '删除',
        content: '此操作会删除当前日记，是否继续？',
        success: function (res) {
          if (res.confirm) {
            wx.navigateBack();
          }
        }
      });
    },
    // 最终提交 
    submitLastOnePaunch: async function () {
      this.packSubmitData();
      let title = this.name;
      let type = this.type;
      let content = this.finalContent;
      let detail = this.detail;
      let data = {
        title: title,
        type: type,
        content: content,
        detail: detail,
      };
      console.log(data);
      let result = await submitDiary({
        data: data, 
      });
      console.log(result);
      if (result.statusCode === 200) {
        this.id = result.data;
        tips.showOk('上传成功');
        wx.navigateBack();
      }
    }
  };

  onLoad(params) {
    // todo 接口：判断是否是由记录点击进入，从而获取数据
    if (params.id) {
      let id = params.id;
      this.id = id;
      this.name = params.name;
      this.getRawDiary(id);
    }
    let that = this;
    wx.setNavigationBarTitle({
      title: that.pageTitle
    });
  }

  getRawDiary = async function (id) {
    let result = await queryDiary({
      data: {
        id: id
      }
    });
    if (result.statusCode === 200) {
      // this.finalContent = result.data;
    }
  };

  // 将秒数转为 00:01 字符串
  secondFormat = function (seconds) {
    let minutes = Math.floor(seconds / 60);
    seconds = seconds - minutes * 60;
    return this._str_pad_left(minutes, '0', 2) + ':' + this._str_pad_left(seconds, '0', 2);
  };
  // 转换为 0'12'' 字符串
  secondFormat2 = function (seconds) {
    let minutes = Math.floor(seconds / 60);
    seconds = seconds - minutes * 60;
    return minutes + "'" + seconds + "''";
  };

  _str_pad_left = function (string, pad, length) {
    return (new Array(length + 1).join(pad) + string).slice(-length);
  };
}
