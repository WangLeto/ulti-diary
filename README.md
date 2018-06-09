# 极日记

查看我们的 [完整文档](https://wangleto.github.io/ulti-diary/) 。

## 前端

使用 wepy 构建，[wepy 项目地址](https://github.com/Tencent/wepy)，[wepy 官方文档](https://tencent.github.io/wepy/document.html#/)。

### 安装依赖

```bash
# 全局安装 wepy
npm install wepy-cli -g

cd frontend
npm instll
```

### 实时编译

```bash
npm run dev
```

### 导入项目

构建后，使用微信开发者工具打开 `dist` 目录，app id 为 `wx7248f970ddf46532`。

**注意**：导入后需在详情中关闭“ES6 转 ES5”、“上传代码时样式自动补全”、“上传代码时自动压缩”。

### 注意事项

`wepy` 的 `repeat` 标签有坑，不要使用。列表渲染使用微信小程序原生的 `wx:for`。

### 使用的开源项目

[wx_calendar](https://github.com/treadpit/wx_calendar)

## 后端

详见线上文档。