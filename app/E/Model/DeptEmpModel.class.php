<?php
namespace E\Model;
use Think\Model\RelationModel;
class DeptEmpModel extends RelationModel{
	protected $_link = array(
		'ts' => array(
			'mapping_type'		=>	self::BELONGS_TO,
			//'mapping_fields'	=>	'dept_name',			//要查询的关联表的字段，不填写就默认查询所有。
			//'mapping_name'		=>	'ts1',				//映射成的查询结果字段键值不填就是默认的'ts'
			'class_name'		=>	'Departments',			//关联的表名，不填的话默认为'ts'。
			'foreign_key' 		=> 	'dept_no',				//关联的外键，
			'as_fields' 		=> 	"dept_name:Department,dept_no:DepartmentId"	
															//
			
		),
	);
	
}