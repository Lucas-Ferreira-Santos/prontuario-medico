<?php
require_once __DIR__ . '/../helpers.php';

class PatientController {
    public function index(){
        require_login();
        $q = trim($_GET['q'] ?? '');
        if ($q !== ''){
            $st = db()->prepare("SELECT * FROM patients
                                 WHERE name LIKE :q OR cpf LIKE :q
                                 ORDER BY name ASC LIMIT 200");
            $st->execute([':q'=>"%$q%"]);
        } else {
            $st = db()->query("SELECT * FROM patients ORDER BY created_at DESC LIMIT 100");
        }
        $rows = $st->fetchAll();
        render('patients/list', compact('rows','q'));
    }

    public function form(){
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $data = [
            'id'=>null,'name'=>'','cpf'=>'','birthdate'=>'','sex'=>'','marital_status'=>'',
            'phone'=>'','email'=>'','address'=>'','city'=>'','uf'=>'','cep'=>'',
            'emergency_contact'=>'','emergency_phone'=>'','allergies'=>'','comorbidities'=>''
        ];
        if ($id){
            $st = db()->prepare("SELECT * FROM patients WHERE id=?");
            $st->execute([$id]);
            $data = $st->fetch();
            if (!$data) { http_response_code(404); exit('Paciente não encontrado.'); }
        }
        render('patients/form', compact('data'));
    }

    public function save(){
        require_login();
        check_csrf();
        $pdo = db();
        $fields = ['name','cpf','birthdate','sex','marital_status','phone','email','address','city','uf','cep',
                   'emergency_contact','emergency_phone','allergies','comorbidities'];
        $data = [];
        foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');

        if ($data['name']==='') exit('Nome é obrigatório.');

        if (!empty($_POST['id'])){
            $data['id'] = (int)$_POST['id'];
            $sql = "UPDATE patients SET
                    name=:name, cpf=:cpf, birthdate=:birthdate, sex=:sex, marital_status=:marital_status,
                    phone=:phone, email=:email, address=:address, city=:city, uf=:uf, cep=:cep,
                    emergency_contact=:emergency_contact, emergency_phone=:emergency_phone,
                    allergies=:allergies, comorbidities=:comorbidities
                    WHERE id=:id";
            $st = $pdo->prepare($sql); $st->execute($data);
            log_action('update','patients',$data['id'],$data['name']);
            redirect(APP_URL.'/?r=patients/view&id='.$data['id']);
        } else {
            $st = $pdo->prepare("INSERT INTO patients
                (name,cpf,birthdate,sex,marital_status,phone,email,address,city,uf,cep,
                 emergency_contact,emergency_phone,allergies,comorbidities,created_by)
                VALUES
                (:name,:cpf,:birthdate,:sex,:marital_status,:phone,:email,:address,:city,:uf,:cep,
                 :emergency_contact,:emergency_phone,:allergies,:comorbidities,:created_by)");
            $data['created_by'] = current_user()['id'];
            $st->execute($data);
            $id = (int)$pdo->lastInsertId();
            log_action('create','patients',$id,$data['name']);
            redirect(APP_URL.'/?r=patients/view&id='.$id);
        }
    }

    public function view(){
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $st = db()->prepare("SELECT * FROM patients WHERE id=?");
        $st->execute([$id]);
        $p = $st->fetch();
        if (!$p) { http_response_code(404); exit('Paciente não encontrado.'); }

        $v = db()->prepare("SELECT * FROM visits WHERE patient_id=? ORDER BY visit_date DESC, created_at DESC");
        $v->execute([$id]);
        $visits = $v->fetchAll();

        render('patients/view', compact('p','visits'));
    }
}
