<?php

/**
 * @参数:
 * $_total    总记录数
 * $_size     一页显示的记录数
 * $_current  当前页
 * $_url      获取当前的url
 * $page_nums 显示的页码个数. 显示的页码个数：2*2+1=5  如：[首页] [上页] 1 2 3 4 5 [下页] [尾页]
 *
 * 调用：
 * $page = new page($total, $size, $current, $url);
 * echo $page->show();
 */
namespace extend;

class Page
{
    private $_total;         // 总记录数
    private $_size;          // 一页显示的内容记录数
    private $_current;       // 当前页
    private $_count;         // 总页数
    private $_url;           // 获取当前的url
    private $is_remark;      // 是否显示统计页面数字
    private $is_ellipsis;    // 是否显示省略号

    private $_start;         // 起头页数
    private $_end;           // 结尾页数

    public function __construct($total = 10, $size = 1, $current = 1, $url, $page_nums = 3, $is_remark = true, $is_ellipsis = false)
    {
        $this->_total = $this->numeric($total);
        $this->_size = $this->numeric($size);
        $this->_current = $this->numeric($current);
        $this->_count = ceil($this->_total / $this->_size);
        $this->_url = $url;
        $this->is_remark = $is_remark;
        $this->is_ellipsis = $is_ellipsis;

        // 对页码数字进行处理
        if ($this->_total < 0)
            $this->_total = 0;

        if ($this->_current < 1)
            $this->_current = 1;

        if ($this->_count < 1)
            $this->_count = 1;

        if ($this->_current > $this->_count)
            $this->_current = $this->_count;

        $this->limit = ($this->_current - 1) * $this->_size;
        $this->_start = $this->_current - $page_nums;
        $this->_end = $this->_current + $page_nums;

        if ($this->_start < 1) {
            $this->_end = $this->_end + (1 - $this->_start);
            $this->_start = 1;
        }

        if ($this->_end > $this->_count) {
            $this->_start = $this->_start - ($this->_end - $this->_count);
            $this->_end = $this->_count;
        }

        if ($this->_start < 1)
            $this->_start = 1;
    }


    // 检测是否为数字
    private function numeric($num)
    {
        if (strlen($num)) {
            if (!preg_match("/^[0-9]+$/", $num)) {
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }


    // 地址替换
    private function page_replace($page)
    {
        return str_replace("{page}", $page, $this->_url);
    }


    // 首页
    private function _home()
    {
        if ($this->_current != 1) {
            return "<a href='" . $this->page_replace(1) . "' title='首页'>&laquo;</a>";
        } else {
            return "<span>&laquo;</span>";
        }
    }


    // 上一页
    private function _prev()
    {
        if ($this->_current != 1) {
            return "<a href='" . $this->page_replace($this->_current - 1) . "' title='上一页'>&lsaquo;</a>";
        } else {
            return "<span>&lsaquo;</span>";
        }
    }


    // 下一页
    private function _next()
    {
        if ($this->_current != $this->_count) {
            return "<a href='" . $this->page_replace($this->_current + 1) . "' title='下一页'>&rsaquo;</a>";
        } else {
            return"<span>&rsaquo;</span>";
        }
    }


    // 尾页
    private function _last()
    {
        if ($this->_current != $this->_count) {
            return "<a href='" . $this->page_replace($this->_count) . "' title='尾页'>&raquo;</a>";
        } else {
            return "<span>&raquo;</span>";
        }
    }


    // 输出: 拼链数据
    public function show($id = 'pages')
    {
        $str = "<div class=" . $id . ">";
        $str.=$this->_home();
        $str.=$this->_prev();

        // page start > 1 显示 ... 。&& 是否显示省略号
        if ($this->_start > 1 && $this->is_ellipsis) {
            $str.="<span class='ellipsis'>...</span>";
        }

        for ($i = $this->_start; $i <= $this->_end; $i++) {
            if ($i == $this->_current) {
                $str.="<a href='" . $this->page_replace($i) . "' title='第" . $i . "页' class='active'>$i</a>";
            } else {
                $str.="<a href='" . $this->page_replace($i) . "' title='第" . $i . "页' class='page-num'>$i</a>";
            }
        }
        // page end < count 显示 ...。&& 是否显示省略号
        if ($this->_end < $this->_count && $this->is_ellipsis) {
            $str.="<span class='ellipsis'>...</span>";
        }
        $str.=$this->_next();
        $str.=$this->_last();

        // 是否显示统计页面数字
        if($this->is_remark){
            $str.="<span class='remark'>共<b>" . $this->_count .
                "</b>页<b>" . $this->_total . "</b>条数据</span>";
        }

        $str.="</div>";
        return $str;
    }
}