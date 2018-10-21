<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop_upd {
    public $version = '1.0.0';
    public $module_name = 'Workshop';
    
    function __construct() { 
        ee()->load->dbforge();
    }

    /**
     * Creates a DB table wxp_workshop_orders that's filled with fake Orders
     */
    function install() {
        //Create a row in the modules table to let EE know our module is installed
        $data = array(
            'module_name' => $this->module_name,
            'module_version' => $this->version,
            'has_cp_backend' => 'n',
            'has_publish_fields' => 'n'
        );
        ee()->db->insert('modules', $data);
        
        //Create the exp_workshop_orders table
        $this->install_workshop_orders();

        //Fill the exp_workshop_orders table with fake Orders
        $this->seed_workshop_orders();
        
        return true;
    }
    
    /**
     * Using DB Forge helper, create exp_workshop_orders table
     */
    protected function install_workshop_orders() {
        $fields = array(
            'id'            => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'status'        => array('type' => 'varchar', 'constraint' => '255'),
            'subtotal'      => array('type' => 'float'),
            'shipping'      => array('type' => 'float'),
            'total'         => array('type' => 'float'),
            'name'         => array('type' => 'varchar', 'constraint' => '255'),
            'notes'         => array('type' => 'varchar', 'constraint' => '1000', 'null' => true, 'default' => null),
            'created_at'    => array('type' => 'timestamp', 'default' => null, 'null' => true),
            'updated_at'    => array('type' => 'timestamp', 'default' => null, 'null' => true)
        );

        ee()->dbforge->add_field($fields);
        ee()->dbforge->add_key('id', TRUE);

        ee()->dbforge->create_table('workshop_orders');
    }


    /**
     * Fill the exp_orders_table with fake data
     */
    protected function seed_workshop_orders($rows = 100) {
        for ($i = 0; $i < $rows; $i++) {
            //total should equal the sum of subtotal and shipping
            $subtotal = rand(100, 100000) / 100;
            $shipping = rand(100, 1000) / 100;
            $total = $subtotal + $shipping;

            //Pick a random date between now and the last 5 years
            $date = date('Y-m-d G:i:s', rand(time() - (60*60*24*365*5), time()));

            $order = array(
                'status' => $this->selectRandom(['open', 'processing', 'shipped', 'delivered', 'canceled', 'failed', 'closed']),
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'name' => $this->selectRandom($this->names()),
                'created_at' => $date,
                'updated_at' => $date
            );

            ee()->db->insert('workshop_orders', $order);
        }
    }

    /**
     * Select a random item from an array
     */
    protected function selectRandom($arr) {
        $count = count($arr);
        return $arr[rand(0, $count - 1)];
    }

    /**
     * Return an array of names
     */
     protected function names() {
        return ['Kassie Muench', 'Juliana Delaney', 'Arnoldo Decicco', 'Leesa Sturgeon', 'Lizeth Kincheloe', 'Gustavo Reveles', 'Roseline Mansir', 'Shaunda Mize', 'Lou Berard', 'Cammie Slavens', 'Reba Macauley', 'Kenyetta Rozar', 'Dung Dunford', 'Kelsey Laine', 'Tai Cue', 'Sherita Ristau', 'Lona Emond', 'Zada Purington', 'Deana Elrod', 'Manuel Charley', 'Jene Locklin', 'Ezequiel Paras', 'Delma Majors', 'Rosanna Wenrich', 'Cyndy Hammaker', 'Janeen Pollak', 'Annmarie Daye', 'Steve Defalco', 'Marian Tegeler', 'Ellena Dopp', 'Becky Marson', 'Marilyn Kellen', 'Cassi Sapp', 'Piper Ocampo', 'Risa Richman', 'Lori Ezell', 'Georgiann Dealba', 'Darron Simas', 'Anjanette Bartell', 'Etha Timmer', 'Evie Flecha', 'Diamond Clepper', 'Kallie Knapik', 'Booker Barcenas', 'Rich Cipolla', 'Jenine Mefford', 'Hsiu Vetrano', 'Cherish Tomaszewski', 'Phyllis Forester', 'Carey Lalor', 'Patrice Kahle', 'Leonard Sauders', 'Kenyatta Sauers', 'Sonya Mattern', 'Sterling Newhall', 'Merilyn Quisenberry', 'Margene Van', 'Moshe Repp', 'Manda Jim', 'Rickie Jenks', 'Diana Viola', 'Gay Whitsett', 'Bebe Berlin', 'Jesse Whitis', 'Clarice Kulpa', 'Erich Sones', 'Rema Saulters', 'Joycelyn Hohn', 'Herbert Beddingfield', 'Hildegard Fowles', 'Myron Calcagni', 'Celia Gawronski', 'Ronna Asmus', 'Aracelis Hayslip', 'Lesha Rousselle', 'Felisa Langsam', 'Booker Mineau', 'Wei Henneman', 'Iva Stach', 'Charis Whitner', 'Lettie Jone', 'Alta Geary', 'Salvador Nickell', 'Ima Gramling', 'Reuben Ritch', 'Tia Mundo', 'Zelma Lavergne', 'Perla Baumert', 'Debroah Pletcher', 'Moon Helsel', 'Marvella Bolger', 'Ellyn Melle', 'Margy Crisci', 'Blanca Parrales', 'Caryn Blakney', 'Omega Mccready', 'Clemencia Trice', 'Melida Gregerson', 'Tami Fullam', 'Cheree Abdalla'];
    }
    
    /**
     * Delete the row from exp_modules to let EE know our module is uninstalled
     *
     * Delete the exp_workshop_orders table.
     */
    public function uninstall() {
        //Get the module_id of our module
        $query = ee()->db->select('module_id')->get_where('modules', array('module_name' => $this->module_name));
        
        //remove the row from exp_modules that correlates to our module
        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('modules');
    
        //delete exp_workshop_orders
        ee()->dbforge->drop_table('workshop_orders');
        
        return TRUE;
    }
    
    /**
     * If we were to make changes to previous versions of this module, they'd be made here.
     */
    public function update($current = '') {
        if ($current == '' OR $current == $this->version) {
            return FALSE;
        }
        
        return FALSE;
    }
}