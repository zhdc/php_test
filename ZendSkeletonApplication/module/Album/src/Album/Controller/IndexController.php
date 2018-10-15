<?php
/**
 * 公司：南昌爵沙科技有限公司
 * 网址：https://www.isjue.cn/
 * 部门：技术保障部
 * 作者：朱德朝
 * 时间: 2018/10/15 0015 17:14
 * 版本：V1.0.0.0
 * 说明：
 */
namespace Album\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    protected $albumTable;

    public function indexAction()
    {
        die('cc');
        return new ViewModel(
//            array(
//            'albums' => $this->getAlbumTable()->fetchAll(),
//        )
        );
    }

    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
}