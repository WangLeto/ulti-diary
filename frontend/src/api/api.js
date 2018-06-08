import wepy from 'wepy';
import {tips} from '../utils/tips';

const apiRoot = 'https://9axbvepi.qcloud.la/';

const request = async (params = {}, url) => {
  let requestContent = {
    url: url,
    method: params.method || 'POST',
    data: params.data || {}
  };
  requestContent.header = {
    Accept: 'application/json'
  };
  if (wepy.$instance.globalData.session) {
    requestContent.header.Authorization = 'Bearer ' + wepy.$instance.globalData.session;
  }
  console.log(requestContent);
  tips.showLoading('请稍等');
  // 在拦截器关闭 loading
  return await wepy.request(requestContent);
};

const submitDiary = (params) => request(params, apiRoot + 'submitDiary');
const submitStar = (params) => request(params, apiRoot + 'submitStar');
const askSession = (params) => request(params, apiRoot + 'askSession');
const submitRename = (params) => request(params, apiRoot + 'rename');
// 图片、视频仅需 url；视频需要时长信息
const queryDiary = (params) => request(params, apiRoot + 'queryDiary');
const uploadFile = async (filePath) => {
  let token = 'Bearer ' + wepy.$instance.globalData.session;
  let r = await wepy.uploadFile({
    url: apiRoot + 'uploadFile',
    filePath: filePath,
    name: 'file',
    header: {
      token: token
    }
  });
  if (r.statusCode === 200) {
    r.data = JSON.parse(r.data);
    r.data['path'] = apiRoot + r.data['path'];
  }
  return r;
};

module.exports = {
  apiRoot,
  submitDiary,
  submitStar,
  askSession,
  submitRename,
  queryDiary,
  uploadFile
};
