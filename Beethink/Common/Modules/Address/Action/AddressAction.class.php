<?php
class AddressAction
{
    /**
     * 获取用户地址列表
     */
    public function getAddr()
    {
        codecheck();
        
        $rs=D('Address')->getAddr($_SESSION['userinfo']['id']);
        returnJson(SUCCESS,$rs);
    }
    /**
     * 添加地址
     * 
     */
    public function addAddr()
    {
        codecheck();
        
        $data=array(
            array('addr','string'),
            array('name','string'),
            array('phone','string')
        );
        dataFilter($data,'post');
        
        $inData=array(
            'cust_id'=>$_SESSION['userinfo']['id'],
            'addr'=>$data['addr'],
            'name'=>$data['name'],
            'phone'=>$data['phone']
        );
        
        D('Address')->insert($inData);
        
        returnJson(SUCCESS,'','操作成功');
    }
    /**
     * 地址修改
     */
    public function alterAddr()
    {
        codecheck();
        
        $data=array(
            array('addr','string'),
            array('name','string'),
            array('phone','string'),
            array('id','int')
        );
        dataFilter($data,'post');
        
        $id=$data['id'];
        unset($data['id']);
        
        D('Address')->update($data,'cust_id='.$_SESSION['userinfo']['id'].' AND id='.$id);
        
        returnJson(SUCCESS,'','操作成功');
    }
}

?>