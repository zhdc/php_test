<?php


	//写一个操作数据库的类
	
	class Model{
		//将内容放入属性之后 你的所有方法都可以通过 $this 来访问你所有的属性
		public $tabName;  //用于存储表名
		public $link;
		public $sql;		//存储你书写的sql语句
		public $limit; 		//用于存储要显示的条数
		public $field = '*'; //存储字段
		public $allFields;   //缓存数据库字段
		public $order;		//用来存储排序内容
		public $where ;    //存储查询条件

		public function __construct($tabName){
			//初始化数据库连接
			$this->getConnect();
			//将你要操作的表名存储起来
			$this->tabName = $tabName;

			//获取数据库字段
			$this->getFields();
		}
		public function sql($sql){
            $this->sql = $sql;
            echo $this->sql;
            return $this->query($sql);
        }

		//增
		//$data['name']='jack';
		//$data['age'] =18;
		public function add($data){
			
			//var_dump($data);exit;
			//var_dump(join(',',array_keys($data)));exit;
			$keys = join(',',array_keys($data));

			$vals = join("','",array_values($data));

			

			$sql="INSERT INTO {$this->tabName}({$keys}) VALUES('{$vals}')";
			//echo $sql;exit;
			$this->sql = $sql;
			return $this->execute($sql);
		}
		//删
		public function del($id){
			$sql="DELETE FROM {$this->tabName} WHERE id={$id}";
			$this->sql = $sql;
			return $this->execute($sql);
		}
		//改
		//修改需要传递一个参数 这个参数是你要修改的字段的数组
		public function update($arr){
			//判断$arr 是不是数组
			if(!is_array($arr)){
				return false;
			}
			//判断 你是否是使用id修改还是使用where条件修改
			if(!empty($arr['id'])){
				$where = ' WHERE  id='.$arr['id'];
			}else{
				$where = $this->where;
			}
			//echo $where;exit;

			$result = '';
			foreach($arr as $key=>$val){
				//反引号是用来判处你使用的字段为关键字的问题
				if($key !='id'){
					$result .="`{$key}`='{$val}',";
				} 
			}

			//echo $result;exit;
			//多出来的一个逗号怎么去掉
			$result = rtrim($result,',');
			//echo $result;exit;

			$sql="UPDATE {$this->tabName} SET {$result} {$where}";
			$this->sql = $sql;
			return $this->execute($sql);

		}
		//查
		public function select(){
			
			$sql = "SELECT {$this->field} FROM {$this->tabName} {$this->where} {$this->order} {$this->limit} ";
			$this->sql = $sql;
			return $this->query($sql);

		}
		//查询一条结果
		public function find($id){
			
			$sql="SELECT {$this->field} FROM {$this->tabName} WHERE id={$id}";
			//你来写查询一条结果的sql语句
			$list=$this->query($sql);
			return $list[0];
		}

		//统计总条数
		public function count(){
			$sql="SELECT COUNT(*) total FROM {$this->tabName}";
			$total = $this->query($sql);
			//var_dump($total);
			return $total[0]['total'];
		}

		/********************下面的方法是用来连贯操作的方法*****************************/
		//字段过滤 字段筛选
		//设置字段
		//$fields = array('id','name','sex');
		public function field($fields){
			
			//判断你传递的参数是否是数组
			if(!is_array($fields)){
				//用于连贯操作
				return $this;
			}
			//检测数据内容 删除没有的字段
			$fields = $this->check($fields);

			//可能你写的数组里面都不在我们字段列表中 返回的是一个空数组所以我们需要再次判断
			if(!is_array($fields) || empty($fields)){
				//连贯操作
				return $this;
			}
			//var_dump($fields);exit;


			$this->field = join(',',$fields);
			//echo $this->field;exit; 

			//连贯操作
			return $this;
		}
		//每页显示多少条
		
		public  function limit($limit){
			$this->limit = ' limit '.$limit;
			return $this;//$user
		}

		//排序的内容应该怎么写 orderby 
		public function order($str){
			$this->order = "ORDER BY  {$str} ";
			return $this;
		}

		//where条件查询
		//$data['id']=1;
		//$data[id]=array('lt',10);
		//$data['name']=array('like','长');
		//$data['id']=array('in','1,2,3,4,5,6');
		public function where($data){
//					var_dump($data);
			//where id =1 and age <20 and name like '%想%';
			//where id =1
			
			//判断传递过来的必须是一个数组 而且是不为空的数组
			if(is_array($data) && !empty($data)){
				//接收结果的	
				$result = array();
				//循环传递进来的数组得到里面的键和值
				foreach($data as $key=>$val){
					//判断值是否是一个数组
					if(is_array($val)){

//						var_dump($val);
						$type = $val[0];
						//判断你是什么样的条件 将你所要的条件进行拼接
						switch($type){
								case "lt":
									$result[] = "{$key}<{$val[1]}";
								break;
								case 'like':
									$result[] = "{$key} LIKE '%{$val[1]}%'";
								break;
								case 'gt':
									$result[] = "{$key}>{$val[1]}";
								break;
								case 'in':
									$result[]= "{$key} in({$val[1]})";
								break;

						}

					}else{
						
						$result[] = " {$key}={$val}";
					}


				}
				//最终得到的数组样式
				//var_dump($result);

				//将数组和where拼接 得到最终的where效果
				$where = ' where '.join(' and  ',$result);
				//echo $where;
				//写入属性
				$this->where = $where;

			}


			return $this;
		}

		//*********************辅助方法******************************//
		//连接数据库方法
		protected function getConnect(){
			//连接数据库前四步操作
			$this->link=mysqli_connect(HOST,USER,PWD);
			if(mysqli_connect_errno($this->link)){
				echo mysqli_connect_error($this->link);exit;
			}
			mysqli_select_db($this->link,DB);
			mysqli_set_charset($this->link,CHARSET);
		}


		//用于添加 修改 删除
		protected function execute($sql){
			$result = mysqli_query($this->link,$sql);
			if($result && mysqli_affected_rows($this->link)>0){
				//如果是添加操作就将添加成功的id返回回去 如果不是添加操作 就返回受影响行
				return  mysqli_insert_id($this->link)?mysqli_insert_id($this->link):mysqli_affected_rows($this->link);
			}
		}

		//用于查询的方法
		protected function query($sql){
			$result = mysqli_query($this->link,$sql);
			if($result && mysqli_num_rows($result)>0){
				$list = array();
				while($row = mysqli_fetch_assoc($result)){
					$list[]=$row;
				}
			}
			if(!empty($list)){
                return $list;
            }
		}
		//获取数据库字段方法
		protected function getFields(){
			//查看表信息数据库
			$sql="DESC {$this->tabName}";
			//发送数据语句
			$result = $this->query($sql);
			//var_dump($result);exit;
			//新建一个数组 用来存储数据库字段
			$fields = array();
			foreach($result as $val){
				$fields[]= $val['Field'];
			}
			
			//设置为缓存字段
			$this->allFields = $fields;
		}
		//用来检测字段的方法
		public function check($arr){
			//遍历传递过来的数组 我们才能拿到数组中的键和值
			foreach($arr as $key=>$val){
				//判断 你的值是否在缓存字段数组中 allFields
				if(!in_array($val,$this->allFields)){
					unset($arr[$key]);
				}

			}
			//var_dump($arr);exit;
			return $arr;
		}

		//回收资源
		public function __destruct(){
			mysqli_close($this->link);
		}	
	}
