<?php

class Chat_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function dating($date='', $mode=false, $time=false) {
		$monthes = array(
		    1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
		    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
		    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
		);
		$weekdays = array(
			0 => 'Воскресенье', 1 => 'Понедельник', 2 => 'Вторник',
			3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота' 
		);
		
		$m = date("n", strtotime($date));
		$d = date("w", strtotime($date));
		//print_r($weekdays[$d]);
		if ($mode) {
			$res = date("j ", strtotime($date)).$monthes[$m].', '.$weekdays[$d]; //.date(" в H:i", strtotime($date));
		} else {
			$res = date("j ", strtotime($date)).$monthes[$m].date(" в H:i", strtotime($date));
		}
		if ($time) {
			$res = date(" в H:i", strtotime($date));
		}
		return $res;
	}
	
	function smiles($mess='') {
		$res = '';
		if ($mess) {
			$res = $mess;
			$osm = str_replace(" ", '', trim($mess));
			$osm = str_replace("(angel)", '', $osm);
			$osm = str_replace("(bigsmile)", '', $osm);
			$osm = str_replace("(blushing)", '', $osm);
			$osm = str_replace("(cool)", '', $osm);
			$osm = str_replace("(crying)", '', $osm);
			$osm = str_replace("(cryingwhilelaughing)", '', $osm);
			$osm = str_replace("(doh)", '', $osm);
			$osm = str_replace("(donttalktome)", '', $osm);
			$osm = str_replace("(facepalm)", '', $osm);
			$osm = str_replace("(giggle)", '', $osm);
			$osm = str_replace("(headbang)", '', $osm);
			$osm = str_replace("(heart)", '', $osm);
			$osm = str_replace("(hug)", '', $osm);
			$osm = str_replace("(inlove)", '', $osm);
			$osm = str_replace("(kiss)", '', $osm);
			$osm = str_replace("(lips)", '', $osm);
			$osm = str_replace("(mmm)", '', $osm);
			$osm = str_replace("(nerd)", '', $osm);
			$osm = str_replace("(nod)", '', $osm);
			$osm = str_replace("(party)", '', $osm);
			$osm = str_replace("(rofl)", '', $osm);
			$osm = str_replace("(sadsmile)", '', $osm);
			$osm = str_replace("(smile)", '', $osm);
			$osm = str_replace("(surprised)", '', $osm);
			$osm = str_replace("(tongueout)", '', $osm);
			$osm = str_replace("(wondering)", '', $osm);
			$osm = str_replace("(worried)", '', $osm);
			$osm = str_replace("(yawning)", '', $osm);
			$size = ($osm)?20:40;
			
			$res = str_replace("(angel)", '<img src="/assets/img/smiles/new/angel.gif" width="'.$size.'px">', $res);
			$res = str_replace("(bigsmile)", '<img src="/assets/img/smiles/new/laugh.gif" width="'.$size.'px">', $res);
			$res = str_replace("(blushing)", '<img src="/assets/img/smiles/new/blush.gif" width="'.$size.'px">', $res);
			$res = str_replace("(cool)", '<img src="/assets/img/smiles/new/cool.gif" width="'.$size.'px">', $res);
			$res = str_replace("(crying)", '<img src="/assets/img/smiles/new/cry.gif" width="'.$size.'px">', $res);
			$res = str_replace("(cryingwhilelaughing)", '<img src="/assets/img/smiles/new/cwl.gif" width="'.$size.'px">', $res);
			$res = str_replace("(doh)", '<img src="/assets/img/smiles/new/doh.gif" width="'.$size.'px">', $res);
			$res = str_replace("(donttalktome)", '<img src="/assets/img/smiles/new/donttalktome.gif" width="'.$size.'px">', $res);
			$res = str_replace("(facepalm)", '<img src="/assets/img/smiles/new/facepalm.gif" width="'.$size.'px">', $res);
			$res = str_replace("(giggle)", '<img src="/assets/img/smiles/new/giggle.gif" width="'.$size.'px">', $res);
			$res = str_replace("(headbang)", '<img src="/assets/img/smiles/new/headbang.gif" width="'.$size.'px">', $res);
			$res = str_replace("(heart)", '<img src="/assets/img/smiles/new/heart.gif" width="'.$size.'px">', $res);
			$res = str_replace("(hug)", '<img src="/assets/img/smiles/new/hug.gif" width="'.$size.'px">', $res);
			$res = str_replace("(inlove)", '<img src="/assets/img/smiles/new/inlove.gif" width="'.$size.'px">', $res);
			$res = str_replace("(kiss)", '<img src="/assets/img/smiles/new/kiss.gif" width="'.$size.'px">', $res);
			$res = str_replace("(lips)", '<img src="/assets/img/smiles/new/lips.gif" width="'.$size.'px">', $res);
			$res = str_replace("(mmm)", '<img src="/assets/img/smiles/new/mmm.gif" width="'.$size.'px">', $res);
			$res = str_replace("(nerd)", '<img src="/assets/img/smiles/new/nerdy.gif" width="'.$size.'px">', $res);
			$res = str_replace("(nod)", '<img src="/assets/img/smiles/new/nod.gif" width="'.$size.'px">', $res);
			$res = str_replace("(party)", '<img src="/assets/img/smiles/new/party.gif" width="'.$size.'px">', $res);
			$res = str_replace("(rofl)", '<img src="/assets/img/smiles/new/rofl.gif" width="'.$size.'px">', $res);
			$res = str_replace("(sadsmile)", '<img src="/assets/img/smiles/new/sad.gif" width="'.$size.'px">', $res);
			$res = str_replace("(smile)", '<img src="/assets/img/smiles/new/smile.gif" width="'.$size.'px">', $res);
			$res = str_replace("(surprised)", '<img src="/assets/img/smiles/new/surprised.gif" width="'.$size.'px">', $res);
			$res = str_replace("(tongueout)", '<img src="/assets/img/smiles/new/tongueout.gif" width="'.$size.'px">', $res);
			$res = str_replace("(wondering)", '<img src="/assets/img/smiles/new/wonder.gif" width="'.$size.'px">', $res);
			$res = str_replace("(worried)", '<img src="/assets/img/smiles/new/worry.gif" width="'.$size.'px">', $res);
			$res = str_replace("(yawning)", '<img src="/assets/img/smiles/new/yawn.gif" width="'.$size.'px">', $res);
		}
		return $res;
	}
	function smiling($smile='') {
		if ($smile) {
			$smile = $this->db->get_where('smiles', array('smile'=>$smile))->row_array();
			return $smile['img'];
		}
	}
	function urlsreplace($text) { 
		$text = preg_replace('/((?:\w+:\/\/|www\.)[\w.\/%\d&?#+=-]+)/i', '<a href="\1" rel="nofollow" target="_blank">\1</a>', $text);
		return $text; 
	}
	
	function setonline() {
		$this->db->where('id', $this->session->userdata('user'));
		$this->db->update('users', array('online'=>time()));
	}
	
	function getonline($time=0) {
		if((time()-$time)>40){
			return false;
		} else {
			return true;
		}
	}
	function samemessage($id=0) {
		$message = $this->db->get_where('messages', array('id'=>$id))->row_array();
		$prev = $this->db->get_where('messages', array('id'=>($id-1)))->row_array();
		$same = ((strtotime($message['timesend'])-strtotime($prev['timesend']))<600&&$message['user']==$prev['user'])?1:0;
		return $same;
	}
	function sameminute($id=0) {
		$message = $this->db->get_where('messages', array('id'=>$id))->row_array();
		$prev = $this->db->get_where('messages', array('id'=>($id-1)))->row_array();
		$same = ($this->Chat_model->dating($message['timesend'])==$this->Chat_model->dating($prev['timesend'])&&$message['user']==$prev['user'])?1:0;
		
		//echo $same;
		return $same;
	}
	

}