function initSlideDown()
{
  var that={};
  that.cNode=$('.slideDown');
  that.preNode=null;
  that.topZindex=201;
  that.init=function ()
  {
     that.bindEve();  
  };
  that.action=function (obj)
  {
    if(that.preNode)
    {
      that.setDisplay(that.preNode,0);
    }
    if(that.preNode==obj)
    {
      that.preNode=null;
      return;
    }
    that.setDisplay(obj,1);
    that.preNode=obj; 
  };
  that.bindEve=function ()
  {
    var oTmp={};
    for(var i=0,len=that.cNode.length;i<len;i++)
    {
      oTmp={};
      oTmp=that.cNode[i];
      oTmp.timer=0;
      oTmp.flag=1;
      oTmp.children[0].onclick=function ()
      {
        that.action(this.parentNode);
      };
      oTmp.onmouseover=function ()
      {
        this.flag=1;
      };
      oTmp.onmouseout=function ()
      {        
        var i=0,pNode=this;
        pNode.flag=0;
        clearInterval(pNode.timer);
        pNode.timer=setInterval(function (){
          if(i>0)
          {
            if(!pNode.flag)
            {
              that.setDisplay(pNode,0);
              that.preNode=null;
            }
            clearInterval(pNode.timer);
          }
          else
          {
            ++i;
          }
        },15);        
      };
    }
  };
  that.setDisplay=function (obj,type)
  {
    if(type)
    {
      obj.style.zIndex=that.topZindex;
      obj.children[1].style.display='block';  
    }
    else
    {
      obj.children[1].style.display='none';
      obj.style.zIndex=0;
    }
  };
  return that;
};