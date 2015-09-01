<?php
/**
 * Created by PhpStorm.
 * User: cong
 * Date: 2015/8/23 0023
 * Time: 15:40
 */
class UcenterMemberAction
{
    /*eidt the user info*/
    public function edit()
    {
        View::display();
    }
    /*change the password*/
    public function modifyPassword()
    {
        View::assign('scripts',array('core.Bracket.bootstrapValidator#min'));
        View::assign('styles',array('core.Bracket.bootstrapValidator#min'));

        View::display();
    }
}