<?php
require_once __DIR__ . '/../helpers.php';

class VisitController {
    public function form(){
        require_login();
        $patient_id = (int)($_GET['patient_id'] ?? 0);
        $st = db()->prepare("SELECT id,name FROM patients WHERE id=?");
        $st->execute([$patient_id]);
        $p = $st->fetch();
        if (!$p) { http_response_code(404); exit('Paciente não encontrado.'); }

        render('visits/form', compact('p'));
    }

    public function save(){
        require_login();
        check_csrf();
        $pdo = db();
        $fields = [
          'patient_id','visit_date','complaint','hpi','ros','personal_history','family_history',
          'habits','physical_exam','bp_systolic','bp_diastolic','hr','rr','temp_c','spo2',
          'weight_kg','height_m','bmi','capillary_glucose','diagnosis','plan'
        ];
        $data = [];
        foreach ($fields as $f) $data[$f] = $_POST[$f] ?? null;
        $data['patient_id'] = (int)$data['patient_id'];
        if (empty($data['visit_date'])) $data['visit_date'] = date('Y-m-d H:i:s');

        $sql = "INSERT INTO visits
            (patient_id, visit_date, complaint, hpi, ros, personal_history, family_history, habits,
             physical_exam, bp_systolic, bp_diastolic, hr, rr, temp_c, spo2, weight_kg, height_m, bmi,
             capillary_glucose, diagnosis, plan, created_by)
            VALUES
            (:patient_id,:visit_date,:complaint,:hpi,:ros,:personal_history,:family_history,:habits,
             :physical_exam,:bp_systolic,:bp_diastolic,:hr,:rr,:temp_c,:spo2,:weight_kg,:height_m,:bmi,
             :capillary_glucose,:diagnosis,:plan,:created_by)";
        $st = $pdo->prepare($sql);
        $data['created_by'] = current_user()['id'];
        $st->execute($data);

        $id = (int)$pdo->lastInsertId();
        log_action('create','visits',$id,(string)$data['patient_id']);
        redirect(APP_URL.'/?r=visits/view&id='.$id);
    }

    public function view(){
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $st = db()->prepare("SELECT v.*, p.name as patient_name, p.cpf
                             FROM visits v JOIN patients p ON p.id=v.patient_id WHERE v.id=?");
        $st->execute([$id]);
        $v = $st->fetch();
        if (!$v) { http_response_code(404); exit('Consulta não encontrada.'); }
        render('visits/view', compact('v'));
    }
}
