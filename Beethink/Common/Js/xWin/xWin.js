/*
xWin对话框管理

支持Alert,Prompt,Loading,Complete,SetLoadTxt,Open 方式
*/
var xWin={
  init:function ()
  {
    xWin.oBaseIndex=1000;
    xWin.oBaseStock=null;
    xWin.oDiagStock=null;
    xWin.fend=null;
    xWin.oDiagChild=[];
    xWin.diagW=350;
    xWin.diagH=180;
    xWin.oWnd=[];
    xWin.diagFlag=false;  //对话框标识
    xWin.oWndModel=null;  //弹出窗口模型
  },
  builtBaseStock:function (d)
  {
    if(!xWin.oBaseStock)
    {
      xWin.init();
      var oDiv=d.createElement('div');
      oDiv.className='oBaseStock';
      xWin.oBaseStock=oDiv;  
      d.body.appendChild(oDiv);
    }
    xWin.oBaseStock.style.zIndex=xWin.getIndex()+1;
    xWin.setDisplay(xWin.oBaseStock,1);
  },
  buildDiagStock:function (d)
  {
    var x=0,y=0,size=null,z=0;
    if(!xWin.oDiagStock)
    {
      var oDiv=d.createElement('div');      
      oDiv.className='oDiagStock';    
      css(oDiv,{'width':xWin.diagW+'px','height':xWin.diagH+'px'});
      oDiv.innerHTML='<p class="pa xWinTitle"></p><p class="xWinIcon"></p><p class="pa xWinCon"></p><p class="pa xWinBtn"></p>';
      drag(oDiv);
      xWin.oDiagChild=oDiv.getElementsByTagName('P');
      xWin.oDiagStock=oDiv;
      d.body.appendChild(oDiv);
    }
    z=xWin.getIndex()+2;
   
    if(!xWin.diagFlag)
    {
      xWin.oWnd.push(z);
    }
    xWin.diagFlag=true;
    xWin.oDiagStock.style.zIndex=z;
    size=winSize();
    /*获取当前中心点,并对oDiagStock重新定位*/
    x=(size.w-xWin.diagW)/2>>0;
    y=(size.h-xWin.diagH)/2>>0;
    xWin.setPos(xWin.oDiagStock,x,y);
    xWin.setDisplay(xWin.oDiagStock,1);
  },
  getIndex:function ()
  {
    var len=xWin.oWnd.length;
    return len==0?xWin.oBaseIndex:xWin.oWnd[len-1];
  },
  setPos:function(obj,x,y)
  {
    css(obj,{'top':y+'px','left':x+'px'});
  },
  complex:function (d,fend)
  {
    xWin.builtBaseStock(d);
    xWin.buildDiagStock(d);
    try{xWin.oDiagChild.length;}
    catch(e){xWin.oDiagChild=xWin.oDiagStock.getElementsByTagName('P')}
    xWin.fend=function ()
    {
      xWin.setDisplay(xWin.oDiagStock,0);   //隐藏窗口 
      xWin.diagFlag=false;
      xWin.oWnd.pop();
      xWin.close();
      if(fend) fend();
    }
  },
  closePrompt:function ()
  {
    xWin.setDisplay(xWin.oDiagStock,0);   //隐藏窗口 
    xWin.diagFlag=false;
    xWin.oWnd.pop();
    xWin.close();
  },
  //关闭
  close:function ()
  {
    var len=xWin.oWnd.length;
    if(len==0)
    {
      xWin.oBaseStock.style.zIndex=xWin.oBaseIndex;
      xWin.setDisplay(xWin.oBaseStock,0);
    }
    else
    {
      xWin.oBaseStock.style.zIndex=xWin.getIndex()-1;
    }
  },
  /* 模式 */
  Alert:function (msg,fend)
  {
    xWin.complex(document,fend);
    xWin.oDiagChild[0].innerHTML='提示';
    xWin.oDiagChild[1].className='xWinIcon xWinAlert';
    xWin.oDiagChild[2].innerHTML=msg;
    xWin.oDiagChild[3].innerHTML='<input type="button" class="xWinBtn xWinAlertIcon" value="确定" onclick="xWin.fend()" />';
    //自动获取焦点
    xWin.oDiagChild[3].children[0].focus();
   
  },
  Prompt:function (msg,fend)
  {
    xWin.complex(document,fend);
    xWin.oDiagChild[0].innerHTML='提示';
    xWin.oDiagChild[1].className='xWinIcon xWinPrompt';
    xWin.oDiagChild[2].innerHTML=msg;
    xWin.oDiagChild[3].innerHTML='<input type="button" class="xWinBtn xWinPromptIcon" value="确定" onclick="xWin.fend()"  /><input type="button" class="xWinBtn xWinPromptIcon" value="取消" onclick="xWin.closePrompt()" />';
    xWin.oDiagChild[3].children[0].focus();
  },
  Loading:function (msg,msg2,fend)
  {
    xWin.complex(document,fend);
    xWin.oDiagChild[0].innerHTML='提示';
    xWin.oDiagChild[1].className='xWinIcon xWinLoading';
    xWin.oDiagChild[2].innerHTML=msg;
    (msg2)||(msg2='Loading')
    xWin.oDiagChild[3].innerHTML=msg2;
  },
  SetLoadingTxt:function (msg)
  {
    xWin.oDiagChild[2].innerHTML=msg;
  },
  Complete:function (msg)
  {
    xWin.SetLoadingTxt(msg);
    var timer=null,i=0;
    timer=setInterval(function (){
      if(i>0)
      {
        xWin.fend();
        clearInterval(timer);
      }
      else
      {
        ++i;
      }
    },500);
  },
  /*窗口创建*/
  CreateWin:function (winName,width,height,fend)
  {
    var size=null,x=0,y=0,d=document;
    var oDiv=null,z=0,len=0;
    //设置遮罩层
    xWin.builtBaseStock(d);
    if(!xWin.oWndModel)
    {
      var d=document;
      var oDiv=d.createElement('div');
      oDiv.className='winStock';
      oDiv.innerHTML='<div class="winTopStock"><span class="winTitle"></span><span id="winClosebtn" class="winClosebtn"></span></div><iFrame class="winFrame" src="" />';    
      xWin.oWndModel=oDiv;
    }
    else
    {
       oDiv=xWin.oWndModel.cloneNode(true);
    }
    z=xWin.getIndex()+2;
    xWin.oWnd.push(z);
    size=winSize();
    css(oDiv.children[1],{'height':height-30+'px'});
    x=(size.w-width)/2>>0;
    y=(size.h-height)/2>>0;
    css(oDiv,{'left':x+'px','top':y+'px','width':width+'px','height':height+'px','zIndex':z});
    oDiv.id=winName;
    return oDiv;
  },
  /*
    弹窗处理
    参数:窗口名称,窗口标题,宽,高,回调函数
  */
  Open:function (winName,title,src,width,height,fend)
  {
    var oDiv=xWin.CreateWin(winName,width,height,fend);
    var oNode=oDiv.children;
   
    drag(oNode[0],oDiv,oNode[1]);
    oNode[0].children[0].innerHTML=title;
    
    xWin.closeWin=function ()
    {
      //移除当前窗口
      document.body.removeChild(oDiv);
      xWin.oWnd.pop();
      xWin.close();
      if(fend) fend();
      xWin.closeWin=null;
    };
    oNode[0].children[1].onclick=function ()
    {
      xWin.closeWin();
    };
    oNode[1].src=src;
    document.body.appendChild(oDiv);
  },
  /* end 模式 */
  setDisplay:function (obj,flag)
  {
    obj.style.display=flag?'block':'none';
  }
};