<?php
require_once __DIR__ . '/../helpers.php';

class AuthController {
    public function login(){
        if (current_user()) redirect(APP_URL.'/');
        $error = null;

        if (is_post()){
            check_csrf();
            $email = trim($_POST['email'] ?? '');
            $pass  = (string)($_POST['password'] ?? '');
            $st = db()->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
            $st->execute([$email]);
            $u = $st->fetch();
            if ($u && password_verify($pass, $u['password_hash'])){
                $_SESSION['user'] = ['id'=>$u['id'], 'name'=>$u['name'], 'email'=>$u['email'], 'role'=>$u['role']];
                log_action('login');
                redirect(APP_URL.'/');
            } else {
                $error = 'Credenciais inválidas.';
            }
        }

        render('auth/login', compact('error'));
    }

    public function logout(){
        log_action('logout');
        session_destroy();
        redirect(APP_URL.'/?r=auth/login');
    }
}
