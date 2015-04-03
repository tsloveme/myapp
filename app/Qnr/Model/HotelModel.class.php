<?php
namespace Qnr\Model;
use Think\Model\RelationModel;
class HotelModel extends RelationModel{
    protected $_link = array(
		'City'=>array(
			'mapping_type' => self::BELONG_TO,
			'class_name' => 'City',
		),
    );
}