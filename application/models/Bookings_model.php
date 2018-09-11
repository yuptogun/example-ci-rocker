<?php

class Bookings_model extends CI_Model {

    protected $table = 'bookings';
    public $basicFields = ['booked_by', 'type', 'starts_at', 'ends_at', 'updated_at'];

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->model('booking_types_model', 'types');
    }

    // 조건 배열을 받아서 Read를 수행합니다.
    public function check($whereArray = null, $toDisplay = null)
    {
        $results = $this->db->where('deleted_at', null)->where($whereArray)->get($this->table)->result();
        return $this->format($results, $toDisplay);
    }

    // 내용을 받아서 Create 또는 Update를 실행합니다.
    public function book($userId, $data, $bookingId = null)
    {
        if (isset($data['extraData'])) $data['extraData'] = json_decode($data['extraData']);
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->basicFields)) {
                $data['extraData'][$key] = $value;
                unset($data[$key]);
            }
        }
        $data['extraData'] = json_encode($data['extraData']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $bookingId ?
            $this->db->update($this->table, $data, ['id' => $bookingId]) :
            $this->db->insert($this->table, $data);
    }

    public function cancel($bookingId)
    {
        return $this->db->update($this->table, ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $bookingId]);
    }

    public function by($userId, $upcoming = false, $toDisplay = null)
    {
        $where = ['booked_by' => $userId];
        if ($upcoming) $where['starts_at >'] = date('Y-m-d H:i:s');
        return $this->check($where, $toDisplay);
    }

    // 전체 결과의 extraData 등을 처리합니다.
    public function format($results, $toDisplay = false)
    {
        // 각 결과에 대해서
        foreach ($results as $result) {

            // extraData를 열어봐서
            $extraData = json_decode($result->extraData);

            // 제대로 json이 들어가 있으면
            if (!empty($extraData) && is_object($extraData)) {

                // 하나하나 모델의 키로 뽑아내줍니다.
                foreach ($extraData as $key => $value) {
                    $result->{$key} = $value;
                }

                // extraData라는 키는 의미 없으니 없애줍니다.
                unset($result->extraData);
            }

            // 예약관리 화면 달력에 뿌리기 위한 포맷팅
            if ($toDisplay) {
                $result->title = $extraData->title ?? $this->types->get(['code' => $result->type])->name;
                $start = new DateTime($result->starts_at);
                $end = new DateTime($result->ends_at);
                $result->start = $start->format(DateTime::ATOM);
                $result->end = $end->format(DateTime::ATOM);
                $result->color = $this->types->get(['code' => $result->type])->color;
            }
        }

        // 포맷팅된 결과들을 돌려줍니다.
        // 라라벨 eloquent는 accessor, mutator 등이 있어서 이런 작업이 아주 깔끔합니다.
        return $results;
    }
}