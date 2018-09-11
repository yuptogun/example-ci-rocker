<?php

class User_Roles_model extends CI_Model {

	protected $table = 'user_roles';

    public function __construct()
    {
        parent::__construct();
    }

    // 사용자권한 정보 받아오기
    // $key 에 지정한 키값만 가져옵니다. null 을 넣으면 레코드 객체 자체를 돌려줍니다.
    public function return($info = 1, $key = 'code')
    {
    	$where = is_string($info) ? ['code' => $info] : ['level' => $info];
    	$role = $this->db->get_where($this->table, $where)->row();
    	return empty($key) ? $role : $role->{$key};
    }

    // 사용자권한 정보를 등급 숫자로 받아오기
    public function level($level = 1, $key = 'code')
    {
    	return $this->return((int) $level, $key);
    }

    // 사용자권한 정보를 코드명으로 받아오기
    public function code($level = 'member', $key = 'code')
    {
    	return $this->return((string) $level, $key);
    }

    // 현재 DB에 등록된 모든 종류의 사용자 권한 배열을 돌려줍니다.
    public function allRoles($key = 'code')
    {
    	return $this->select($key)->get($this->table)->result_array();
    }
}