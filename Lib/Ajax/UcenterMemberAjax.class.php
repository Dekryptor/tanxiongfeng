<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/23 0023
 * Time: 18:50
 */
class UcenterMemberAjax
{
    /*判断当前密码与原密码是否一致*/
    public function modifyPassword()
    {
        Sys::S('core.Verify.Input');
        $data=array(
            'src_password'=>array(null,'string','','原密码不能为空'),
            'new_password'=>array(null,'string','','新密码不能为空'),
        );
        $data=Input::dataFilter($data,'post');

        if(md5($data['src_password'])!=$_SESSION['userinfo']['password'])
        {
            Error::halt(FAIL,'原密码不正确');
        }
        else
        {
            if($data['src_password'] == $data['new_password'])
            {
                Error::halt(FAIL,'新密码不能与原密码一致');
            }
            else
            {
                Sys::D('UcenterMember');
                UcenterMemberModel::savePassword($_SESSION['userinfo']['id'],$data['new_password']);

                Error::halt(SUCCESS,'操作成功');
            }
        }
    }
}