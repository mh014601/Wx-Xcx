// pages/goods/search/search.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods_list: []
  
  },
  formSubmit: function (e) {
   var that = this;
   console.log(this);
    var v = e.detail.value;
    var goods_name = v.goods_name;

    // 发起request请求
    wx.request({
      url: 'http://localhost/thinkphp/index.php/Home/Index/xcx_goods_search', //仅为示例，并非真实的接口地址
      data: {
        goods_name: goods_name,
      },
      method: 'post',
      header: {
        'content-type': 'application/x-www-form-urlencoded' // 默认值
      },
      success(res) {
        console.log(res);
        that.setData({
          goods_list:res.data
        });

        } 
      
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