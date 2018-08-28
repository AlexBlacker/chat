<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('user')){
			redirect(base_url()."login");
		}
		$this->load->model('Chat_model');
	}
	
	public function index() {
		$this->Chat_model->setonline();
		$user = $this->db->get_where('users', array('id!='=>$this->session->userdata('user')));
		$u = $user->row_array();
		$data['user'] = $u;
		$data['online'] = $this->Chat_model->getonline($u['online']);
		$data['smiles'] = $this->db->query('SELECT smile, COUNT(smile) count FROM smilestat GROUP BY smile ORDER BY COUNT(smile) DESC')->result_array();
		$mess = $this->db->query("SELECT `m`.*, `u`.`id` `uid`, `u`.`name`, `u`.`online`, `u`.`avatar` FROM `messages` `m` INNER JOIN `users` `u` ON `u`.`id` = `m`.`user` ORDER BY m.id DESC LIMIT 20");
		$res = array_reverse($mess->result_array());
		$data['messages'] = $res;
		$this->db->where('user!=', $this->session->userdata('user'));
		$this->db->update('messages', array('unread'=>0));
		$this->load->view('chat', $data);
		//print_r($data['smiles']);
	}
	
	public function load($p=1) {
		$num=20;
		$s = $p * $num - $num;
		$this->Chat_model->setonline();
		$user = $this->db->get_where('users', array('id!='=>$this->session->userdata('user')));
		$u = $user->row_array();
		$data['user'] = $u;
		$data['online'] = $this->Chat_model->getonline($u['online']);
		
		$mess = $this->db->query("SELECT `m`.*, `u`.`id` `uid`, `u`.`name`, `u`.`online`, `u`.`avatar` FROM `messages` `m` INNER JOIN `users` `u` ON `u`.`id` = `m`.`user` ORDER BY m.id DESC LIMIT ".$s.", ".$num);
		$res = array_reverse($mess->result_array());
		$data['messages'] = $res;
		$this->db->where('user!=', $this->session->userdata('user'));
		$this->db->update('messages', array('unread'=>0));
		$res = $this->load->view('clean_chat', $data, true);
		echo json_encode($res);
	}
	
	
	function send() {
		if (isset($_POST['to'])&&isset($_POST['mess'])) {
			
			
			$data['timesend'] = date("Y-m-d H:i:s");
			$data['user']=$this->session->userdata('user');
			$data['mess'] = htmlspecialchars(trim($_POST['mess']));
			$data['mess'] = str_replace("\n","<br/>\n",$_POST['mess']);
			$data['attach'] = trim($_POST['attach']);
			$data['unread'] = '1';
			if ($data['mess']) {
				$this->Chat_model->setonline();
				$this->db->insert('messages', $data);
				$id = $this->db->insert_id();
				$mess = $this->db->query("SELECT `m`.*, `u`.`id` `uid`, `u`.`name`, `u`.`online`, `u`.`avatar`
					FROM `messages` `m` INNER JOIN `users` `u` ON `u`.`id` = `m`.`user`
					WHERE `m`.`id` = '".$id."'");
				$res = $mess->row_array();
				$res['avatar'] = ($res['avatar'])?$res['avatar']:"";
				$res['mess'] = $this->Chat_model->urlsreplace($this->Chat_model->smiles($res['mess']));
				$query = $this->db->query("SELECT * FROM `messages` WHERE `id`<'".$id."' ORDER BY `id` DESC LIMIT 1");
				$last = $query->row_array();
				//if ((strtotime($data['timesend'])-strtotime($last['timesend']))<600&&$last['user']==$data['user']) {$res['same']=1; $same=false;} else {$res['same']=0; $same=true;}
				$same = $this->Chat_model->samemessage($res['id']);
				$res['sameminute'] = $this->Chat_model->sameminute($res['id']);
				$res['same'] = $same;
				$s = ($same)?1:0;
				$res['date'] = $this->Chat_model->dating($res['timesend'], $s, $s);
			} else {
				$res=false;
			}
		} else {
			$res=false;
		}
		echo json_encode($res);
	}

	public function updonline()	{
		$user = $this->db->get_where('users', array('id!='=>$this->session->userdata('user')));
		$u = $user->row_array();
		$online = $this->Chat_model->getonline($u['online']);
		$this->Chat_model->setonline();
		$class = ($online)?'yes':'no';
		
		$d=date("d"); $m=date("m");	$y=date("Y");
		if (date("Y", $u['online'])==$y&&date("m", $u['online'])==$m&&date("d", $u['online'])==$d) {
			$arrdates = 'сегодня';
		} elseif (date("Y", $u['online'])==($y)&&date("m", $u['online'])==$m&&date("d", $u['online'])==$d-1) {
			$arrdates = 'вчера';
		} elseif (date("Y", $u['online'])==($y)&&date("m", $u['online'])==$m&&date("d", $u['online'])==$d-2) {
			$arrdates = 'позавчера';
		}
		$loged = ($u['id']==1)?' заходил ':' заходила ';
		$on = ($online)?'в чате':$loged.$arrdates.date(" в H:i", $u['online']);
		$res = '<div class="onl '.$class.'">'.$u['name'].' '.$on.'</div>';
		echo json_encode($res);
	}
	public function updateonline()	{
		$this->Chat_model->setonline();
		echo json_encode(true);
	}
	
	function read() {
		$this->db->where('user!=', $this->session->userdata('user'));
		$this->db->update('messages', array('unread'=>0));
		echo json_encode(true);
	}
	
	function smilestat() {
		if (isset($_POST['smile'])) {
			$this->db->insert('smilestat', array('smile'=>$_POST['smile']));
		}
		echo json_encode(true);
	}
	
	function attach() {
		$res = array('result'=>false, 'text'=>'');
		if (isset($_FILES['attach'])&&$_FILES['attach']) {
			$attach = $_FILES['attach'];
			$path = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
			$file = substr(md5(time()), 0,10);
			$type = explode('/', $attach['type']);
			$file .= '.'.$type[1];
			$res['result'] = move_uploaded_file($attach['tmp_name'], $path.$file);
			$res['text'] = $file;
		}
		echo json_encode($res);
	}
	
	function attachdel() {
		$res = false;
		$res = @unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$_POST['attach']);
		echo json_encode($res);
	}
	
}
