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
    requestContent.header.Authorization = wepy.$instance.globalData.session;
    // requestContent.header.Authorization = wx.getStorageSync('session');
  }
  // 在拦截器关闭 loading
  return await wepy.request(requestContent);
};

const submitDiary = (params) => request(params, apiRoot + 'submitDiary');
const submitStar = (params) => request(params, apiRoot + 'submitStar');
const askSession = (params) => request(params, apiRoot + 'askSession');
const submitRename = (params) => request(params, apiRoot + 'submitRename');
const getDayHasDiary = (params) => request(params, apiRoot + 'getDayHasDiary');
const getDayDiaryList = (params) => request(params, apiRoot + 'getDiaryList');
const delDiary = (params) => request(params, apiRoot + 'removeDiary');
const queryDiary = (params) => request(params, apiRoot + 'queryDiary');
const searchDiary = (params) => request(params, apiRoot + 'searchDiary');
const updateDiary = (params) => request(params, apiRoot + 'submitUpdate');
const uploadFile = async (filePath) => {
  let token = wepy.$instance.globalData.session;
  let r = await wepy.uploadFile({
    url: apiRoot + 'uploadFile',
    filePath: filePath,
    name: 'file',
    header: {
      Authorization: token
    }
  });
  if (r.statusCode === 200) {
    r.data = JSON.parse(r.data);
    r.data['path'] = apiRoot + r.data['path'];
  } else if (r.statusCode === 401) {
    r = await uploadFile(filePath);
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
  uploadFile,
  getDayHasDiary,
  getDayDiaryList,
  delDiary,
  searchDiary,
  updateDiary,
};
