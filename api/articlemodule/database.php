<?php
	
	function escapeSQL ($str) 
	{
    	if (is_null ($str))
        	return null;
      	
        if (is_array ($str)) 
		{
			$result = array();
        	foreach ($str as $name => $value) 
			{
          		$result[$name] =  escapeSQL ($value);
        	}
        	return $result;
      	}
	    return strval ($str);
    }

    function escapeSQLSearchPattern ($str) 
	{
    	if (is_null ($str))
        	return null;
      	
        if (is_array ($str)) 
		{
        	$result = array();
        	foreach ($str as $name => $value) 
			{
          		$result[$name] =  escapeSQLSearchPattern ($value);
        	}
        	return $result;
      	}
	    @assert (is_scalar ($str));
    	$rv =  escapeSQL ($str);
      	$rv = preg_replace ('/([\\\\_%])/', '\\\\\\\\\\\\\\1', $rv);
      	return $rv;
    }

   	function escapeSQLString ($str) 
	{
    	if (is_null ($str))
      		return 'NULL';
    	return "'" .  escapeSQL ($str) . "'";
    }

    function escapeSQLTimestamp ($ts) 
	{
    	if (is_null ($ts))
      		return 'NULL';
    	$escapedTimeString = gmstrftime ($GLOBALS['DATABASE__PHP_TIME_FORMAT'], $ts);
    	return "TO_TIMESTAMP ('$escapedTimeString', '${GLOBALS['DATABASE__DB_TIME_FORMAT']}')";
    }

    function createSQLParameter ($arg, $as_string = true) 
    {
    	if (is_null ($arg))
        	return 'NULL';
      	if ($as_string)
        	return  escapeSQL (strval ($arg));
      	return strval ($arg);
    }

    function createInsertCommand ($table, $content) 
	{
	  
    	@assert (is_string ($table));
      	@assert (strlen ($table) > 0);
      	@assert (is_array ($content));
      	@assert (count ($content) > 0);
      	$keys = '';
      	$values = '';
      	$comma = '';
      	foreach ($content as $key => $value) 
		{
        	$value =  escapeSQL ($value);
        	$keys .= "$comma$key";
        	$values .= "$comma'$value'";
        	$comma = ", ";
      	}
	
      	$sql = "INSERT INTO $table ($keys) VALUES ($values)";
      	return $sql;
		
    }

    function createUpdateCommand ($table, $content, $match = null) 
	{
    	@assert (is_string ($table));
    	@assert (strlen ($table) > 0);
    	@assert (is_array ($content));
    	@assert (count ($content) > 0);
    	$sql = "UPDATE $table SET ";
    	$where = '';
    	$exclude_keys = array();
    	if (!is_null ($match)) 
    	{
        	if (is_array ($match)) 
        	{
          		if (count ($match) > 0) 
          		{
            		$where = " WHERE ";
            		$and = '';
            		foreach ($match as $key => $value) 
            		{
		            	@assert (is_string ($key));
		              	@assert (is_scalar ($value));
		              	$exclude_keys[$key] = true;
		              	$value =  escapeSQL ($value);
		              	$where .= "$and$key = '$value'";
		              	$and = " AND ";
            		}
          		}
        	} 
        	else 
        	{
          		@assert (is_string ($match));
          		if (strlen ($match) > 0)
            		$where = " WHERE $match";
        	}
      	}
      
      	$field_count = 0;
      	$comma = '';
      	foreach ($content as $key => $value) 
      	{
        	@assert (is_string ($key));
        	if (array_key_exists ($key, $exclude_keys))
          		continue;
        	if (is_null ($value))
          		$sql .= "$comma$key = NULL";
        	else 
        	{
          		@assert (is_scalar ($value));        
          		$notam_list = array();
          		$value =  escapeSQL ($value);
          		$sql .= "$comma$key = '$value'";
        	}
        	$comma = ', ';
        	++$field_count;
      	}
      	@assert ($field_count > 0);
      	$sql .= $where;
      	return $sql;
	}

    function createDeleteCommand ($table, $match = null) 
    {
    	@assert (is_string ($table));
      	@assert (strlen ($table) > 0);
      	$sql = "DELETE FROM $table";
      	if (is_array ($match)) 
      	{
       		$search_cond = '';
        	$and = '';
        	$cond_count = 0;
        	foreach ($match as $key => $value) 
        	{
          		@assert (is_string ($key));
          		@assert (strlen ($key) > 0);
          		@assert (is_scalar ($value));
          		$value =  escapeSQL ($value);
          		$search_cond .= "$and$key = '$value'";
          		$and = " AND ";
          		++$cond_count;
        	}
        	if ($cond_count > 0)
          		$sql .= " WHERE $search_cond";
      	} 
      	else if (strlen (trim ($match)) > 0) 
      	{
        	$sql .= " WHERE $match";
      	}
      	return $sql;
    }


    function query ($sql) 
    {
      $link = mysqli_connect("localhost", "root","", "ubijourn_JOHS");

		  $dbq = mysqli_query ($link, $sql);
      	@assert ($dbq);
     	return $dbq;
    }

    function getRowCount ($dbq, $dbc = null) 
    {
    	$count = mysqli_num_rows ($dbq);
      	return $count;
    }

    function getAffectedRowCount ($dbq) 
    {
    	$count = mysqli_affected_rows ($dbq);
      	return $count;
    }
    
    function command ($sql) 
    {
      $link = mysqli_connect("localhost", "root","", "ubijourn_JOHS");
		$dbq = mysqli_query ($link, $sql);
      	@assert ($dbq);
		return $dbq;
    }


    function fetch ($dbq, $row_id = null) 
    {
    	if (is_null ($row_id))
       		$row = mysqli_fetch_array ($dbq);
      	else
        	$row = mysqli_fetch_array ($dbq, $row_id);
      	if ($row) 
      	{
        	$buf = '';
        	$comma = '';
        	foreach ($row as $key => $value) 
        	{
          		if (is_int ($key))
            		continue;
          		$buf .= "$comma$key=$value";
          		$comma = ', ';
        	}
		}
      	return $row;
    }

        function fetch_assoc ($dbq, $row_id = null) 
    {
      if (is_null ($row_id))
          $row = mysqli_fetch_assoc ($dbq);
        else
          $row = mysqli_fetch_assoc ($dbq, $row_id);
        if ($row) 
        {
          $buf = '';
          $comma = '';
          foreach ($row as $key => $value) 
          {
              if (is_int ($key))
                continue;
              $buf .= "$comma$key=$value";
              $comma = ', ';
          }
    }
        return $row;
    }

    function fetchAll ($dbq, $start = 0, $count = null) 
    {
    	$result = array();
      	$row_id = $start;
      	if (is_null ($count))
        	$end = mysqli_num_rows ($dbq);
      	else
        	$end = min ($start + $count, mysqli_num_rows ($dbq));
		
      	while (true) 
      	{
			if ($row_id >= $end)
          		break;
        	if (!($row = mysqli_fetch_array ($dbq, $row_id)))
          		break;
        	array_push ($result, $row);
        	++$row_id;
      	}
      	return $result;
    }

    function insert ($table, $content) 
    {  alert($content);
    	$sql =  createInsertCommand ($table, $content);
	  	$status =  command ($sql);
      	@assert ($status);
	  	return $status;
    }
	
   
    function update ($table, $content, $match) 
    {
    	$sql =  createUpdateCommand ($table, $content, $match);
	  
	  	$status =  command ($sql);
      	@assert ($status);
	  	return $status;
    }

    function delete ($table, $match) 
    {
    	$sql =  createDeleteCommand ($table, $match);
      	$status =  command ($sql);
      	@assert ($status);
	  	return $status;
    }
?>