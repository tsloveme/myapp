<?php
namespace Qnr\Model;
use Think\Model\RelationModel;
class PriceModel extends RelationModel{
	public function alert(){
		echo(11111);
	}
	
	protected $_link = array(
		'room' => array(
			'mapping_type' => self::BELONGS_TO,
			'foreign_key'	=>	'roomtypeid',
			'mapping_fields'=>	'roomName',
			'mapping_class'	=>	'room',
			//'mapping_class' =>	'room',
			'as_fields'=>'roomName:room_name'
		),
		//'hotel' => array(
			//'mapping_type'=> self::HAS_MANY,
		///	'foreign_key'=>'hotelId',
		//)
	);
	/*protected $_link = array(
		'room' => array(
			//'mapping_type'=>self::BELONGS_TO,
			//'mapping_fields'	=>	'roomName',
			//'as_feilds'			=>	'roomName'
		),
		'hotel' =>array(
			//'mapping_type' 		=> 	self::BELONGS_TO,
			//'mapping_class' 	=> 	'hotel',
			//'mapping_fields'	=>	'hotelName'
		)
		
	);*/
}