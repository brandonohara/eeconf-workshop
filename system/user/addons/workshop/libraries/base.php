<?php

/**
 * The Orders library extends this class, so it has access to all these methods
 */
class Base {

    function __construct() {
        // set the package path
        // https://docs.expressionengine.com/latest/development/legacy/libraries/loader.html
        ee()->load->add_package_path(PATH_THIRD.'workshop');
    }
    
    /**
     * This method is called by the routing function, 
     * it directs the API request to the proper method
     */
    public function call_method($method, $model = null) {
        //if the method doesn't exist, or the method isn't callable, exit with an error
        if (!method_exists($this, $method) || !is_callable(array($this, $method))) {
            $this->responseWithError('Method does not exist', 404);
        }

        //call the method on the library, passing the model if available
        return $this->$method($model);
    }
    
    /**
     * This method prints a formated json string, and sets the HTTP code
     */
    public function response($data = null, $message = '', $code = 200) {
        //set the HTTP status code
        ee()->output->set_status_header($code);

        //set the content type to JSON
        header('Content-Type: application/json');

        //print the JSON response
        echo json_encode([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);

        //prevent more code from running
        exit();
    }

    /**
     * This is a helper function that calls 
     * the response method without a data parameter
     */
    public function responseWithError($error, $code = 500) {
        return $this->response(null, $error, $code);
    }

    /**
     * This method checks if the HTTP parameter is set in the GET or POST request
     * and is not an empty string. Return the default value if the parameter isn't set
     * or is an empty string
     */
    public function get($parameter, $default = null) {
        return ee()->input->get_post($parameter) && ee()->input->get_post($parameter) != '' 
            ? ee()->input->get_post($parameter) 
            : $default;
    }

    /**
     * Returns an array of commonly used parameters like pagination and sorting
     */
    public function filters() {
        return [
            'limit' => $this->get('limit', 20),
            'offset' => $this->get('offset', 0),
            'orderby' => $this->get('orderby', 'id'),
            'sort' => $this->get('sort', 'asc')
        ];
    }

}