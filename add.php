 <?php
    $user = 'root';
    $password = 'root';
    $host = 'localhost';
    $port = 8889;
    
    $link = mysql_connect(
       "$host:$port", 
       $user, 
       $password
    );
     //testing connection success
     if(mysqli_connect_errno()) {
     die("DB connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"
     );
     }
    $db_selected = mysql_select_db("SongSearchEngin", $link);  

// GET PARAMS
$artistHtm = $_POST["artist"];
$songNameHtm = $_POST["songName"];
$lyricsHtm = $_POST["lyrics"];
// GET PARAMS

// ESCAPE STRINGS
$artist1 = mysql_real_escape_string($artistHtm);
$songName1 = mysql_real_escape_string($songNameHtm);
$lyrics1 = mysql_real_escape_string($lyricsHtm);
// ESCAPE STRINGS

// INSERT A NEW DOCUMENT
$temp = "INSERT INTO `files`(`artist`, `songname`, `lyrics`) VALUES ('$artist1','$songName1','$lyrics1');";
$temp_res = mysql_query($temp) or die('Query failed: ' . mysql_error());
// INSERT A NEW DOCUMENT

// GET ID of new DOCUMENT
$id = mysql_insert_id();
// GET ID of new DOCUMENT

// CLEAN STRING
$splitted = preg_split('#\s+#', $lyricsHtm, null, PREG_SPLIT_NO_EMPTY); 
// CLEAN STRING


// RUN ON EVERY KEYWORD
foreach ($splitted as $AA) {
    $isExists = 0;
	$AA = preg_replace("/[^ \w]+/", "", $AA);
	// CHECK IF KEYWORD EXISTS in DB
	$query = 'SELECT * FROM words WHERE word="'.$AA.'"';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	// CHECK IF KEYWORD EXISTS in DB
	
    if(mysql_num_rows($result)>0) { //keyword exists
        $query2 = 'SELECT file FROM words WHERE word = "'.$AA.'"'; // select documents of keyword
        $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
        $values2 = mysql_fetch_array($result2);
        $data   = preg_split('/\s+/',$values2[0]); // split whitespaces
        foreach ($data as $AA2) {
        	if ($AA2 == $id){ // document exists in keywords
               
        			$isExists = 1;
        	}
        }
        if ($isExists==0) { // update document if doesn't exists in list
       	$query3 = 'UPDATE words SET file = "'.$values2[0].' '.$id.'" WHERE word = "'.$AA.'"';
        $result3 = mysql_query($query3) or die('Query failed: ' . mysql_error());
        }
		$query1 = 'UPDATE words SET appearnum=appearnum+1  WHERE word = "'.$AA.'"'; // update shows of keyword
		$result = mysql_query($query1) or die('Query failed: ' . mysql_error());
	} else { // a new keyword
		$query4 = 'INSERT into words (word, appearnum, file) VALUES ("'.$AA.'", 1, "'.$id.'")';
        $result4 = mysql_query($query4) or die('Query failed: ' . mysql_error());
	}

}
$data = array("status" => "success");
header('Content-Type: application/json');
echo json_encode($data);
?>