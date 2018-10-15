<?php
/**
 * 公司：南昌爵沙科技有限公司
 * 网址：https://www.isjue.cn/
 * 部门：技术保障部
 * 作者：朱德朝
 * 时间: 2018/10/15 0015 17:10
 * 版本：V1.0.0.0
 * 说明：
 */
namespace Album\Model;

class Album
{
    public $id;
    public $artist;
    public $title;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
        $this->title  = (!empty($data['title'])) ? $data['title'] : null;
    }
}