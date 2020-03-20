// pages/test/test.js
// 返回app.js 文件中的所有数据
const app = getApp();
// console.log(app.globalData.classname) // I am global data


Page({

  /**
   * 页面的初始数据
   */
  data: {
    "bglist": ["/images/bg1.jpg", "/images/bg2.jpg", "/images/bg3.jpg","/images/bg4.jpg"],
    "info":[
      {"name":"张三"},
      {"name":"李四"}
      ],
    "sex":2,
    "name":'公主殿下',
    "classname": app.globalData.classname,
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    interval: 2000,
    duration: 500
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