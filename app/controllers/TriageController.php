<?php
require_once __DIR__ . '/../helpers.php';

class TriageController {

  // FILA (ambos veem: enfermeira e médico)
  public function index(){
    require_login();
    $q      = trim($_GET['q'] ?? '');
    $status = $_GET['status'] ?? (user_has_role(['medico']) ? 'pendente' : 'pendente'); // default pendente para ambos
    $onlyMine = isset($_GET['meus']) && $_GET['meus'] === '1' ? true : false;

    $sql = "SELECT t.*, p.name AS patient_name, u.name AS medico_nome
            FROM triages t
            JOIN patients p ON p.id = t.patient_id
            LEFT JOIN users u ON u.id = t.taken_by";
    $where = []; $args = [];

    if ($q !== '') { $where[]="(p.name LIKE :q OR p.cpf LIKE :q)"; $args[':q']="%$q%"; }
    if ($status !== '') { $where[]="t.status = :st"; $args[':st']=$status; }
    if ($onlyMine && current_user()) { $where[]="t.taken_by = :me"; $args[':me']=current_user()['id']; }

    if ($where) $sql .= ' WHERE '.implode(' AND ',$where);

    // ordem de chegada (pendente) e depois por prioridade visual do risco
    $sql .= " ORDER BY
              CASE LOWER(t.risk)
                   WHEN 'vermelho' THEN 1
                   WHEN 'laranja'  THEN 2
                   WHEN 'amarelo'  THEN 3
                   ELSE 4 END,
              t.triage_time ASC";

    $st = db()->prepare($sql); $st->execute($args);
    $rows = $st->fetchAll();
    render('triage/list', compact('rows','q','status','onlyMine'));
  }

  // Enfermeira cria triagem
  public function form(){
    require_login();
    require_role(['enfermeira','secretaria','admin']);
    $patient_id = (int)($_GET['patient_id'] ?? 0);
    $st = db()->prepare("SELECT id,name FROM patients WHERE id=?");
    $st->execute([$patient_id]); $p = $st->fetch() ?: exit('Paciente não encontrado.');
    render('triage/form', compact('p'));
  }

  public function save(){
    require_login();
    require_role(['enfermeira','secretaria','admin']);
    check_csrf();

    $f=['patient_id','triage_time','motive','comorbidities','meds','allergy','bp_systolic','bp_diastolic','hr','rr','spo2','dxa','tax','weight_kg','height_m','bmi','risk','notes'];
    $data=[]; foreach($f as $k) $data[$k] = $_POST[$k] ?? null;
    $data['patient_id'] = (int)$data['patient_id'];
    if (empty($data['triage_time'])) $data['triage_time'] = date('Y-m-d H:i:s');

    $sql="INSERT INTO triages (patient_id,triage_time,motive,comorbidities,meds,allergy,
          bp_systolic,bp_diastolic,hr,rr,spo2,dxa,tax,weight_kg,height_m,bmi,risk,notes,status,created_by)
          VALUES (:patient_id,:triage_time,:motive,:comorbidities,:meds,:allergy,
          :bp_systolic,:bp_diastolic,:hr,:rr,:spo2,:dxa,:tax,:weight_kg,:height_m,:bmi,:risk,:notes,'pendente',:created_by)";
    $data['created_by'] = current_user()['id'] ?? null;

    $st=db()->prepare($sql); $st->execute($data);
    log_action('create','triages',(int)db()->lastInsertId(),(string)$data['patient_id']);
    redirect(APP_URL.'/?r=triage/index&status=pendente');
  }

  // Médico clica "Atender" -> tenta travar a triagem
  public function claim(){
    require_login(); require_role(['medico','admin']); check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $pid= (int)($_POST['patient_id'] ?? 0);
    $u  = current_user()['id'];

    // travar apenas se ainda estiver pendente
    $st = db()->prepare("UPDATE triages SET status='em_atendimento', taken_by=?, called_at=NOW()
                         WHERE id=? AND status='pendente'");
    $st->execute([$u,$id]);

    if ($st->rowCount() === 0) {
      exit('Esta triagem já não está mais pendente.');
    }
    redirect(APP_URL.'/?r=visits/form&patient_id='.$pid.'&triage_id='.$id);
  }

  // Médico devolve à fila (cancelou o atendimento)
  public function release(){
    require_login(); require_role(['medico','admin']); check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $u  = current_user()['id'];
    db()->prepare("UPDATE triages SET status='pendente', taken_by=NULL, called_at=NULL
                   WHERE id=? AND taken_by=?")->execute([$id,$u]);
    redirect(APP_URL.'/?r=triage/index&status=pendente');
  }

  public function view(){
    require_login();
    $id=(int)($_GET['id']??0);
    $st=db()->prepare("SELECT t.*, p.name AS patient_name FROM triages t JOIN patients p ON p.id=t.patient_id WHERE t.id=?");
    $st->execute([$id]); $t=$st->fetch() ?: exit('Triagem não encontrada.');
    render('triage/view', compact('t'));
  }
}
