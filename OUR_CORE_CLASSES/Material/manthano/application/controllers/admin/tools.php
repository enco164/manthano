<?class Tools extends MY_Controller {



    public function __construct(){
        parent::__construct();
    }

    public function index(){
        echo "Alati";
    }

    public function memcache_clear(){

        //$this->cache->flush();
        if($this->cache->clean())
            echo "Ociscen cache";
        else
            echo "Nije uspelo ciscenje cache-a";
    }

}