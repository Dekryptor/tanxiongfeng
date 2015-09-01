/*隔行分色*/
function initTableLine()
{
  var obj=$('table');
  
  return {
    init:function ()
    {
      var bNode=[],cHead=[];
      this.headClass='thead';
      this.colorRef=['bgColor0','bgColor1'];  //颜色参考
      for(var i=obj.length-1;i>=0;i--)
      {
        cHead=obj[i].getElementsByTagName('thead')[0];
        bNode=obj[i].getElementsByTagName('tbody')[0];
        this.headAction(cHead);
        this.bodyAction(bNode);
      }
    },
    headAction:function (hNode)
    {
      var cNode=hNode.children,i=0,len=0;
      for(i=0,len=cNode.length;i<len;i++)
      {
        cNode[0].className=this.headClass;
      }
    },
    bodyAction:function (bNode)
    {
      var cNode=bNode.children,i=0,len=0;
      for(i=0,len=cNode.length;i<len;i++)
      {
        cNode[i].className=this.colorRef[(i+1)%2];
      }
    }
  }
};
initTableLine().init();