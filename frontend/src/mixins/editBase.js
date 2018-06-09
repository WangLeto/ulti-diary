import wepy from 'wepy';
import {submitDiary, submitStar, queryDiary, delDiary} from '../api/api';
import {tips} from '../utils/tips';

export default class testMixin extends wepy.mixin {
  data = {
    starImages: ['../assets/star.png', '../assets/star-color.png'],
    isStared: false,
    id: null,
    name: null,
    finalContent: null
  };

  computed = {
  };

  methods = {
    star: function () {
      this.realStar();
    },
    del: function () {
      let id = this.id;
      wx.showModal({
        title: '删除',
        content: '此操作会删除当前日记，是否继续？',
        success: function (res) {
          if (res.confirm) {
            if (!id) {
              wx.navigateBack();
            } else {
              testMixin.deleteDiary(id);
            }
          }
        }
      });
    }
  };

  async realStar() {
    let isStared = this.isStared;
    let id = this.id;
    let result = await submitStar({
      data: {
        id: id,
        isStar: !isStared ? 1 : 0
      }
    });
    if (result.statusCode === 200) {
      console.log(result.data.star);
      this.isStared = !!result.data.star;
      if (this.isStared) {
        tips.showOk('已收藏');
      } else {
        tips.showOk('已取消收藏');
      }
      this.$apply();
    } else if (result.statusCode === 401) {
      await wepy.$instance.getSession();
      this.realStar();
    }
  };

  static async deleteDiary(id) {
    let result = await delDiary({
      data: {
        id: id
      }
    });
    if (result.statusCode === 200) {
      setTimeout(() => {
        tips.showOk('删除成功');
      }, 500);
      wx.navigateBack();
    } else if (result.statusCode === 401) {
      await wepy.$instance.getSession();
      this.deleteDiary(id);
    }
  }

  starImage() {
    return this.starImages[this.isStared ? 1 : 0];
  }

  onLoad() {
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
    if (result.statusCode === 401) {
      await wepy.$instance.getSession();
      result = await this.getRawData(id);
    } else if (result.statusCode === 200) {
      return result;
    }
  };

  // 最终提交
  submitLastOnePaunchAndGoBack = async function () {
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
      wx.navigateBack();
      tips.showOk('上传成功');
    } else if (result.statusCode === 401) {
      await wepy.$instance.getSession();
      this.submitLastOnePaunchAndGoBack();
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
