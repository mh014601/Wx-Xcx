// pages/goods/index/index.js
const app =  getApp();
// 3.获取全局变量到值
var site_url = app.globalData.site_url

Page({

  /**
   * 页面的初始数据
   */
  data: {
    goodsList:[],
    page:1
  },
  jumpde:function(e){
    // 1.js点击绑定参数
    var t = e.target.dataset;
    console.log(t);
    console.log(t.id);
    wx.redirectTo({
      url: '/pages/goods/detail/detail?id='+t.id
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.goodsListData();
  },
  // 显示首页数据
  goodsListData:function(){
    // 2.获取当前对象中data属性到值
    var p = this.data.page
    var that = this;
    wx:wx.request({
      url: site_url+'Home/Index/goodsList',
      data: {
        page:p
      },
      header: {},
      method: 'GET',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        //获取现有数据 goodsList + 最新数据 res.data 赋值给 goodsList
        var g = that.data.goodsList
        for(var i in res.data){
          g.push(res.data[i]);
        }
        that.setData(
          {
            goodsList: g
          }
        );
        
      },
      fail: function(res) {},
      complete: function(res) {},
    })
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
    // console.log(Math.random())
    wx.showLoading({
      title: '加载中...',
    })

    setTimeout(function () {
      wx.hideLoading()
    }, 2000)
    var now_page = this.data.page + 1;
    this.setData({
      page:now_page
    })
    this.goodsListData();
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})