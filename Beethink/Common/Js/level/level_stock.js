/*
1.功能:
  1)对无序节点实现分级处理
  2)动态生成html
  3)尺寸更具窗口大小变化而自适应
  4)缓存数据 默认为 20 天
  5)实现模拟点击  当模拟点击某叶子节点时 自动展开对应父级节点
  6)对于当前选中的节点 附加 选中效果(字体加粗)
  
2.注意:
  1)认为 id=1 && pid=0 的节点为根节点 必须设置根节点
  2)reduce 是需要减去的高度

3.优点:
  1)效率高
*/
function initLevelTree(data,id,reduce)
{
  var that={};
  that.name=name;
  that.reduce=reduce?parseInt(reduce):0;
  that.num=data.length;
  
  that.curIndex=0;
  that.rootId=0;
  that.preNode=null;
  that.srcNode=null;
  that.data=[];        //节点综合信息 
  that.cacheNode={};   //缓存的节点
  that.cookieFlag=true;   //cookie是否有效
  that.obj=$('#'+id);
  that.rootNum=0;
  
  that.init=function ()
  {
    that.getSelectTree(data);
  };
  that.initData=function (data)
  {
    for(var i=0,len=data.length;i<len;i++)
    {
        data[i]['id']=parseInt(data[i]['id']);
        data[i]['pid']=parseInt(data[i]['pid']);
    }
  },
  /* 获取树状结构  */
  that.getSelectTree=function (data)
  {
      /* 调整顺序 */
      var gInfo=[],queue=[],rs={};
      //建立id关联
      that.data=that.initData(data);
      rs=that.getCookie(that.data);

      if(!rs)  //无缓存
      { 
        that.cookieFlag=false;
        //pid分组
        gInfo=that.getGroupInfo(data);
        that.gInfo=gInfo; 
        //id->[isParent,isLast,level]
        that.getTreeData(gInfo[that.rootId],queue,gInfo);
      }
      else
      {
        queue=rs.queue;
        that.sel=rs.sel;
        that.rootNum=rs.rootNum;
      }
      that.decorate(that.data,queue);  
      
  };
  /*循环生成html*/
  that.getHTML=function (queue)
  {
    //ext = 状态_是否为尾节点_是否为1级节点_当前索引(从零开始)_父节点 (状态默认为1 即显示状态)
    var i=1,j=0,curPid=0,cur={},htmlArr=[],len=queue.length;
    var str='';
    for(i;i<len;i++)
    {
      cur=that.data[queue[i]];
      if(cur.isParent)
      {          
         ++j;
         if(cur.pid==1)  //遇到一个一级节点 则复位 层级 level
         {
           curPid=cur.id;
           htmlArr.push('<div class="treeroot"><span id="dd_'+cur.id+'" class="tnode '+(curPid==that.sel?'down':'normal')+'" flag="true" ext="'+(curPid==that.sel?1:0)+'_'+cur.isLast+'_1_'+j+'_'+cur.pid+'">'+cur.c+'</span></div><div id="dt_'+j+'" class="nodeBox '+(curPid==that.sel?'show':'hide')+'">');
         }
         else
         {
           htmlArr.push('<div class="treelist">'+that.getIcon(cur.id,curPid)+'<a class="tlink" id="dd_'+cur.id+'" ext="1_'+cur.isLast+'_0_'+j+'_'+cur.pid+'">'+cur.c+'</a></div><div id="dt_'+j+'" class="childNode show">'); 
         }
      }
      else
      {
        htmlArr.push('<div class="treelist">'+that.getIcon(cur.id,curPid)+'<a id="dl_'+cur.pid+'" class="tlink" href="javascript:;">'+cur.c+'</a></div>');
        if(cur.isLast)
        {
          str=that.getTail(cur.pid);
          htmlArr.push(str);
        } 
      }
    }
    return htmlArr.join('');
  };
  //实现模拟点击  循环展开父节点
  that.modifyClick=function (obj)
  {
    var pNode={}, 
        id='',
        pid=0,
        data=[];
    
    id=obj.id.split('_');
    pid=id[1];
    while(pid!=0)
    {
      pNode=that.getElem('dd_'+pid);
      data=pNode.getAttribute('ext').split('_');
      if(data[0]=='0')
      {
       data[0]='0'; 
       pNode.setAttribute('ext',data.join('_'));
       that.action(pNode);
      }
      pid=data[4];
    }
  };
  /*获取尾巴*/
  that.getTail=function (pid)
  {
    var htmlArr=[],flag=false;
    if(!that.data[pid].isLast) return '</div>';
    while(that.data[pid].pid!=0)
    {
      if(that.data[pid].isLast){
        htmlArr.push('</div>');
      } 
      else{
        htmlArr.push('</div>');
        break;
      }
      pid=that.data[pid].pid; 
    }
    return htmlArr.join('');
  };
  /*根据id获取节点对象*/
  that.getElem=function (id)
  {
    if(typeof that.cacheNode[id]=='undefined')
    {
      that.cacheNode[id]=$('#'+id);
    }
    return that.cacheNode[id];
  };
  /*
  装饰
  v=>1  c=>1  s=>0  id=>1  pid=>0  isParent=>1  isLast=>1  
  */
  that.decorate=function (data,queue)
  {
    that.obj.innerHTML=that.getHTML(queue);
    that.resizeHeight(winSize().h);
    that.preNode=that.obj.children[0].children[0];
    that.bindEve();
    //缓存数据
    if(!that.cookieFlag) that.setCookie(data,queue);
  };
  //数据初始化
  that.initData=function (data)
  {
    var rs=[],i=0,tmpId;
  
    for(i=data.length-1;i>=0;i--)
    {
      tmpId=parseInt( data[i].id );
      if(typeof rs[tmpId]=='undefined')
      {
        rs[tmpId]={};
      }
      rs[tmpId]=data[i];  
      
    }
    return rs;
  };
  //推算层级数据
  that.getTreeData=function (idArr,queue,gInfo)
  {
    var i=0,cId=0,len=idArr.length;
    if(!this.hasOwnProperty('gInfo')) this.gInfo=gInfo;
    for(i;i<len;i++)
    {       
       cId=idArr[i];
       //判断当前节点是否含有子节点 //id->[isParent,isLast]
       that.data[cId].isParent=(typeof this.gInfo[cId]=='undefined')?0:1;
       that.data[cId].isLast=(typeof idArr[i+1]=='undefined')?1:0;
      
       queue.push(cId);
       if(typeof this.gInfo[cId]!='undefined') that.getTreeData(this.gInfo[cId],queue);
    }
  };
  //分组信息 
  that.getGroupInfo=function (data)
  {
    var group=[],cPid=0;
    for(var i=0,len=that.num;i<len;i++)
    {
      cPid=data[i].pid;
      if(typeof group[cPid]=='undefined') group[cPid]=[]; 
      group[cPid].push(data[i].id);
    }
    that.sel=group[1][0];
    that.rootNum=group[1].length;
    return group;
  }; 
  //动作
  that.action=function (obj)
  { //ext = 状态_是否为尾节点_是否为1级节点_当前索引(从零开始) (状态默认为1 即显示状态)
    var data=[],tmpData=[];
  
    //需判断当前节点状态
    data=obj.getAttribute('ext').split('_');
    data[0]=data[0]=='1'?'0':'1';                   //状态切换
    
    if(data[2]=='1')
    {
      if(that.preNode==obj) return;
      tmpData=that.preNode.getAttribute('ext').split('_');
      tmpData[0]='0';
      that.pnodeAction(that.preNode,tmpData);
    
      that.preNode=obj;
      
      that.pnodeAction(obj,data);
    }
    else
    {
      that.cnodeAction(obj,data);
    }
  };
  //一级节点动作处理
  that.pnodeAction=function (obj,data)
  {
    var cNode={};
    cNode=that.getElem('dt_'+data[3]);
    obj.setAttribute('ext',data.join('_'));
    obj.className=(data[0]=='1'?'tnode down':'tnode normal');
    cNode.style.display=(data[0]=='1'?'block':'none');
  };
  //非一级节点动作处理
  that.cnodeAction=function (obj,data)
  {
    var node=obj.parentNode.children,
        cNode_1=node[node.length-2],
        cNode_2=node[node.length-3],
        cNode=that.getElem('dt_'+data[3]),
        cls=[];
   
    obj.setAttribute('ext',data.join('_'));    //更新状态
    //更新状态
    if(data[0]=='1')    //显示状态
    {
      cls[0]=parseInt(data[1])?'minusbottom':'minus';
      cls[1]='folderopen';
    }
    else
    {
      cls[0]=parseInt(data[1])?'plusbottom':'plus';
      cls[1]='folder';
    }
    cNode_1.className='icon '+cls[1];
    cNode_2.className='icon '+cls[0];
    cNode.style.display=data[0]=='1'?'block':'none';
  };
  /*事件绑定*/
  that.bindEve=function ()
  {
    that.obj.onclick=function (e)
    {
      e=e||window.event;
      var target=e.target || e.srcElement;
      stopPropagation(e);    //阻止冒泡
      switch(target.tagName)
      {
      case 'A':
        if(target.getAttribute('ext'))
        {
          that.action(target); 
        }
        that.setSelDisplay(target);
        return;
      case 'SPAN':      
        if(target.getAttribute('ext')=='switch'){
          var node=target.parentNode.children;   //获取当前节点的父节点
          target=node[node.length-1];
        }
        that.action(target);
        that.setSelDisplay(target);
        return;
      default:;
      }; 
    };
    //调整当前选中对象的显示状态
    that.setSelDisplay=function (obj)
    {
      if(that.srcNode) that.srcNode.style.fontWeight=400;
      obj.style.fontWeight=800;
      that.srcNode=obj;
    };
    //自适应窗口
    bind(window,'resize',function (){
      that.resizeHeight(winSize().h)
    });
  };
  //根据当前窗口高度调整 nodebox 高度
  that.resizeHeight=function (winH)
  {
    that.height=winH-30*that.rootNum-that.reduce;
    //获取.nodeBox节点 并 更新height属性
    if(this.nodebox==null)
    {
      this.nodebox=$('.nodeBox',that.obj);
    }
    for(var i=this.nodebox.length-1;i>=0;i--)
    {
      this.nodebox[i].style.height=that.height+'px';
    }
  };
  //缓存计算好的数据   缓存20天
  that.setCookie=function (data,queue)
  {
    var dataArr=[],cur={};
    for(var k in data)
    {
      cur=data[k];
      dataArr.push(cur.id+'_'+cur.isParent+'_'+cur.isLast);
    }
    setCookie('tree_sel',that.sel,480);
    setCookie('tree_rootNum',that.rootNum,480);
    setCookie('tree_data',dataArr.join('~'),480);
    setCookie('tree_queue',queue.join('_'),480);

  };
  //获取缓存内容
  that.getCookie=function (data)
  {
    var tmp=[],
        rs={},
        tree_sel=getCookie('tree_sel');
        tree_rootNum=getCookie('tree_rootNum');
        treeData=getCookie('tree_data'),
        tree_queue=getCookie('tree_queue');
    //如果两者任意一个为空 则数据缺省
    if(!treeData || !tree_queue || !tree_rootNum || !tree_sel) return false;
   
    treeData=treeData.split('~');
    
    for(var i=0,len=treeData.length;i<len;i++)
    {
       tmp=[];
       tmp=treeData[i].split('_');
       data[tmp[0]].isParent=parseInt(tmp[1]);
       data[tmp[0]].isLast=parseInt(tmp[2]);
    }
    rs.queue=tree_queue.split('_');
    rs.sel=parseInt(tree_sel);
    rs.rootNum=parseInt(tree_rootNum);
    return rs;
  };
  /* 获取对应图标*/
  that.getIcon=function (id,pid)
  {
    var cPid=that.data[id].pid,str='',cur={},cls=[];
    cur=that.data[id];
    //判断一下当前节点是否为尾节点
    if(cur.isParent)
    {
      cls=cur.isLast?['minusbottom','folderopen']:['minus','folderopen'];
    }
    else
    {
      cls=cur.isLast?['joinbottom','page']:['join','page'];
    }
    str='<span ext="switch" class="icon '+cls[0]+'"></span><span class="icon '+cls[1]+'"></span>';
    while(cPid!=pid)
    {
      str=that.data[cPid].isLast?'<span class="icon empty"></span>'+str:'<span class="icon line"></span>'+str;
      cPid=that.data[cPid].pid;   
    }
    return str;
  };
  return that;
};