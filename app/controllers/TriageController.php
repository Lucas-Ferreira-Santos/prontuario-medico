<?php
require_once __DIR__ . '/../helpers.php';

class TriageController {

  public function index(){
    require_login();
    // Recepção vê 'aberto'; médico pode filtrar todos
    $status = $_GET['status'] ?? (user_has_role('recepcao') ? 'aberto' : '');
    $q = trim($_GET['q'] ?? '');
    $sql = "SELECT t.*, p.name FROM triages t JOIN patients p ON p.id=t.patient_id";
    $args=[]; $where=[];
    if ($q!==''){ $where[]="(p.name LIKE :q OR p.cpf LIKE :q)"; $args[':q']="%$q%"; }
    if ($status!==''){ $where[]="t.status=:st"; $args[':st']=$status; }
    if ($where) $sql .= " WHERE ".implode(" AND ",$where);
    $sql .= " ORDER BY 
      CASE LOWER(t.risk) WHEN 'vermelho' THEN 1 WHEN 'laranja' THEN 2 WHEN 'amarelo' THEN 3 ELSE 4 END,
      t.triage_time ASC";
    $st=db()->prepare($sql); $st->execute($args); $rows=$st->fetchAll();
    render('triage/list', compact('rows','q','status'));
  }

  public function form(){
    require_login();
    require_role(['recepcao','admin']);                       // só recepção cria
    $patient_id = (int)($_GET['patient_id'] ?? 0);
    $st = db()->prepare("SELECT id,name,allergies,comorbidities FROM patients WHERE id=?");
    $st->execute([$patient_id]); $p = $st->fetch() ?: exit('Paciente não encontrado.');
    render('triage/form', compact('p'));
  }

  public function save(){
    require_login(); require_role(['recepcao','admin']);
    check_csrf(); $pdo=db();

    $fields=['patient_id','motive','comorbidities','meds','allergy','bp_systolic','bp_diastolic',
             'hr','rr','spo2','dxa','tax','weight_kg','height_m','bmi','risk','notes','triage_time'];
    $data=[]; foreach($fields as $f) $data[$f]=$_POST[$f]??null;
    $data['patient_id']=(int)$data['patient_id'];
    if (empty($data['triage_time'])) $data['triage_time']=date('Y-m-d H:i:s');

    $sql="INSERT INTO triages
      (patient_id, triage_time, motive, comorbidities, meds, allergy,
       bp_systolic, bp_diastolic, hr, rr, spo2, dxa, tax, weight_kg, height_m, bmi,
       risk, notes, status, created_by)
      VALUES
      (:patient_id,:triage_time,:motive,:comorbidities,:meds,:allergy,
       :bp_systolic,:bp_diastolic,:hr,:rr,:spo2,:dxa,:tax,:weight_kg,:height_m,:bmi,
       :risk,:notes,'encaminhado',:created_by)";
    $st=$pdo->prepare($sql); $data['created_by']=current_user()['id']; $st->execute($data);

    log_action('create','triages',(int)$pdo->lastInsertId(),(string)$data['patient_id']);
    redirect(APP_URL.'/?r=triage/index');                     // volta pra fila
  }

  public function view(){
    require_login();
    $id=(int)($_GET['id']??0);
    $st=db()->prepare("SELECT t.*, p.name,p.cpf FROM triages t JOIN patients p ON p.id=t.patient_id WHERE t.id=?");
    $st->execute([$id]); $t=$st->fetch() ?: exit('Triagem não encontrada.');
    render('triage/view', compact('t'));
  }

  public function set_status(){
    require_login(); check_csrf();
    require_role(['recepcao','admin']);                       // recepção controla status
    $id=(int)($_POST['id']??0); $stt=$_POST['status']??'aberto';
    db()->prepare("UPDATE triages SET status=? WHERE id=?")->execute([$stt,$id]);
    redirect(APP_URL.'/?r=triage/index');
  }
}
