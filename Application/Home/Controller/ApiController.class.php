<?php

namespace Home\Controller;

use Think\Controller;

class ApiController extends Controller
{
    const TOKEN = 'phpmaster';
    // 微信公众号appid appsecret
    const APPID = "wx5d700b1fd7c6bb21";
    const APPSECRET = "18fc068a13214bae1fc94255732f0302";

    public function index()
    {
        if(isset($_GET['echostr'])){
            if($this->checkWechat()){
                echo $_GET['echostr'];
            }
        }else{
             $this->responseMsg();
        }

    }

    //响应
    public function responseMsg()
    {
        //$str = $GLOBALS["HTTP_RAW_POST_DATA"];// 7-
        $postStr = file_get_contents("php://input");//php 7+
//        file_put_contents("a.txt", $postStr);
        //将xml格式的字符串转成对象
//        libxml_disable_entity_loader(true);
        $this->postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // 用户发送的数据类型
        $MsgType = $this->postObj->MsgType;
        switch ($MsgType) {
            // 事件
            case 'event':
                $this->sendEvent();
                break;
            // 文本消息
            case 'text':
                $Content = $this->postObj->Content;
                // 查询天气
                $this->sendText($Content);
                break;
            // 文本消息
            case 'voice':
                $this->sendVoice($this->getUserFile('voice', '.' . $this->postObj->Format));
                // 回复语音识别的内容
//                $this->sendText($this->postObj->Recognition);

                break;
            case 'image':
                //  1.如果用户发送了图片，获取用户发送视频的media_id
                //  2.通过media_id 调用获取临时素材接口 把图片下载到本地
                //  3.通过调用上传临时素材接口 上传图片文件并返回mediaid

                $this->sendImg($this->getUserFile('image', '.jpg'));
                break;
            case 'video':
                //  1.如果用户发送了视频，获取用户发送视频的media_id
                //  2.通过media_id 调用获取临时素材接口 把视频下载到本地
                //  3.通过调用上传临时素材接口 上传视频文件并返回mediaid

                $this->sendVideo($this->getUserFile('video', '.mp4'));
                break;
        }
    }

    // 事件操作
    public function sendEvent()
    {
        switch ($this->postObj->Event) {
            // 订阅 关注
            case 'subscribe':
                $this->sendText('非常感谢您的关注，公众号功能还在持续开发完善中，敬请期待！有任何问题请点击帮助按钮！');
                break;
            // 取消订阅 取消关注
            case 'unsubscribe':
                break;
            case "CLICK":
                // 点击了菜单
                $this->sendClick();
                break;
        }
    }

    public function sendClick()
    {
        switch ($this->postObj->EventKey) {
            case 'joke':
                break;
            case 'girl':
                $this->sendImg();
                break;
            case 'video':
                $this->sendVideo();
                break;
            case 'help':
                $Content = "公众号功能持续开发完善中\n";
                $Content .= "现有功能1.发文字，图片，语音，视频，原路返回（发的什么，回复什么）\n";
//                $Content .= "公众号功能持续开发完善中\n";
//                $Content = "公众号功能持续开发完善中\n";
                $this->sendText($Content);
                break;
        }
    }

