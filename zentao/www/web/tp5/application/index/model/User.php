<?php
namespace app\index\model;
use think\Model;

class User extends Model{
    protected $pk = 'Id';
    protected $table = 'Tuser';
    protected $field = [
        'id',
        'username',
        'password',
        'email',
        'phone'
    ];
}
?>