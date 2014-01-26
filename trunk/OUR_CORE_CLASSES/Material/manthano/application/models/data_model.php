<?php

Class Data_model extends Crud_model{

    public function construct(){
        parent::construct();
    }

    private function get_cache_key($key){
        $cache_prefix=config_item('cache_prefix');
        $results=$this->cache->get($cache_prefix.$key);
        if ($results==NULL){
            //echo 'PAGE MISS 1';
            $results=$this->cache->get($cache_prefix.$key);
        }
        return $results;
    }

    public function delete_cache_key($key){
        $cache_prefix=config_item('cache_prefix');
        $results=$this->cache->delete($cache_prefix.$key);
    }

    

    public function get_locations($id=NULL){

        $data_prefix_name='locations';
        $results=$this->get_cache_key($data_prefix_name);
        if($results==NULL){
            $query_results=$this->db_get_locations();
            if($query_results) foreach($query_results as $query_result){
                $results[$query_result['id']]=$query_result;
            }
            cache_add($data_prefix_name,$results, 1000);
        }
        if(count($results)){
            if($id) return $results[$id];
            return $results;
        }
        else{
            return null;
        }
    }

    public function get_something($id){ //rly put somth here
        $data_prefix_name='ad_';
        $results=$this->get_cache_key($data_prefix_name.$id);
        if($results==NULL){
            //echo "CACHE MISS TOTAL. GETTING FROM DB";
            $results=$this->db_get_something($id);
            cache_add($data_prefix_name,$results, 1000);
        }
        if(count($results)) return $results;
        else return null;
    }

    

    public function insert_newsletter_subscriptions($data){

        if($this->db_insert_newsletter_subscriptions($data)) return true;
        else return false;
    }


    

    public function get_latest_news(){
        $data_prefix_name='latest_news';
        $results=$this->get_cache_key($data_prefix_name);
        if($results==NULL){
            $results=$this->db_get_latest_news();
            cache_add($data_prefix_name,$results, 1800);
        }
        if(count($results)) return $results;
        else return null;
    }

}

