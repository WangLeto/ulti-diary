import wepy from 'wepy';

const apiRoot = 'http://127.0.0.1:8018/';

const request = async (params = {}, url) => {
  let requestContent = {
    url: url,
    method: params.method || 'GET',
    data: params.data || {}
  };
  if (wepy.$instance.globalData.session) {
    let thirdSession = await wepy.$instance.globalData.session;
    requestContent.header = {
      thirdSession: thirdSession
    };
  }
  return await wepy.request(requestContent);
};

const submitDiary = (params) => request(params, apiRoot + 'submitDiary');
const submitStar = (params) => request(params, apiRoot + 'submitStar');
const askSession = (params) => request(params, apiRoot + 'askSession');

module.exports = {
  apiRoot,
  submitDiary,
  submitStar,
  askSession
};
