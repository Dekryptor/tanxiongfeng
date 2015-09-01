//搜索栏
function initSlideSearch()
{
  var that={};
  
  that.status=0;      //0=>不显示状态 1=>显示状态
  that.init=function ()
  {
    var obj=$('#searchSlide');
    that.closeBtn=$('#sclose');
    that.sBtn=$('#sTxt');
    that.oSearch=$('#searchCon');
    that.bindEve();
  };
  that.action=function (flag)
  {
    that.sBtn.className='slideTxt '+(flag?'sUp':'sDown');
    that.setDisplay(that.oSearch,flag);
    that.status=flag;
  };
  that.setDisplay=function (obj,type)
  {
    obj.style.display=type?'block':'none';
  };
  that.bindEve=function ()
  {
    that.closeBtn.onclick=that.sBtn.onclick=function (){
      that.action(that.status?0:1);
    };
  };
  return that;
};