<?php
require_once 'Slim/Slim.php';
require_once 'includes/util.php';
require_once 'includes/ResourceNotFoundException.php.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->config('debug', true);
$view = $app->view();
$view->setTemplatesDirectory('./templates');

/*$app->post('/addSubject', function () use ($app) {
	$newSubject = $app->request->post('newSubject');
	$conn = getConnection();
	$sql = "INSERT INTO subject (description) VALUES('$newSubject')";
	if ($conn->query($sql) === TRUE) {
		echo "<h5>New record created successfully</h5>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
	$app->redirect('listSubjects');
});*/
$app->get('/', function () use ($app){
    $app->redirect('home');
});

$app->get('/home', function () use ($app) {
    $app->render('home.php');
});

$app->get('/formQuiz', function () use ($app) {
    $app->render('formQuiz.php');
});



$app->get('/listSubjects', function () use ($app) {
	$data = getSubjects();
	print json_encode($data);
});

/*
$app->post('/addUser', function () use ($app) {
    $name = $app->request->post('name');
	$email = $app->request->post('email');
	$password = $app->request->post('password');
	$roleId = 1;
	$conn = getConnection();
	$sql = "INSERT INTO users (name, password, email, role_id)
		VALUES ('$name', '$password', '$email','$roleId')";
	if ($conn->query($sql) === TRUE) {
		echo "<h5>New record created successfully</h5>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
	$app->redirect('home');
});
*/
 
/*$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});*/
 
 $app->get('/users', function () use ($app) {  
   $data = getUsers(); 
   $app->response()->header('Content-Type', 'application/json');
   //print_r($data);
   $strJson = "[";
   foreach($data as $user)
	  $strJson .= json_encode($user).",";
   $strJson = substr($strJson,0,strlen($strJson)-1);
   $strJson .= "]";
   echo $strJson;
});
 
$app->get('/users/:id', function ($id) use ($app) {    
  try {
    $data = getUser($id);
	if ($data!=null) {
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode($data);
    } else {      
      throw new ResourceNotFoundException();
    }
  }catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
  }catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

$app->get('/subjects', function () use ($app) {  
   $data = getSubjects(); 
   $app->response()->header('Content-Type', 'application/json');
   echo json_encode($data);
});

$app->post('/user', function () use ($app) {    
  try {
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
       
    $username = (string)$input->name;
    $email = (string)$input->email;
    $password = (string)$input->password;
	addUser($username,$email,$password);
    $app->redirect("users");
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

$app->post('/addQuiz', function () use ($app) {    
  try {
    $request = $app->request();
    $body = $request->getBody();
    $input = json_decode($body); 
       
    $name = (string)$input->name;
    $subject_id = (string)$input->subject_id;
	
	addQuiz($name,$subject_id);
    $app->redirect("formQuestion");
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
});

$app->get('/formQuestion', function () use ($app) {
    $app->render('formQuestion.php');
});

$app->run();
?>
