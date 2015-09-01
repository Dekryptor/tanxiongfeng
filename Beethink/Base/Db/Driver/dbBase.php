<?php
abstract class IDB
{
    protected $lastSQL;
    protected $conCount;   
    public function halt($err)
    {
        die($err);
    }
}
?>