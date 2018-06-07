<?php

return [
	/**
	 * 小程序APPID
	 */
    'appid' => 'wx7248f970ddf46532',
    /**
     * 小程序Secret
     */
    'secret' => 'cfe8e76646b538e03459539b5ea987a9',
    /**
     * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
     */
    'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];
