<?php

class Crud_model extends CI_Model{

    public function db_log_error(){

    }

    public function email_exists($email){
        $this->db->select('mail');
        $this->db->from('user');
        $this->db->where('mail',$email);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return TRUE;
        else return FALSE;
    }

    /*------------------------------------------------ Get data from database -------------------------------------------*/

   

    public function db_get_users(){
        $this->db->select('*');
        $this->db->from('user');
        $result=$this->db->get();
        $results=$result->result_array();
        return $results;
    }
    public function db_get_activity($id){
        $this->db->select('*');
        $this->db->from('activity');
        $where['idActivity']=$id;
        $this->db->where($where);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results[0];
        else return NULL;
    }


    public function db_get_emails_unsent(){
        $this->db->select('*');
        $this->db->from('email_proxy');
        $this->db->where('sent',0 );
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results;
        else return null;
    }

    public function db_get_user($user_id){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('user_id',$user_id);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results[0];
        else return null;
    }

    public function db_get_users_in($column,$data_list){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where_in($column,$data_list);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results;
        else return null;
    }

    public function db_get_Material($where){
        $this->db->select('*');
        $this->db->from('Material');
        $this->db->where($where);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results;
        else return null;
    }

    public function db_get_user_material($where){
        $this->db->select('*');
        $this->db->from('material');
        $this->db->join('user','user.user_id=material.OwnerId');
        $this->db->where($where);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results;
        else return null;
    }




    public function db_get_locations($id=NULL){
        $this->db->select('*');
        $this->db->from('locations');
        /*if($id){
            $this->db->where('id',$id);
        }*/
        $result=$this->db->get();
        $results=$result->result_array();

        if(count($results)) return $results;
        else return null;
    }

    public function db_get_forgot_password($data){
        $this->db->select('*');
        $this->db->from('forgot_password');
        $this->db->where($data);
        $result=$this->db->get();
        $results=$result->result_array();
        if(count($results)) return $results[0];
        else return null;
    }

    public function db_get_ads($where=array(),$order_by="time_submitted",$order='desc'){
        $this->db->select('*');
        $this->db->from('ads');
        $this->db->where($where);
        $this->db->order_by($order_by,$order);
        $result=$this->db->get();
        $results=$result->result_array();
        //if(count($results))
            return $results;
        //else return array();
    }


    /*---------------------------------------------- insert data in database ------------------------------------------------------*/



    public function db_insert_email($data){
        $this->db->insert('email_proxy', $data);
        if($this->db->affected_rows()==1) return true;
        else return false;
    }


    public function db_insert_somth_custom($aid,$lid){  //custom query
        if(is_numeric($aid) && is_numeric($lid)){
            $results=$this->db->query("some custom query");
            if($this->db->affected_rows()>0) {
                return $this->db_some_custom_func($aid,$lid);
            }
            else {return false;}
        }
        return "FATAL ERROR, WRONG PARAMETERS PASSED";
    }


    public function db_insert_forgot_password($data){
        $this->db->insert('forgot_password', $data);
        if($this->db->affected_rows()>0) return $this->db->insert_id();
        else return null;
    }

    public function db_insert_material($data){
        $this->db->insert('Material', $data);
        if($this->db->affected_rows()==1) return true;
        else return false;
    }


    /*----------------------------------------------- update data in database ------------------------------------------------------*/

    public function db_update_email($id,$data){
        //ask database
        $this->db->where('id',$id);
        $this->db->update('email_proxy',$data);
        return $this->db->affected_rows();
    }

    public function db_update_somth($aid, $lid, $data){
        $this->db->where('aid', $aid);
        $this->db->where('lid', $lid);
        $this->db->update('some_table', $data);
        if($this->db->affected_rows()==1) return true;
        else return false;
        //var_dump($data);
        //return 'sdfsdf '.$aid.' '.$lid.' '.json_encode($data);

    }

    public function db_update_user_password($email,$pass,$salt){
        //ask database
        $this->db->where('mail',$email);
        $data['password']=$pass;
        $data['salt']=$salt;
        $this->db->update('user',$data);
    }

    public function db_update_user_data($user_id, $data){
        $this->db->where('user_id',$user_id);
        $this->db->update('user',$data);

        if($this->db->_error_number()==0) return true;
        else return false;
    }
    public function db_update_Material($where, $data){
        $this->db->where($where);
        $this->db->update('Material',$data);
        if($this->db->affected_rows()>0) return true;
        else return false;
        /*if($this->db->_error_number()==0) return true;
        else return false;*/
    }


    /*------------------------------------------------------miscelenious--------------------------------*/


    public function db_delete_forgot_password($hash){
        $data['hash']=$hash;
        $this->db->delete('forgot_password', $data);
        if($this->db->affected_rows()==1) return true;
        else return null;
    }

    public function db_delete_user($user_id){
        $where['user_id']=$user_id;
        $this->db->delete('user', $where);
        if($this->db->affected_rows()==1) return true;
        else return null;
    }


}