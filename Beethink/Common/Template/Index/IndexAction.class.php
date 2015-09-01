<?php
class IndexAction extends Action
{
    public function index()
    {
        $this->assign('hello','hello bee!');

        $this->display();
    }
}
?>