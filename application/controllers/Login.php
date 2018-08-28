<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Chat_model');
	}
	
	public function index() {
		if($this->session->userdata('user')){
			$this->Chat_model->setonline();
			redirect(base_url());
		}
		$this->load->view('login');
	}
	
	public function auth() {
		$pass = $this->input->post('pass');
		$this->form_validation->set_rules('pass', 'Пароль', 'trim|required|md5|xss_clean|max_length[45]');
		$query = $this->db->get_where('users',array('pass'=>md5($pass)),1,0);
		
		if ($query->num_rows() > 0){
			
			$res = $query->row_array();
			$newdata = array('logged' => TRUE,'user'=>$res['id'],'name'=>$res['name'],'online'=>$res['online'],'avatar'=>$res['avatar']);
			$this->session->set_userdata($newdata);
			$this->Chat_model->setonline();
			redirect(base_url());
		} else {
			redirect(base_url()."login?error");
		}
		
		
	}
	function logout() {
		$this->session->sess_destroy();
		redirect(base_url()."login");
	}
	
	
	function lastonlines() {
		$query = $this->db->get_where('users',array());
		$res = $query->result_array();
		$this->load->view('onlines', array('res'=>$res));
	}
	
	
}
