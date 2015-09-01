function message()
{
    return {
        init:function (node)
        {
            this.node=node;
            this.timer=null;
            this.dataRefer={
                wrong:{
                    className:'alert alert-warning',
                    tip:'提示! '
                },
                success:{
                    className:'alert alert-success',
                    tip:'提示! '
                },
                warn:{
                    className:'alert alert-danger',
                    tip:'提示! '
                }
            }
            this.interval=5;
        },
        /*设置显示状态*/
        setDisplay:function  (obj,status)
        {
            $(obj).css('display',status?'block':'none');
        },
        /*装饰*/
        decorate:function (data,msg)
        {
            $(this.node).attr('class',data.className);
            $(this.node).html('<a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.tip+'</strong>'+msg);
        },
        /*定时器*/
        showInterval:function (callback)
        {
            var index=0;
            var that=this;
            this.setDisplay(this.node,1);

            if(this.timer){clearTimeout(this.timer);}
            clearInterval(this.timer);
            this.timer=setTimeout(function (){

                that.setDisplay(that.node,0);
                clearInterval(that.timer);
                if(callback){callback();}
            },this.interval*1000);

        },
        /*隐藏*/
        hide:function ()
        {
            if(this.timer)
            {
                clearTimeout(this.timer);
            }

            this.setDisplay(this.node,0);
        },
        /*错误*/
        wrong:function (msg,callback,ifInterval)
        {
            if(typeof(ifInterval) == 'undefined'){ifInterval=true;}

            this.decorate(this.dataRefer.wrong,msg);
            if(ifInterval)
            {
                this.showInterval(callback);
            }
            else
            {
                this.setDisplay(this.node,1);
            }
        },
        /*成功*/
        success:function (msg,callback,ifInterval)
        {
            if(typeof(ifInterval) == 'undefined'){ifInterval=true;}

            this.decorate(this.dataRefer.success,msg);
            if(ifInterval)
            {
                this.showInterval(callback);
            }
            else
            {
                this.setDisplay(this.node,1);
            }
        },
        /*warn*/
        warn:function (msg,callback,ifInterval)
        {
            if(typeof(ifInterval) == 'undefined'){ifInterval=true;}
            this.decorate(this.dataRefer.warn,msg);
            if(ifInterval)
            {
                this.showInterval(callback);
            }
            else
            {
                this.setDisplay(this.node,1);
            }
        }
    }
}