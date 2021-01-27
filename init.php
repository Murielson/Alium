<?php
session_start();

$pdo = null;

function connectDatabase()
{
  if ($GLOBALS['pdo'] !== null) {
    return;
  }
  $host = 'localhost';
  $db = 'alium';
  $user = 'root';
  $pass = '';
  $dsn = "mysql:host=$host;dbname=$db;charset=utf8";

  $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
    $GLOBALS['pdo'] = new PDO($dsn, $user, $pass, $opt);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
connectDatabase();

function isLogged($user)
{
  return $user;
}

function isAdmin()
{
  return isset($_SESSION['logged-user']) && isset($_SESSION['admin']);
}

function login($username, $password)
{
  $usrnm = trim(addslashes($username));
  $passwd = sha1(trim(addslashes($password)));

  $query = "SELECT * FROM usuario WHERE `email` = ? OR `username` = ? AND `password` = ?";
  
  $stmt = $GLOBALS['pdo']->prepare($query);
  
  $stmt->execute([$usrnm, $usrnm, $passwd]);
  $user = $stmt->fetch();
 
  $row = $stmt->rowCount();

  if ($row == 1) {
    // isLogged($user);
    return $user;
  }
  return false;
}

if (login('username', 'password')) {
  echo "credenciais inválidas";
} else {
  $teste = login('teste', 'abc');
  echo $teste['email'];
}