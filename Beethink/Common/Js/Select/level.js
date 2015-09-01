/*
isDisabled 为Boolean值  默认为false
*/
function initSelectTree(obj,data,name,sel,ext,disable)
{
  return {
    init:function ()
    {
      var queue=[],rs={};
      this.num=data.length;
      this.obj=obj;
      this.midImg='┣'; 
      this.name=name;
      this.lastImg='┗';
      this.ext=typeof ext=='undefined'?'':'<option value="">'+ext+'</option>';
      this.sel=typeof sel=='undefined'?'':sel;
      this.notLastImg='┃';
      this.dis=typeof disable=='undefined'?'':' disabled';
      this.space='&nbsp;&nbsp;';
      
      this.data=this.initData(data);
      this.gInfo=this.getGroupInfo(data);
      this.getSelData(this.gInfo[0],queue);
      this.decorate(this.data,queue);
    },
    //建立id关联
    initData:function (data)
    {
      var rs=[],i=0;
      for(i=this.num-1;i>=0;i--)
      {
        rs[parseInt(data[i].id)]=data[i];
      }
      return rs;
    },
    //获取分组信息
    getGroupInfo:function (data)
    {
      var group=[],cPid=0;
      for(var i=0,len=this.num;i<len;i++)
      {
        cPid=data[i].pid;
        if(typeof group[cPid]=='undefined') group[cPid]=[];
        group[cPid].push(data[i].id);
      }
      return group;
    },
    //获取数据
    getSelData:function(idArr,queue)
    {
      var i=0,cId=0,len=idArr.length;
      for(i;i<len;i++)
      {
        cId=idArr[i];
        //判断当前节点是否含有子节点
        this.data[cId].isParent=(typeof this.gInfo[cId]=='undefined')?0:1;
        this.data[cId].isLast=(typeof idArr[i+1]=='undefined')?1:0;
        queue.push(cId);
        if(typeof this.gInfo[cId]!='undefined') this.getSelData(this.gInfo[cId],queue);
      }
    },
    //装饰
    decorate:function (data,queue)
    {
      var htmlArr=[],cId=0;
      htmlArr.push('<select name="'+this.name+'" id="'+this.name+'"'+this.dis+'>');
      htmlArr.push(this.ext);
      for(var i=0,len=queue.length;i<len;i++)
      {
        cId=queue[i];
        if(this.sel!=data[cId].v)
        {
          htmlArr.push('<option value="'+data[cId].v+'">'+this.getIcon(cId)+data[cId].c+'</option>');  
        }
        else
        {
          htmlArr.push('<option selected value="'+data[cId].v+'">'+this.getIcon(cId)+data[cId].c+'</option>');
        }
      }
      htmlArr.push('</select>');
     
      this.obj.innerHTML=htmlArr.join('');
    },
    //获取内容前缀标识
    getIcon:function (id)
    {
      var cId=0,
          cPid=this.data[id].pid,
          str='';
      str=this.data[id].isLast?this.lastImg:this.midImg;
      while(cPid!=0)
      {
        str=this.data[cPid].isLast?this.space+str:this.notLastImg+str;
        cPid=this.data[cPid].pid;  
      }
      return str;
    }
  }
};
/*
var data=[
  {'v':'1','c':'1','s':0,'id':1,'pid':0},
  {'v':'2','c':'2','s':0,'id':3,'pid':1},
  {'v':'3','c':'3','s':0,'id':2,'pid':1},
  {'v':'4','c':'4','s':0,'id':5,'pid':2},
  {'v':'5','c':'5','s':0,'id':4,'pid':3},
  {'v':'6','c':'6','s':0,'id':6,'pid':4},     
  {'v':'7','c':'7','s':0,'id':7,'pid':3},
  {'v':'8','c':'8','s':0,'id':8,'pid':1}
];
initSelectTree(data,'beeSelect');
*/