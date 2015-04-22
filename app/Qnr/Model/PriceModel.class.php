<?php
namespace Qnr\Model;
use Think\Model\RelationModel;
class PriceModel extends RelationModel{
	public function alert(){
		echo(11111);
	}
	
	protected $_link = array(
		//'hotel' => array(),
		'room' => array(
			'mapping_type' => self::BELONGS_TO,
			'foreign_key'	=>	'roomtypeid',
			'mapping_fields'=>	'roomname',
			'mapping_class'	=>	'room',
			'as_fields'=>'roomname'
		),
		'hotel' => array(
			'mapping_type'=> self::BELONGS_TO,
			'foreign_key'=>'hotelid',
			'mapping_fields'=>'hotelname,hotelseq',
			'as_fields'=>'hotelname,hotelseq'
		)
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