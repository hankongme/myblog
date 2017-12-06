<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model  {
	
	public function __construct() {
		parent::__construct();

	}
	
	public static function getRows( $args )
	{
	   header("Content-type: text/html; charset=utf-8");
       $table = with(new static)->table;
	   $key = with(new static)->primaryKey;
	   
        extract( array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> '' ,
			'order' 	=> '' ,
			'params' 	=> '' ,
			'global'	=> '1'	  
        ), $args ));
		
		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		$connection = with(new static)->connection;
		if($global == 0 )
				$params .= " AND {$table}.entry_by ='".Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1			
        
		$rows = array();
	    $result = DB::connection($connection)->select( self::querySelect() . self::queryWhere(). " 
				{$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ");
		
		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }	
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() ); 	
		//echo 	$counter_select; exit; 
		$res = DB::connection($connection)->select( $counter_select . self::queryWhere()." {$params} ". self::queryGroup());
		$total = $res[0]->total;


		return $results = array('rows'=> $result , 'total' => $total);	

	
	}
	
	public static function getRow( $id )
	{
       $table = with(new static)->table;
	   $key = with(new static)->primaryKey;
	   $connection = with(new static)->getConnectionName();

		$result = DB::connection($connection)->select(
				self::querySelect() . 
				self::queryWhere().
				" AND ".$table.".".$key." = '{$id}' ". 
				self::queryGroup()
			);	
		if(count($result) <= 0){
			$result = array();		
		} else {

			$result = $result[0];
		}
		return $result;		
	}	 
	

    static function getComboselect( $params , $limit =null)
    {   
        $limit = explode(':',$limit);
        if(count($limit) >=3)
        {
            $table = $params[0]; 
            $condition = $limit[0]." `".$limit[1]."` ".$limit[2]." ".$limit[3]." "; 
            $row =  DB::select( "SELECT * FROM ".$table." ".$condition);
        }else{
            $table = $params[0]; 
            $row =  DB::table($table)->get();
        }

        return $row;


    }	
		
	
	static function getColumnTable( $table )
	{	  
        $columns = array();
		$connection = with(new static)->getConnectionName();
	    foreach(DB::connection($connection)->select("SHOW COLUMNS FROM $table") as $column)
        {
           //print_r($column);
		    $columns[$column->Field] = '';
        }
	  

        return $columns;
	}	
	
	static function makeInfo( $id )
	{	
		$row =  DB::table('tb_module')->where('module_name', $id)->get();
		$data = array();
		foreach($row as $r)
		{
			$data['id']		= $r->module_id; 
			$data['title'] 	= $r->module_title; 
			$data['note'] 	= $r->module_note; 
			$data['table'] 	= $r->module_db; 
			$data['key'] 	= $r->module_db_key;
			$data['config'] = SiteHelpers::CF_decode_json($r->module_config);
			$field = array();	
			foreach($data['config']['grid'] as $fs)
			{
				foreach($fs as $f)
					$field[] = $fs['field']; 	
									
			}
			$data['field'] = $field;	
					
		}
		return $data;
			
	
	} 
	
	static function getTableList( $db ) 
	{
	 	$t = array(); 
		$dbname = 'Tables_in_'.$db ; 
		foreach(DB::select("SHOW TABLES FROM {$db}") as $table)
        {
		    $t[$table->$dbname] = $table->$dbname;
        }	
		return $t;
	}	
	
	static function getTableField( $table ) 
	{
        $columns = array();
	    foreach(DB::select("SHOW COLUMNS FROM $table") as $column)
		    $columns[$column->Field] = $column->Field;
        return $columns;
	}

    static function getDBList()
    {
        $connections = Config::get('database.connections');

        $dbs = [];
        foreach($connections as $k=>$v){
            if($v['driver'] === 'mysql'){
                $dbs[$k] = $k;
            }
        }
        return $dbs;
    }
	
	function getColoumnInfo( $result )
	{
		$pdo = DB::getPdo();
		$res = $pdo->query($result);
		$i =0;	$coll=array();	
		while ($i < $res->columnCount()) 
		{
			$info = $res->getColumnMeta($i);			
			$coll[] = $info;
			$i++;
		}
		return $coll;	
	
	}
	
	function builColumnInfo( $statement )
	{
		$driver 		= Config::get('database.default');
		$database 		= Config::get('database.connections');
		$db 		= $database[$driver]['database'];
		$dbuser 	= $database[$driver]['username'];
		$dbpass 	= $database[$driver]['password'];
		$dbhost 	= $database[$driver]['host'];
		
		$data = array();				
		$mysqli = new mysqli($dbhost,$dbuser,$dbpass,$db);
		if ($result = $mysqli->query($statement)) {
		
			/* Get field information for all columns */
			while ($finfo = $result->fetch_field()) {
				$data[] = (object) array(
							'Field'	=> $finfo->name,
							'Table'	=> $finfo->table,
							'Type'	=> $finfo->type
							);
			}
			$result->close();
		}
		
		$mysqli->close();
		return $data;
	
	}	
	
	function validAccess( $id)
	{

		$row = DB::table('tb_groups_access')->where('module_id','=', $id)
				->where('group_id','=', Session::get('gid'))
				->get();
		
		if(count($row) >= 1)
		{
			$row = $row[0];
			if($row->access_data !='')
			{
				$data = json_decode($row->access_data,true);
			} else {
				$data = array();
			}	
			return $data;		
			
		} else {
			return false;
		}			
	
	}	

}
