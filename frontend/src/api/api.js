import wepy from 'wepy';
import {tips} from '../utils/tips';

const apiRoot = 'http://127.0.0.1:8018/';

const request = async (params = {}, url) => {
  let requestContent = {
    url: url,
    method: params.method || 'GET',
    data: params.data || {}
  };
  if (wepy.$instance.globalData.session) {
    requestContent.header = {
      thirdSession: wepy.$instance.globalData.session
    };
  }
  tips.showLoading('请稍等');
  let r = await wepy.request(requestContent);
  tips.hideLoading();
  return r;
};

const submitDiary = (params) => request(params, apiRoot + 'submitDiary');
const submitStar = (params) => request(params, apiRoot + 'submitStar');
const askSession = (params) => request(params, apiRoot + 'askSession');
const submitRename = (params) => request(params, apiRoot + 'rename');
// 图片、视频仅需 url；视频需要时长信息
const queryDiary = (params) => request(params, apiRoot + 'queryDiary');

module.exports = {
  apiRoot,
  submitDiary,
  submitStar,
  askSession,
  submitRename,
  queryDiary
};
