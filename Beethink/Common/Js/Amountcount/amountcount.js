/*
  功能:粗略限制文本字数
  
  特点:
    1.统计当前文本数量
    2.支持3种提示状态(normal,yellow,red)
    3.支持动画显示和隐藏
    4.文本超过指定数量,将自动截取有效长度
  
  参数:
    data={
      'oTxt':'文本框对象',
      'oLeft':'统计框',
      'maxNum':'最大输入字符数',
      'numPrompt':'低于n个字符时开始提示,默认为30'
    }
  注意:
    1.该方法将汉字当作一个字符处理
     
 */
function initAmountLimit()
{
  return {
    init:function (data)
    {
      this.numWarn=30;
      this.setParam(data);
      this.oTxt.maxLength=data.maxNum;
      this.bindEve();
    },
    /**
     * 参数设置
     */
    setParam:function (data)
    {
      for(var k in data)
      {
        this[k]=data[k];
      }
    },
    /**
     * 设置显示状态
     */
    setDisplay:function (obj,flag)
    {
      obj.style.display=flag?'block':'none';
    },
    /**
     * 获取字符串长度
     */
    getRelLength:function(str)
    {
      //<summary>获得字符串实际长度，中文3，英文1</summary>
      var relLen= 0, len = str.length, charCode = 0;
      for (var i = 0; i < len; i++) {
          relLen+=str.charCodeAt(i)>128?3:1;
      }
      return relLen;
    },
   
    count:function (len)
    {
      var left=this.maxNum-len;
      
      if(left>this.numWarn)
      {
        this.oLeft.innerHTML='剩余'+left+'个字符';
      }
      else
      {
        this.oLeft.innerHTML='剩余<span style="color:#F00">'+left+'</span>个字符';
      }  
   
    },
    bindEve:function ()
    {
      var that=this;
      this.oTxt.onkeyup = function ()
      {
        that.count(this.value.length);
      };
      this.oTxt.onfocus = function ()
      {
        that.count(this.value.length);
        that.setDisplay(that.oLeft,1);
        animate(that.oLeft,{'opacity':100},300);
        
      };
      this.oTxt.onblur = function ()
      {
        animate(that.oLeft,{'opacity':30},200,function (){
          that.setDisplay(that.oLeft,0);
        });
      };
    }
  }
}
/*
var oAmount=initAmountLimit();
var data={
  'oTxt':$('#text'),
  'oLeft':$('#left-num'),
  'maxNum':10,
  'numWarn':2
};
oAmount.init(data);
*/