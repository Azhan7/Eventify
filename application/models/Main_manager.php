<?php
class main_manager extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
    ##insert function
    public function insert($data, $table) {
        if ($this->db->insert($table, $data)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function insert_get_id($data, $table) {
    	if ($this->db->insert($table, $data)) {
    		 $insert_id = $this->db->insert_id();
   			 $this->db->trans_complete();
   			 return  $insert_id;
    	} else {
    		return false;
    	}
    }
    public function update1($id,$data,$colname,$table)
    {
        $this->db->trans_start();
        $this->db->set($colname,$data);
        $this->db->where('id', $id);
        $this->db->update($table, $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    ##update
    public function update($id, $data, $table) {
      
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update($table, $data);
        $this->db->trans_complete();
      
        if ($this->db->trans_status() === FALSE) {
            return false;
        } 
        else {
            return true;
            }
    }
    
    public function update_col_name($id, $col_name, $data, $table) {
    	$this->db->trans_start();
    	$this->db->where('id', $id);
    	$this->db->update($table, $data);
    	$this->db->trans_complete();
    	if ($this->db->trans_status() === FALSE) {
    	    return false;
    	} else {
    	    return true;
    	}
    }
    ##delete
    public function delete($id, $table) {
        $this->db->where('id', $id);
        if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function delete_by_other_id($col, $col_val, $table) {
    	$this->db->where($col, $col_val);
    	if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }
    ##select all 
    public function select_all($table) {
        $this->db->select("*")
                ->from($table)
                ->order_by("id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }
    
    public function select_all_row_order($table,$row,$order) {
    
    	$this->db->select("*")
    	->from($table)
    	->order_by($row, $order);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
    		return $query->result_array();
    	} else {
    		return 0;
    	}
    }
	
    ##pagination count
    public function record_count($table) {
        return $this->db->count_all($table);
    }

    public function record_count_by_col($col, $col_val, $table) {

        $this->db->where($col, $col_val);
        $this->db->from($table);
        $count = $this->db->count_all_results();
        return $count;
    }
    ##select all for pagination
    public function select_pagination($limit, $start, $table) {
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    ## select all if status is 1
    public function select_all_status($table) {
        $this->db->where('status', 1);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    ## select by id
    public function select_by_id($id, $table) {
        $this->db->where('id', $id)
        ->order_by('id', 'asc');
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }
    ##count check specifically for delete check either value is present in other table
    public function delete_count($col, $col_val, $table) {
        $this->db->where($col, $col_val);
        $this->db->from($table);
        $count = $this->db->count_all_results();
        return $count;
    }
    public function delete_count_two_columns($col_one, $col_val_one, $col_two, $col_val_two, $table) {
        $this->db->where($col_one, $col_val_one);
        $this->db->where($col_two, $col_val_two);
        $this->db->from($table);
        $count = $this->db->count_all_results();
        return $count;
    }
    ##get all for specic define foreign key id 
    public function select_by_other_id($col, $col_val, $table) {
        $this->db->where($col, $col_val)
        ->order_by('id', 'asc');
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }
    public function select_other_coulmn_join_name_by_other_id($other_id, $other_val, $main_table,$join_to_id,$join_by_id,$join_table,$name_wanted) {
        $query = $this->db->query('SELECT a.*,b.'.$name_wanted.' AS name FROM '.$main_table.' a  INNER JOIN '.$join_table.' b 
        ON a.'.$join_to_id.' = b.'.$join_by_id.'   
        WHERE a.'.$other_id.' = '.$other_val.'');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function select_distinct_in_column($column,$table) {
        $query = $this->db->query('SELECT DISTINCT '.$column.' FROM '.$table.'');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }
    
    public function select_by_other_id_active($col, $col_val, $table) {
    	$this->db->where($col, $col_val);
    	$this->db->where('is_active','1')
        ->order_by('id', 'asc');
    	$query = $this->db->get($table);
    	if ($query->num_rows() > 0) {
    		return $query->result_array();
    	} else {
    		return 0;
    	}
    }

    public function select_by_other_id_status($col, $col_val,$status_col,$status_col_val, $table) {
        $this->db->where($col, $col_val);
        $this->db->where($status_col,$status_col_val)
        ->order_by('id', 'asc');
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function select_limit_status($limit, $table) {
        $this->db->select('*')
                ->from($table)
                ->where("status", 1)
                ->limit($limit)
                ->order_by("id", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function record_count_join($table1, $table2, $col1, $col2) {
        $this->db->select('count(*) as total')
                ->from($table1)
                ->join($table2, $table1 . "." . $col1 . "=" . $table2 . "." . $col2, 'inner');
        $query = $this->db->get();
        $count = $query->row_array();
        return $count['total'];
    }
    public function convert_text($text) {
        $t = $text;
        $specChars = array('%25' => '','%3C' => '','%3E' => '','%5E' => '','%7C' => '','%60' => '','%'   => '','<'   => '','>'   => '','^'   => '','`'   => '','|'   => '','??'  => '','???'  => '','???'  => '','???'  => '','???'  => '','??'  => 'S','???'  => '','??'  => 'CE','??'  => 'Z','???'  => '','???'  => '','???'  => '','???'  => '','???'  => '.','???'  => '','??'  => 's','???'  => '','??'  => 'ce','??'  => 'Z','??'  => 'Y','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => '','??'  => 'A','??'  => 'A','??'  => 'A','??'  => 'A','??'  => 'A','??'  => 'A','??'  => 'AE','??'  => 'C','??'  => 'E','??'  => 'E','??'  => 'E','??'  => 'E','??'  => 'I','??'  => 'I','??'  => 'I','??'  => 'I','??'  => 'D','??'  => 'N','??'  => 'O','??'  => 'O','??'  => 'O','??'  => 'O','??'  => 'O','??'  => 'x','??'  => '','??'  => 'U','??'  => 'U','??'  => 'U','??'  => 'U','??'  => 'Y','??'  => '','??'  => '','??'  => 'a','??'  => 'a','??'  => 'a','??'  => 'a','??'  => 'a','??'  => 'a','??'  => 'ae','??'  => 'c','??'  => 'e','??'  => 'e','??'  => 'e','??'  => 'e','??'  => 'i','??'  => 'i','??'  => 'i','??'  => 'i','??'  => '','??'  => 'n','??'  => 'o','??'  => 'o','??'  => 'o','??'  => 'o','??'  => 'o','??'  => '','??'  => '','??'  => 'u','??'  => 'u','??'  => 'u','??'  => 'u','??'  => 'y','??'  => '','??'  => 'y', );
            foreach ($specChars as $k => $v) {
                $t = str_replace($k, $v, $t);
            }
            return $t;
        }    
}
?>