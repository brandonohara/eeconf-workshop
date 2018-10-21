<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop_ext {

    var $name           = 'Workshop API';
    var $version        = '1.0.0';
    var $description    = 'API for Orders';
    var $settings_exist = 'n';
    var $docs_url       = '';

    var $settings       = array();

    public function __construct($settings = '') {
        $this->settings = $settings;
    }

    /*
     * Compare the first segment against Workshop API namespace, and if it matches,
     * load the library matching segment 2, and calling the method of segment 3.
    */
    public function route_api($session) {

        if (strtolower(ee()->uri->segment(1)) === 'api') {

            // if (!$session->userdata('group_id') || $session->userdata('group_id') > 1) {
            //     echo json_encode([
            //         'code' => 500,
            //         'message' => 'Must be Super Admin in to use API',
            //         'data' => []
            //     ]);
            //     die();
            // }

            //get the model
            $controller = strtolower( ee()->uri->segment(2) );
            $method = strtolower( ee()->uri->segment(3) );
            $model_id = strtolower( ee()->uri->segment(4) );

            ee()->load->library($controller, null, 'workshop');
            ee()->workshop->call_method($method, $model_id);

            die();
        }
    }

    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see https://ellislab.com/codeigniter/user-guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    public function activate_extension() {
        $this->settings = array(
            'api_namespace'   => 'api'
        );


        $data = array(
            'class'     => __CLASS__,
            'method'    => 'route_api',
            'hook'      => 'sessions_end',
            'settings'  => serialize($this->settings),
            'priority'  => 10,
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        ee()->db->insert('extensions', $data);
    }

    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    public function update_extension($current = '') {
        if ($current == '' OR $current == $this->version) {
            return FALSE;
        }

        ee()->db->where('class', __CLASS__);
        ee()->db->update('extensions', array('version' => $this->version));
    }

    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    public function disable_extension() {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }
}
// END CLASS