// pages/login/login.js
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },
  formSubmit:function(e){
    var v = e.detail.value;
    var uname = v.uname;
    var upass = v.upass;
    wx:wx.request({
      url: 'http://localhost/thinkphp/index.php/Home/Index/login',
      data: {
        uname:uname,
        upass:upass
      },
      header: {},
      method: 'GET',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        // console.log(res);
        console.log(res.data);
        if(!res.data){
          wx.showModal({
            title: '提示',
            content: '这是一个模态弹窗',
            success(res) {
              if (res.confirm) {
                console.log('用户点击确定')
              } else if (res.cancel) {
                console.log('用户点击取消')
              }
            }
          })
        }else{
          wx.showToast({
            title: '成功',
            icon: 'success',
            duration: 10000,
            success:function(){}
          })
              wx:wx.setStorage({
                        key: 'flag',
                        data: res.data.flag,
                        success:function(res){

                          wx.switchTab({
                              url: '/pages/indexs/indexs',
                            })
                        }

                      })
        }
        

        
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})