<?php
class Action
{
   private static $view;    //视图实例对象
   public function __construct()
   {
      View::init();
   }
   protected function display($tpl='',$data=array())
   {
        View::display($tpl,$data);
   }
   //模板变量赋值
   protected function assign($k,$v,$order=0)
   {
        View::assign($k,$v,$order);
   }
   public function layout($tpl='')
   {
      return View::layout($tpl);
   }
   /*
    * layout 渲染变量
    * */
    public function l_assign($k,$v,$order='')
    {
        View::l_assign($k,$v,$order);
    }
   //成功页面跳转
   protected function success($msg,$url)
   {
        header('Location:./Exception_success.php?msg='.$msg.'&url='.$url);
   }
   //失败页面跳转
   protected function error($msg)
   {
        header('Location:./Exception_error.php?msg='.$msg.'&url='.$url);
   }
   
};