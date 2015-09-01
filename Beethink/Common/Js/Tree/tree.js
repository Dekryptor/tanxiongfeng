function tree()
{
    return {
        init:function (data,param,reduce)
        {
            this.groupData=[];
            this.referData={};
            this.length=data.length;
            this.order=[];   //顺序记录
            
            return this.getLevelData(0);
        },
        
        getLevelData:function (id)
        {
            var tmpId=0;
            
            if(id!=0)
            {
                this.referData[id]['isLast']=this.isLast(id);
                this.referData[id]['isParent']=id==0?1:this.hasChild(id);
                this.order.push(id); 
            }
            
            for(var i=0,len=this.groupData[id].length;i<len;i++)
            {
                tmpId=this.groupData[id][i]['id'];
              
                if(this.hasChild(tmpId))
                {
                    this.getLevelData(tmpId);
                }
                else
                {
                    this.referData[tmpId]['isLast']=this.isLast(tmpId);
                    this.referData[tmpId]['isParent']=0;
                    this.order.push(tmpId);                                     
                }
            }
        },
        getReferData:function (data)
        {
            for(var i=0,len=data.length;i<len;i++)
            {
                this.referData[data[i]['id']]=data[i];
            }
        },
        isLast:function (id)
        {
            var pid=this.referData[id]['pid'];
            
            if(typeof this.groupData[pid]=='undefined')
            {
                return true;
            }
            var len=this.groupData[pid].length;
            return this.groupData[pid][len-1]['id']==id?1:0;
        },
        hasChild:function (id)
        {
            return typeof this.groupData[id]=='undefined'?0:1;
        },
        getGroupData:function (data)
        {
            var pid=0;
            for(var i=0,len=data.length;i<len;i++)
            {
                pid=data[i]['pid'];
                
                if(typeof this.groupData[pid]=='undefined')
                {
                    this.groupData[pid]=[];
                }
                
                this.groupData[pid].push(data[i]);
            }
        }  
    }   
}

/* 
var data=[
    {'id':1,'pid':0},
    {'id':2,'pid':1},
    {'id':3,'pid':2},
    {'id':4,'pid':3},
    {'id':5,'pid':1},
    {'id':6,'pid':2},
    {'id':7,'pid':3},
    {'id':8,'pid':1},
    {'id':9,'pid':2}
];
var oTree=new tree();
oTree.init(data);
*/