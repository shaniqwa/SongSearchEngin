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
        $songName = $_POST["songName"];
        // GET PARAMS
        // ESCAPE STRINGS
        $songName = mysql_real_escape_string($songName);
        // ESCAPE STRINGS


        // CLEAN STRING
        //$songName = preg_split('#\s+#', $songName, null, PREG_SPLIT_NO_EMPTY); 
        // CLEAN STRING
        // GET ID OF SONG BY NAME
        $query = 'SELECT id FROM files WHERE songName="'.$songName.'"';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        // GET ID OF SONG BY NAME
        $id = mysql_fetch_array($result);
        // echo "deleting song name:".$songName." id of song:".$id[0];
        $query1 = 'DELETE FROM files WHERE id = '.$id[0];
        $result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
        
        $query2 = 'UPDATE SongSearchEngin.words SET file = replace(file, " '.$id[0].'", "") WHERE file LIKE "%'.$id[0].'%"';
        $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());

        
$data = array("status" => "success");
header('Content-Type: application/json');
echo json_encode($data);
               
?>