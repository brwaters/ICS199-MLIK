<?php 

function checkNameProdEntry($desc, $dbc)	{
		
		if (isset(desc)	{
		// returned true, ADD DB DESCRIPTIONS TO ARRAY
		$rows = [];
		$query = "SELECT Description FROM ICS199Group07_dev.DESCRIPTIONS";

		$result = mysqli_query($dbc, $query;)
		

		if (!$result) { 
			die("Query Failed."); 
		}

		while($row = mysqli_fetch_array($result))	{
		    $rows[] = $row;
		}

		if (in_array($desc, $rows))	{
			return false;
		}

	}	else 	{
		return false;
		// error handle this, adding in error code to show name cannot be null
	}
}

function checkNameProdEntry()	{
				// if not blank returns true
	if (isset(desc)	{
		// returned true, ADD DB DESCRIPTIONS TO ARRAY
		$rows = [];
		$query = "SELECT Description FROM ICS199Group07_dev.DESCRIPTIONS";

		$result = mysqli_query($dbc, $query;)
		if (!$result) { 
			die("Query Failed."); 
		}

		while($row = mysqli_fetch_array($result))	{
		    $rows[] = $row;
		}

		if (in_array($desc, $rows))	{
			return false;
		}

	}	else 	{
		return false;
		// error handle this, adding in error code to show name cannot be null
	}
}

function checkPriceProdEntry($input)	{
	// acceptable: 1, 1.0, 1.23 
	// not acceptable 1.234 

	if (preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $input)) 
	{ 
	  return true;
	} 
	else 
	{ 
	  return false;
	} 
}
?>
