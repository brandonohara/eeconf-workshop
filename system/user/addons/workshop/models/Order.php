<?php

class Order extends CI_Model {

    protected $table = 'workshop_orders';

    /**
     * Returns an array of Orders
     */
    public function all($filters) {
        //if the status parameter is set, add where clause
        if (isset($filters['status'])) {
            ee()->db->where('status', $filters['status']);
        }

        //if the search parameter is set, add where clause where the name 
        //has the search string somewhere inside the name column
        if (isset($filters['search'])) {
            ee()->db->where('name LIKE', '%' . $filters['search'] . '%');
        }

        //set the orderby and sort parameters
        ee()->db->order_by($filters['orderby'], $filters['sort']);

        //set the limit and offset parameters
        ee()->db->limit($filters['limit'], $filters['offset']);

        //get the results from our query
        $query = ee()->db->get($this->table);

        return $query->result_array();
    }
    
    /**
     * Returns a single Order row
     */
    public function get($order_id) {
        $query = ee()->db->get_where($this->table, array('id' => $order_id));
        return $query->row_array();
    }

    /**
     * Stores a Order into the DB, sets the date columns
     */
    public function create($data) {
        $data['created_at'] = date('Y-m-d G:i:s', time());
        $data['updated_at'] = date('Y-m-d G:i:s', time());
        ee()->db->insert($this->table, $data);

        return $data;
    }
    
    /**
     * Updates an Order with an id = $order_id, updates the date column
     */
    public function update($order_id, $data) {
        $data['updated_at'] = date('Y-m-d G:i:s', time());
        ee()->db->where('id', $order_id)->update($this->table, $data);

        return $data;
    }
    
    /**
     * Deletes an Order with id = $order_id
     */
    public function delete($order_id) {
        ee()->db->where('id', $order_id)->delete($this->table);

        return $order_id;
    }
}