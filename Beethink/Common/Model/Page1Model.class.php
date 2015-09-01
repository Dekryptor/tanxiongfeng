<?php
class Page1Model
{
    protected static $PG = 1,      //当前页
        $trueTableName = '',       //表全名
        $pageSize = 15,             //分页记录数
        $handle = null;             //数据库操作柄
    /*
    $data=array(
        'condition'=>array(
            '列名'=>array('值','条件','类型'),
        );
        'pg'=>当前页,
        'order'=>'排序方式',
        'cols'=>'列1，列2',
        'pageSize'=>'分页记录数',
        'trueTableName'=>'表全名',
        'extWhere'=>'拓展查询条件  可为空'
    );
    */
    public function getPager(&$data)
    {
        self::initParam($data);
        $where = self::getWhere($data['condition']);
        (isset($data['extWhere'])) && ($where = self::getExtWhere($where, $data['extWhere']));
        self::$handle = Sys::M($data['trueTableName']);
        (!isset($data['cols'])) && ($data['cols'] = '*');

        $total = self::getTotalCount($where);
        $pageCount = ceil($total / self::$pageSize);
        $limit = self::getLimit(self::$PG, self::$pageSize);
        $rs = self::getData($data['cols'], $where, $data['order'], $limit);

        return array('data' => $rs, 'paging' => array('page' => self::$PG, 'totalCount' => $total, 'numberOfPage' => $pageCount));
    }
    /*参数初始化+预处理*/
    private static function initParam(&$data)
    {
        isset($data['pageSize']) ? (int)$data['pageSize'] : self::$pageSize;
        isset($data['pg']) ? (int)$data['pg'] : self::getPage();
        (!isset($data['order'])) && ($data['order'] = '');
        (empty($data['order'])) || ($data['order'] = ' ORDER BY ' . $data['order']);

        self::$trueTableName = $data['trueTableName'];
        self::$PG = $data['pg'];
        self::$pageSize = $data['pageSize'];
    }

    /*获取当前页*/
    private static function getPage()
    {
        return (isset($_GET['pg'])) ? (int)$_GET['pg'] : 1;
    }
    /*
     * 获取拓展的条件
     * */
    static function getExtWhere($where, $extWhere)
    {
        return ' WHERE ' . (empty($where) ? $extWhere : $where . ' ' . $extWhere);
    }
    /**
     * 获取总的记录数
     */
    static function getTotalCount($where)
    {
        $rs = self::$handle->query('SELECT COUNT(0) AS total FROM ' . self::$trueTableName . $where);
        return $rs[0]['total'];
    }
    /*获取记录*/
    static function getData($cols, $where, $order, $limit)
    {
        die('SELECT ' . $cols . ' FROM ' . self::$trueTableName . $where . $order . $limit);
        return self::$handle->query('SELECT ' . $cols . ' FROM ' . self::$trueTableName . $where . $order . $limit);
    }
    /*获取limit*/
    static function getLimit($pg, $pageSize)
    {
        $start = ($pg - 1) * $pageSize;
        return ' LIMIT ' . $start . ',' . $pageSize;
    }
    /*获取条件*/
    static function getWhere(&$data)
    {
        if (!is_array($data)) return false;
        $where_arr = array();

        foreach ($data as $k => $v) {
            if ($v[0] === '') continue;
            $where_arr[] = self::analyseCondition($k, $v);
        }

        return implode(' AND ', $where_arr);
    }
    /*解析条件*/
    static function analyseCondition($col, $data)
    {
        isset($data[2]) || ($data[2] = 'string');
        $v = self::_addslashes($data[0], $data[2]);

        switch (strtolower($data[1])) {
            case '<':
            case '<=':
                ;
            case '>':
                ;
            case '>=':
                ;
            case '=':
                ;
            case '!=':
                return $col . $data[1] . $v;
            case 'like':
            case '%':
                return $col . ' LIKE \'' . $v . '%\'';
            case '%%':
                return $col . ' LIKE \'%' . $v . '%\'';
            case 'in':
                return $col . ' IN (' . $v . ')';
            case 'find':
                return 'FIND_IN_SET(' . $col . ',\'' . $v . '\')';
            case '!find':
                return '!FIND_IN_SET(' . $col . ',\'' . $v . '\')';
            case 'ignore':
                return $col . '=' . $v;
            case 'between':
            case '-':
                return $col . ' BETWEEN ' . self::_addslashes($data[0][0], $data[2]) . ' AND ' . self::_addslashes($data[0][1], $data[2]);
            default:
                return $col . '=\'' . $v . '\'';
        }
    }
    /**
     * 根据条件处理数据
     */
    static function _addslashes($val, $type)
    {
        if (is_array($val)) {
            return $val;
        }
        switch (strtolower($type)) {
            case 'int':
            case 'smallint':
            case 'tinyint':
            case 'mediumint':
                return (int)$val;
            case 'float':
                return (float)$val;
            case 'double':
                return (double)$val;
            case 'bool':
                return (bool)$val;
            case 'ignore':
                return $val;
            default:
                return '\'' . addslashes($val) . '\'';
        }
    }
}