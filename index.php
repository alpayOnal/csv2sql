<?php

header('content-type:text/html;charset=utf-8');
$r=$_REQUEST;
$f=$_FILES;

function risset($r,$vars){
	foreach($vars as $var)
		if(!isset($r[$var]))
			return false;

	return true;
}

$rqFields=array('fdelimiter','tdelimiter','table','cfields','columns');

if(risset($r,$rqFields) && isset($f['csvfile'])){
	$cfile=fopen($f['csvfile']['tmp_name'],'r');

	$sql='insert into '.$r['table'].' ('.$r['columns'].') values'."\n";
	$r['cfields']=explode(',',$r['cfields']);

	while($row=fgetcsv($cfile,0,$r['fdelimiter'],$r['tdelimiter'])){
		$selFields=array();
		foreach($r['cfields'] as $i)
			$selFields[]=addslashes(strip_tags($row[$i]));

		$sql.='(\''.implode('\',\'',$selFields).'\'),'."\n";
	}

	$sql=mb_substr($sql,0,-2).';';
	echo '<textarea rows="10" cols="80">'.$sql.'</textarea>';
}


?>
<form action="?" method="POST" enctype="multipart/form-data">
<ul>
	<li>
		<label for="csvfile">CSV File :</label>
		<input type="file" name="csvfile" value="" id="csvfile" />
	</li>
	<li>
		<label for="fdelimiter">Field Delimiter :</label>
		<input type="text" name="fdelimiter" value="," id="fdelimiter" />
	</li>
	<li>
		<label for="tdelimiter">Text Delimiter :</label>
		<input type="text" name="tdelimiter" value="'" id="tdelimiter" />
	</li>
	<li>
		<label for="table">Table Name</label>
		<input type="text" name="table" value="table_name" id="table" />
	</li>
	<li>
		<label for="cfields">Selected Fields</label>
		<input type="text" name="cfields" value="1,2,4" id="cfields" />
	</li>
	<li>
		<label for="columns">Table Columns</label>
		<input type="text" name="columns" value="id,title,description" id="columns">
	</li>
	<li>
		<input type="SUBMIT" value="Convert" />
	</li>

</ul>
</form>
