<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Orders extends Base {

    public function __construct() {
        parent::__construct();

        //load our Order model
        ee()->load->model('Order');
    }
            
    /**
     * Accepts filters to pass onto the Order all() method.
     * Returns an array of Orders
     */
    function index() {
        $filters = $this->filters();
        $filters['search'] = $this->get('search');
        $filters['status'] = $this->get('status');

        $orders = ee()->Order->all($filters);
        return $this->response($orders);
    }

    /**
     * Accepts an $order_id, and attempts to return the Order
     */
    public function show($order_id) {
        $order = ee()->Order->get($order_id);

        //return a 404 if there isn't an Order with id = $order_id
        if (!$order) {
            return $this->responseWithError('Unable to find Order with ID: ' . $order_id . '.', 404);
        }

        return $this->response($order);
    }

    /**
     * Creates a new Order from given HTTP parameters
     */
    public function store() {
        //get the data from the request
        $data = [
            'status' => $this->get('status'),
            'subtotal' => $this->get('subtotal'),
            'shipping' => $this->get('shipping'),
            'total' => $this->get('total'),
            'name' => $this->get('name')
        ];

        //validate the request, exit with error on failure
        $this->validate($data);

        //create the Order
        $order = ee()->Order->create($data);
        return $this->response($order);
    }

    /**
     * Updates an Order from given HTTP parameters
     */
    public function update($order_id) {
        //get the data from the request
        $data = [
            'status' => $this->get('status'),
            'subtotal' => $this->get('subtotal'),
            'shipping' => $this->get('shipping'),
            'total' => $this->get('total'),
            'name' => $this->get('name')
        ];

        //validate the request, exit with error on failure
        $this->validate($data);

        //update the Order
        $data = ee()->Order->update($order_id, $data);

        return $this->response($data);

    }

    /**
     * Deletes an Order with id = $order_id
     */
    public function destroy($order_id) {
        $data = ee()->Order->delete($order_id);
        return $this->response($data);
    }

    /**
     * Validation of HTTP parameters for creating/updating Order
     */
    protected function validate($data)
    {
        //Set of rules to pass validation
        $rules = array(
            'name' => 'required',
            'status' => 'required',
            'subtotal' => 'required|numeric',
            'shipping' => 'required|numeric',
            'total' => 'required|numeric'
        );

        //run validation
        $result = ee('Validation')->make($rules)->validate($data);

        //if validation fails, return a response with errors
        if (!$result->isValid()) {
            return $this->responseWithError($result->getAllErrors());
        }

        //if we've reached this point, validation has passed
        return true;
    }
}