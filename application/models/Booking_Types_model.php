<?php

class Booking_Types_model extends CI_Model {

    protected $table = 'booking_types';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($array, $availablesOnly = false)
    {
        $query = $availablesOnly ?
            $this->db->where('available', (int) true)->get_where($this->table, $array) :
            $this->db->get_where($this->table, $array);
        return $query->num_rows() == 1 ? $query->row() : $query->result();
    }

    public function availables()
    {
        return $this->get(['available' => (int) true]);
    }
}