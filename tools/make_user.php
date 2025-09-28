<?php
require_once __DIR__.'/../app/db.php';
$n = $_GET['name']  ?? 'Usuário';
$e = $_GET['email'] ?? 'user@local';
$p = $_GET['pass']  ?? '123456';
$r = $_GET['role']  ?? 'enfermeira'; // enfermeira | medico | admin
$st = db()->prepare("INSERT INTO users(name,email,password_hash,role) VALUES (?,?,?,?)");
$st->execute([$n,$e,password_hash($p,PASSWORD_DEFAULT),$r]);
echo "OK: $e ($r)";
