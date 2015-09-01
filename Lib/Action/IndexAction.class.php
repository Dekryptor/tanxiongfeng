<?php
class IndexAction
{
    static function index()
    {
        $treeData=array(
            array('id'=>1,'pid'=>0,'name'=>'bee1','age'=>'12','href'=>'javascript:;','html'=>'test1','icon'=>'fa fa-home'),
            array('id'=>2,'pid'=>1,'name'=>'bee2','age'=>'13','href'=>'javascript:;','html'=>'test2','icon'=>'fa fa-home'),
            array('id'=>3,'pid'=>2,'name'=>'bee3','age'=>'14','href'=>'javascript:;','html'=>'test3','icon'=>'fa fa-home'),
            array('id'=>4,'pid'=>1,'name'=>'bee4','age'=>'15','href'=>'javascript:;','html'=>'test4','icon'=>'fa fa-home'),
            array('id'=>8,'pid'=>2,'name'=>'bee8','age'=>'16','href'=>'javascript:;','html'=>'test5','icon'=>'fa fa-home'),
            array('id'=>6,'pid'=>4,'name'=>'bee6','age'=>'17','href'=>'javascript:;','html'=>'test6','icon'=>'fa fa-home'),
            array('id'=>7,'pid'=>1,'name'=>'bee7','age'=>'18','href'=>'javascript:;','html'=>'test7','icon'=>'fa fa-home'),
        );


        Sys::S('core.Html5.Menu.LeftMenu');
        $data = LeftMenu::getMenu($treeData,'id','pid','href','html','icon');

        View::assign('userinfo',$_SESSION['userinfo']);
        View::assign('leftMenu',$data);

        View::display();
    }
    /*主页*/
    static function mainpannel()
    {
        $date=date('Y-m-d',NOW);

        View::assign('date',$date);

        View::display();
    }
}