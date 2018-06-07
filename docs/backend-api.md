## 后端 API

所有请求以 HTTP 状态码 200 表示请求成功，请求结果以 application/json 格式在 Response Body 中返回；状态码 400 表示请求失败，具体的错误消息以 application/json 形式在 Response Body 中返回。

### 获取令牌

* URL

| 协议   | URL         | 方法   |
| :--- | :---------- | :--- |
| HTTP | /askSession | POST |

* 请求头

| 名称     | 值                | 描述   |
| :----- | :--------------- | :--- |
| Accept | application/json |      |

* 请求参数

| 名称   | 类型     | 描述              |
| :--- | :----- | :-------------- |
| code | String | 由前端 wx.login 获得 |

* 返回结果示例

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODA4OFwvYXNrU2Vzc2lvbiIsImlhdCI6MTUyODM3MTgxMiwiZXhwIjoxNTI4Mzc1NDEyLCJuYmYiOjE1MjgzNzE4MTIsImp0aSI6Ijhxb2hYVkcxa0NhMlR4YloiLCJzdWIiOjAsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.TNAfuLpUOIMW5-RUnb2uVFsNn89ZvMWFGtVE98L_snw"
}
```

### 文件上传

* URL

| 协议   | URL         | 方法   |
| :--- | :---------- | :--- |
| HTTP | /uploadFile | POST |

* 请求头

| 名称     | 值                | 描述   |
| :----- | :--------------- | :--- |
| Accept | application/json |      |

* 请求参数

| 名称   | 类型     | 描述     |
| :--- | :----- | :----- |
| file | Object | 要上传的文件 |

* 返回结果示例

```json
{
  "path": "public/upload/e6e3e0a652d1cff887bcc48e1107337a8dbd0554.jpg"
}
```

* 异常情况

| 触发条件            | 返回信息   |
| :-------------- | :----- |
| 上传信息不包含 file 字段 | 上传文件为空 |
| file 信息不合法      | 文件上传出错 |

### 获取日记信息（单个）

* URL

| 协议   | URL         | 方法   |
| :--- | :---------- | :--- |
| HTTP | /queryDiary | POST |

* 请求头

| 名称            | 值                   | 描述   |
| :------------ | :------------------ | :--- |
| Accept        | application/json    |      |
| Authorization | Bearer +（空格）+（认证令牌） | 认证信息 |

* 请求参数

| 名称   | 类型      | 描述   |
| :--- | :------ | :--- |
| id   | Integer | 日记ID |

* 返回结果示例

```json
{
  "id": 1,
  "title": "日记1",
  "type": 1,
  "content": "",
  "star": 0,
  "create_at": 1528356843,
  "update_at": 1528356843
}
```

### 提交日记信息

* URL

| 协议   | URL          | 方法   |
| :--- | :----------- | :--- |
| HTTP | /submitDiary | POST |

* 请求头

| 名称            | 值                   | 描述   |
| :------------ | :------------------ | :--- |
| Accept        | application/json    |      |
| Authorization | Bearer +（空格）+（认证令牌） | 认证信息 |

* 请求参数

| 名称      | 类型      | 描述              |
| :------ | :------ | :-------------- |
| title   | String  | 标题              |
| type    | Integer | 类型（1图文、2音频、3视频） |
| content | String  | 内容              |

* 返回结果示例

```json
{
  "id": 1,
  "title": "日记1",
  "type": 1,
  "content": "",
  "star": 0,
  "create_at": 1528356843,
  "update_at": 1528356843
}
```

### 更新日记标题

* URL

| 协议   | URL           | 方法   |
| :--- | :------------ | :--- |
| HTTP | /submitRename | POST |

* 请求头

| 名称            | 值                   | 描述   |
| :------------ | :------------------ | :--- |
| Accept        | application/json    |      |
| Authorization | Bearer +（空格）+（认证令牌） | 认证信息 |

* 请求参数

| 名称    | 类型      | 描述   |
| :---- | :------ | :--- |
| id    | Integer | 日记ID |
| title | String  | 标题   |

* 返回结果示例

```json
{
  "id": 1,
  "title": "日记1",
  "type": 1,
  "content": "",
  "star": 0,
  "create_at": 1528356843,
  "update_at": 1528356843
}
```

### 更新日记收藏状态

* URL

| 协议   | URL         | 方法   |
| :--- | :---------- | :--- |
| HTTP | /submitStar | POST |

* 请求头

| 名称            | 值                   | 描述   |
| :------------ | :------------------ | :--- |
| Accept        | application/json    |      |
| Authorization | Bearer +（空格）+（认证令牌） | 认证信息 |

* 请求参数

| 名称     | 类型      | 描述             |
| :----- | :------ | :------------- |
| id     | Integer | 日记ID           |
| isStar | Integer | 是否收藏（0不收藏、1收藏） |

* 返回结果示例

```json
{
  "id": 1,
  "title": "日记1",
  "type": 1,
  "content": "",
  "star": 0,
  "create_at": 1528356843,
  "update_at": 1528356843
}
```
