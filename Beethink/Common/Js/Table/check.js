//表格的check操作
function initTableCheck(tableObj)
{
  var that={};
  var tBody=tableObj.getElementsByTagName('tbody')[0];
  that.trNode=tBody.getElementsByTagName('tr');
  that.cNode=$('.checkbox',tBody);
  that.checkAllBtn=$('#checkAll');
  that.num=that.cNode.length;
    
  that.checkNum=0;
  //类样式
  that.styleClass=['bgColor0','bgColor1','select'];
  that.init=function ()
  {
    //给cNode指派对应索引
    for(var i=that.num-1;i>=0;i--)
    {
      that.cNode[i].index=i;
    }
    that.bindEve();
  };
  that.notice=function (type)
  {
    type?++that.checkNum:--that.checkNum;
    //判断是否应该修改全选按钮状态
    if(that.checkNum>=that.num)
    {
      if(!that.checkAllBtn.checked) that.checkAllBtn.checked=true;
    }
    else
    {
      if(that.checkAllBtn.checked) that.checkAllBtn.checked=false;
    }
  };
  that.checkAll=function ()
  {
    for(var i=that.num-1;i>=0;i--)
    {
      that.action(that.cNode[i],1);
    }
    that.checkNum=that.num;
  };
  that.clearAll=function ()
  {
    for(var i=that.num-1;i>=0;i--)
    {
      that.action(that.cNode[i],0);
    }
    that.checkNum=0;
  };
  that.action=function (obj,type)
  {
    var index=obj.index;
    if(type)
    {
     obj.checked=true; 
     that.trNode[index].className=that.styleClass[2];
    }
    else
    {
      obj.checked=false;
      that.trNode[index].className=that.styleClass[index%2];
    }
  };
  that.getChecked=function ()
  {
     var data=[],oTmp={};
     for(var i=0,len=that.num;i<len;i++)
     {
        oTmp=that.cNode[i];
        if(oTmp.checked) data.push(oTmp.value);
     }
     return data.join(',');
  };
  that.bindEve=function (){
    that.checkAllBtn.onclick=function ()
    {
      if(this.checked)
      {
        that.checkAll();
      } 
      else
      {
        that.clearAll();
      } 
    }
    var cNode=null;
    for(var i=that.num-1;i>=0;i--)
    {
      cNode=that.cNode[i];
      cNode.onclick=function ()
      {
        that.action(this,this.checked);
        that.notice(this.checked);
      };
    }
  };
  return that;
};