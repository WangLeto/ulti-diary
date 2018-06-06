let tips = {
  showError: function (title) {
    wx.showToast({
      title: title,
      image: '../assets/error.png'
    });
  },

  showOk: function (title) {
    wx.showToast({
      title: title,
      icon: 'success'
    });
  },

  showLoading: function (title) {
    wx.showLoading({
      title: title
    })
  },

  hideLoading: function () {
    wx.hideLoading();
  }
};

export {tips};
