<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Chat_model');
	}
	
	public function login()	{
		
	}
	
	public function auth() {
		$res = (object) array('success'=>false, 'responce'=>(object)array(), 'text'=>'', 'error'=>true);;
		if (isset($_REQUEST['password'])&&$_REQUEST['password']) {
			$query = $this->db->get_where('users', array('pass'=>md5($_REQUEST['password'])));
			if ($query->num_rows()) {
				$res->success = true;
				$res->error = false;
				$res->responce = $query->row_array();
			} else {
				$res->text = 'Не правильный пароль';
			}
		}
		header('Content-Type: application/json');
		echo json_encode($res);
	}
	
}