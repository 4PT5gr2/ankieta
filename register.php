<?php

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	
	exit('Uzupełnij formularz');
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Uzupełnij formularz');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email się nie zgadza');
}
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Zła nazwa użytkownika');
}
if (strlen($_POST['password']) > 30 || strlen($_POST['password']) < 5) {
	exit('Hasło musi posiadać od 5 do 30 znaków ');
}

if ($stmt = $con->prepare('SELECT id, password FROM user WHERE username = ?')) {
	
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	
	if ($stmt->num_rows > 0) {
		
		echo 'Ta nazwa użytkownika jest zajęta, spróbuj innej';
	} else {
		
        if ($stmt = $con->prepare('INSERT INTO user (username, password, email) VALUES (?, ?, ?)')) {
	
	        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	        $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
	        $stmt->execute();
	        
			header('Location: index.html');
    } else {
	
	        echo 'Error';
}
        
	}
	$stmt->close();
} else {
	
	echo 'Error';
}
$con->close();
?>