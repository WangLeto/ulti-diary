import wepy from 'wepy';
import {submitDiary, submitStar} from '../api/api';

export default class testMixin extends wepy.mixin {
  data = {
    images: {
      star: ['../assets/star.png', '../assets/star-color.png']
    },
    isStared: false,
    id: null
  };

  computed = {
    starImg: function () {
      return this.images.star[this.isStared ? 1 : 0];
    },

    // 是否已经上传过，控制 star 图标显示
    isFirstEdit: function () {
      return this.id === null;
    }
  };

  onShow = function () {
    let that = this;
    wx.setNavigationBarTitle({
      title: that.title
    });
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
    submit: async function () {
      let submitData = this.packSubmitData();
      let result = await submitDiary({
        data: {
          blob: submitData
        }
      });
      if (result.statusCode === 200) {
        this.id = result.data;
      } else if (result.statusCode === 400) {
        this.showError();
      }
    }

  };

  showError = function (msg) {

  }

  packSubmitData = function () {
    return null;
  }
}
