/*
取长补短
*/
var obj={};
var wrap=Class(obj,{
  Create:function (obj)
  {
    this.obj=obj;   
    this.cols=[];        //列宽数据 
    this.rows=[];        //行高数据
    this.cacheData=[];   //统计每个图片高宽信息
    this.w=getAttr(obj,'width');
    this.h=getAttr(obj,'height');
  
    this.eachW=250;      //每一个宽度
    this.spacing=5;      //间距
    this.numCols=(this.w/this.eachW)>>0;
    this.numRows=3;
    this.bindFlag=0;
    this.oMoreBtn=null;
    this.oMoreBtnBox=null;
    this.moreBtnX=(this.w-350)/2>>0;
    this.eachLoadNum=this.numCols*this.numRows;   //每一次加载的数量
  },
  getData:function ()
  {
    
  },
  builtMoreBtn:function (y,flag)
  {
    if(!this.oMoreBtn)
    {
      var oTmp=document.createElement('div');
      css(oTmp,{'width':'350px','height':'35px','position':'absolute','top':'0px','left':this.moreBtnX+'px'});
      oTmp.innerHTML='<input type="button" class="LoadMoreBtn" onclick="getWrapData()" value="加载更多" />';
      this.obj.appendChild(oTmp);
      this.oMoreBtnBox=oTmp;
      this.oMoreBtn=oTmp.children[0];
    }
    this.oMoreBtnBox.style.top=y+'px';
    if(flag)
    {
      this.oMoreBtn.disabled=true;
      this.oMoreBtn.value='没有更多了';
    }
    else
    {
      this.oMoreBtn.value='加载更多';
    }
  },
  setPos:function (obj,x,y)
  {
    css(obj,{'top':y+'px','left':x+'px'});
  },
  init:function (data){
    //计算列信息
    var tmp={};
    for(var i=0,len=this.numCols;i<len;i++)
    {
      tmp={};
      tmp.i=i;
      tmp.h=0;
      this.cols[i]=i*this.eachW+i*this.spacing;
      this.rows.push(tmp);
    }
    this.built(data);
  },
  loadImg:function (cNode,data)
  {
    var d=document,
        i=0,
        len=cNode.length;
   
    //异步加载图片 
    var timer=setInterval(function (){
      if(i+1>len)
      {
        clearInterval(timer);
        return;
      }
      cNode[i].style.background='url('+data[i++].src+') no-repeat center center';
      
    },100);
    if(!this.bindFlag)  this.bindEve();
  },
  calc:function (data,rows,cols)   //每一行 从大到小排列
  {
    var tmpData=[],
        j=0,
        index=0,
        rs=[];
   
    for(var i=0;i<rows;i++)
    {
      tmpData=[];
      for(j=0;j<cols;j++)
      {
        index=i*cols+j;
        if(typeof data[index]=='undefined') break;
        tmpData.push(data[index]);  
      }
      tmpData.sort(function (a,b){return b.h-a.h});
      for(j=0;j<cols;j++)
      {
        rs.push(tmpData[j]);
      }
    } 
    return rs;
  },
  built:function (data){
     var d=document,
         cNode=[],
         y=0,
         j=0,
         rows=this.numRows,
         cols=this.numCols,
         index=0,
         s=0,
        
         oF=d.createDocumentFragment();
     //需要一个取长补短的步骤
     data=this.calc(data,rows,cols);
    
     for(var i=0;i<rows;i++)   //行
     {
        this.rows.sort(function (a,b){return a.h-b.h});   //从小到大
        s=i*cols;
      
        for(j=0;j<cols;j++)
        { 
           index=s+j;
           if(typeof data[index]=='undefined') break;
           y=this.rows[j].h;
           this.rows[j].h+=data[index].h+this.spacing;
           cNode[index]=d.createElement('li');
           css(cNode[index],{'width':this.eachW+'px','height':data[index].h+'px','top':y+'px','left':this.cols[this.rows[j].i]+'px'});
           oF.appendChild(cNode[index]); 
        }
     }
     this.obj.appendChild(oF);
     this.loadImg(cNode,data);
     //显示加载更多按钮
     
     this.builtMoreBtn(this.getMaxHigh(this.rows)+40,0);
  },
  getMaxHigh:function (data)
  {
    var oD=[];
    for(var i=data.length-1;i>=1;i--)
    {
      oD.push(data[i].h);
    }
    return Math.max.apply(this,oD);
  },
  bindEve:function ()    //事件绑定 
  {
    this.bindFlag=1;
    var obj=this;
    //判断是否滚动到底部
    window.onscroll=function(){
		var d=document;
  
    if(getScroll(d).top+getWindowSize(d).h==getScrollWH(d).h)
      getWrapData()
    };
  }
});
/*
wrap.Create($('#wrap')); 
wrap.init(data);
function getWrapData()
{
  wrap.built(data);   
};
*/
