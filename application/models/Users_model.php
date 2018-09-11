<?php

class Users_model extends CI_Model {

	protected $table = 'users';

    public function __construct()
    {
        parent::__construct();

        // CI에는 Eloquent와 같은 relation 자동 처리가 없어서 부득이 모델을 따로 또 불러와야 합니다.
        $CI =& get_instance();
        $CI->load->model('user_roles_model', 'roles');
    }

	// 로그인 유지중인 사용자 가져오기
	public function currentUser()
	{
		// JWT 인증값이 있는지 봅니다.
		// jwt 라이브러리는 config/autoload.php 에서 로딩해둔 상태입니다.
		// libraries/JWTauth.php 를 참고하세요.
		$jwt = $this->jwtauth->getJWT();

		// 없으면 실패.
		if (!$jwt) return null;

		// JWT가 유효한지 봅니다.
		$token = $this->jwtauth->decodeJWT($jwt);

		// 유효하지 않으면 실패시키고, 유효하면 그 1명에 대한 레코드 객체를 돌려줍니다.
		return !$token ? null : $this->select((array) $token, 1);
	}

	// SELECT
	// $numbers에 숫자를 넣어서 몇 명 가져올지 정할 수 있습니다.
	// $inactives에 true를 넣으면 휴면/탈퇴 처리된 회원도 가져옵니다.
	public function select($array, $numbers = null, $inactives = false)
	{
		$query = $this->db->where($array);

		// 이 분기를 타기 때문에 inactive 처리된 회원이 삭제된 것처럼 나오게 됩니다.
		if ($inactives) $query = $query->where('role != "'.$this->roles->level(0).'"');

		$result = $query->get($this->table);

		// 실행 결과가 1개일 때와 여러 개일 때의 결과 반환 처리를 분기합니다.
		return ((int) $numbers == 1) ? $result->row() : $result->result();
	}

	// INSERT
	public function insert($array)
	{
		// 입력 정보를 적당히 전처리합니다.
		$array = $this->beforeSetArray($array);

		// 과감하게 인서트하고 결과를 반환합니다.
		return $this->db->insert($this->table, $array);
	}

	// UPDATE
	public function update($array, $userId)
	{
		// insert() 와 흡사하므로 축약했습니다.
		return $this->db->update($this->table, $this->beforeSetArray($array), ['id' => $userId]);
	}

	// DELETE
	// 정말로 삭제하지는 않고 휴면 처리를 합니다. 엄밀한 의미에서의 soft delete는 아니지만...
	public function delete($userId)
	{
		return $this->update(['role' => $this->roles->level(0)], $userId);
	}

    // 이메일과 비밀번호 평문을 받아서 실제 가입돼 있는 회원인지 확인합니다.
    public function isValidUser($identity, $password)
	{
		// $identity 는 회원 고유번호, 이메일 또는 사용자명으로 처리 가능
		$key = is_int($identity) ? 'id' :
			(strpos($identity, '@') !== false ? 'email' : 'username');

		// 그 회원에 대해 저장된 비밀번호 해싱값을 가져온 다음
		$savedPassword = $this->select([$key => $identity], 1)->password;

		// password_hash() 내장함수로 단방향 암호화한 비밀번호를 이 내장함수로 검증할 수 있습니다.
		return password_verify($password, $savedPassword);
	}

	// 특정 $column이 특정 $value를 가지지 않고 있는지 알려줍니다.
	public function isNew($column, $value)
	{
		return count($this->select([$column => $value])) == 0;
	}

	// 입력으로 넘길 배열을 손봐줍니다.
	public function beforeSetArray($array)
	{
		// ID는 A_I에 PK이므로 입력에서 빼줘야 합니다.
		if (isset($array['id']))
			unset($array['id']);

		// 이상한 role을 갖고 있는 회원은 전부 member로 강등합니다.
		if (isset($array['role']) && !in_array($array['role'], $this->roles->allRoles()))
			$array['role'] = $this->roles->level(1);

		// 비밀번호 해싱. 이 내장함수로 해싱하면 password_verify() 내장함수로 검증 가능한 안전한 단방향 암호화가 됩니다.
		if (isset($array['password']))
			$array['password'] = password_hash($array['password'], PASSWORD_DEFAULT);

		// 배열을 돌려줍니다.
		return $array;
	}
}