    // 发送文本
    public function sendText($content)
    {
        // 被动回复给用户消息
        $str = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[text]]></MsgType>
                  <Content><![CDATA[%s]]></Content>
               </xml>";
        $ToUserName = $this->postObj->ToUserName;
        $FromUserName = $this->postObj->FromUserName;
        $CreateTime = time();
        $resultStr = sprintf($str, $FromUserName, $ToUserName, $CreateTime, $content);
        echo $resultStr;
    }

    // 发送语音
    public function sendVoice($media_id)
    {
        // 被动回复给用户消息
        $str = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[voice]]></MsgType>
                  <Voice>
                    <MediaId><![CDATA[%s]]></MediaId>
                  </Voice>
                </xml>";
        $ToUserName = $this->postObj->ToUserName;
        $FromUserName = $this->postObj->FromUserName;
        $CreateTime = time();
        $resultStr = sprintf($str, $FromUserName, $ToUserName, $CreateTime, $media_id);
        echo $resultStr;
    }

    public function sendImg($media_id)
    {
        $str = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                  <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                  </Image>
               </xml>";
        $ToUserName = $this->postObj->ToUserName;
        $FromUserName = $this->postObj->FromUserName;
        $CreateTime = time();
        $resultStr = sprintf($str, $FromUserName, $ToUserName, $CreateTime, $media_id);
        echo $resultStr;
    }

    // 发送图文
    public function sendNews()
    {
        $str = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[news]]></MsgType>
                  <ArticleCount>%s</ArticleCount>
                  <Articles>
                    <item>
                      <Title><![CDATA[%s]]></Title>
                      <Description><![CDATA[%s]]></Description>
                      <PicUrl><![CDATA[%s]]></PicUrl>
                      <Url><![CDATA[%s]]></Url>
                    </item>
                      <item>
                      <Title><![CDATA[%s]]></Title>
                      <Description><![CDATA[%s]]></Description>
                      <PicUrl><![CDATA[%s]]></PicUrl>
                      <Url><![CDATA[%s]]></Url>
                    </item>
                  </Articles>
               </xml>";
        $ToUserName = $this->postObj->ToUserName;
        $FromUserName = $this->postObj->FromUserName;
        $CreateTime = time();
        $count = 2;
        // sprintf 格式化输出
        $t1 = "CCTV第一次提到了汉服断代的原因！";
        $d1 = "CCTV法制频道第一次提到了汉服断代的原因历史告诉我们的是正视它 而不是回避大国之殇：汉服断代简史";
        $pic1 = "http://www.phpmaster.cn/usr/uploads/2019/11/2408243032.jpg";
        $url1 = "http://www.phpmaster.cn/index.php/archives/59/";
        $t2 = "胡汉中国的定型期——清帝国(作为汉人,你不可不知)";
        $d2 = "中国人有中国人的形象，即便是元帝国，占领全中国，让南人为第四等";
        $pic2 = "http://www.phpmaster.cn/usr/uploads/2019/10/3343280148.jpg";
        $url2 = "http://www.phpmaster.cn/index.php/archives/37/";
        $resultStr = sprintf($str, $FromUserName, $ToUserName, $CreateTime, $count, $t1, $d1, $pic1, $url1, $t2, $d2, $pic2, $url2);
        echo $resultStr;
    }

    //发送视频
    public function sendVideo($media_id)
    {
        $str = "<xml>
          <ToUserName><![CDATA[%s]]></ToUserName>
          <FromUserName><![CDATA[%s]]></FromUserName>
          <CreateTime>%s</CreateTime>
          <MsgType><![CDATA[video]]></MsgType>
          <Video>
            <MediaId><![CDATA[%s]]></MediaId>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
          </Video>
        </xml>
        ";
        $ToUserName = $this->postObj->ToUserName;
        // 用户
        $FromUserName = $this->postObj->FromUserName;
        // 用户发送的内容
        // 公众号回复给用户的时间
        $CreateTime = time();
        $Title = "视频";
        $Description = "原路返回的视频";
        $resultStr = sprintf($str, $FromUserName, $ToUserName, $CreateTime, $media_id, $Title, $Description);
        echo $resultStr;
    }

    //   //  1.如果用户发送了视频，获取用户发送视频的media_id
    public function getUserFile($type, $ext)
    {
        $access_token = $this->getAccessToken();
        $MediaId = $this->postObj->MediaId;
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $MediaId;
        $res = curl($url);
        $filename = "./" . $this->postObj->MsgId . $ext;
        //  2.通过media_id 调用获取临时素材接口 把视频下载到本地
        // 保存视频
        $this->saveWeiXinFile($filename, $res);

        $media_id = $this->getMediaId($type, $filename);
        unlink(realpath($filename));
        return $media_id;
    }

    //  //  2.通过media_id 调用获取临时素材接口 把视频下载到本地
    public function saveWeiXinFile($filename, $filecontent)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }

    // 获取access_token
    public function getAccessToken()
    {
        $arr = M('token')->find(1);
        if (time() > $arr['expire_time']) {
            // 过期了
            $access_token = $this->createAccessToken();
        } else {
            $access_token = $arr['access_token'];
        }
        echo $access_token;
        return $access_token;
    }

    // 生成access_token
    public function createAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::APPID . "&secret=" . self::APPSECRET;
        $json_str = curl($url);
        $arr = json_decode($json_str, true);
        $access_token = $arr['access_token'];
        // 更新数据库
        M('token')->where(['id' => 1])->save(['access_token' => $access_token, 'expire_time' => time() + 7000]);
        return $access_token;
    }


    // 用户绑定 用户关注公众号后添加记录
    public function bindNotice()
    {
        $access_token = $this->getAccessToken();
        // 用户
        $FromUserName = $this->postObj->FromUserName;

        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . "$FromUserName" . "&lang=zh_CN";
        $json_str = curl($url);
//        $userInfo = json_decode($json_str,true);
        // 用户信息存储数据表
    }

    // 创建自定义菜单
    public function createMenu()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
        $menu['button'][0]['name'] = '汉服';
//        $menu['button'][0]['sub_button'][0]= ['name'=>'看美女','type'=>'click','key'=>'girl'];
//        $menu['button'][0]['sub_button'][1]= ['name'=>'看视频','type'=>'click','key'=>'video'];
//        $menu['button'][0]['sub_button'][2]= ['name'=>'博客','type'=>'view','url'=>'http://www.phpmaster.cn'];
        $menu['button'][0]['sub_button'][0] = ['name' => '扫一扫', 'type' => 'scancode_push', 'key' => 'saoyisao'];
        $menu['button'][0]['sub_button'][1] = ['name' => '拍照', 'type' => 'pic_sysphoto', 'key' => 'photo'];

        $menu['button'][1]['name'] = '帮助中心';
        $menu['button'][1]['type'] = 'click';
        $menu['button'][1]['key'] = 'help';

        $menu['button'][2]['name'] = '我的博客';
        $menu['button'][2]['type'] = 'view';
        $menu['button'][2]['url'] = 'http://www.phpmaster.cn';
        //中文编码问题
        $json = json_encode($menu, JSON_UNESCAPED_UNICODE);
        $bool = curl($url, $json, 1);
        var_dump($bool);
    }

    // 调用临时素材接口
    public function getMediaId($type, $filename)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $access_token . "&type=$type";

        if (class_exists('\CURLFile')) {
            $data = array('media' => new \CURLFile(realpath($filename)));
        } else {
            $data = array('media' => '@' . realpath($filename));
        }
        $json_str = curl($url, $data, 1);
        $obj = json_decode($json_str);
        $media_id = $obj->media_id;
        return $media_id;
    }

    public function checkWechat()
    {
        //1.接收 由微信服务器转出的参数
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        // 2.将接收的三个值放入数组
        $tmpArr = [self::TOKEN, $timestamp, $nonce];
        // 3.进行字典排序 从小到大 1.2.3. a.b.c
        sort($tmpArr, SORT_STRING);
        // 4.合并成一个字符串
        $tmpArr = implode($tmpArr);
        // 5.sha1加密
        $tmpStr = sha1($tmpArr);
        // 6.加密之后的结果 同 微信传递过来的 signature 比对
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

}