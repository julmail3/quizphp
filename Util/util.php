<?phpfunction getConnection(){$servername = "localhost";$username = "root";$password = "";$dbname = "slim";return new mysqli($servername, $username, $password, $dbname);}function getSubjects(){	$conn = getConnection();	$sql = "SELECT * FROM subject ORDER BY description ";	$result = $conn->query($sql);		if ($result->num_rows > 0) {		$data = array();        while($row = $result->fetch_assoc()) {			$data[] = $row;		}		return $data;	}	return null;}function getUsers(){	$conn = getConnection();	$sql = "SELECT * FROM users ORDER BY name ";	$result = $conn->query($sql);	if ($result->num_rows > 0) {		$data = array();        while($row = $result->fetch_assoc()) {			$data[] = $row;		}		return $data;	}	return null;}function getUser($id){	$conn = getConnection();	$sql = "SELECT * FROM users WHERE id=$id ORDER BY name ";	$result = $conn->query($sql);		if ($result->num_rows > 0) {		$data = array();        $row = $result->fetch_assoc();		$data[] = $row;		return $data;	}		return null;	}function addUser($name,$email,$password){		$conn = getConnection();	$sql = "INSERT INTO users (name,email,password) VALUES('$name','$email','$password')";	if ($conn->query($sql) === TRUE) {		echo "<h5>New record created successfully</h5>";	} else {		echo "Error: " . $sql . "<br>" . $conn->error;	}	$conn->close();	}?>