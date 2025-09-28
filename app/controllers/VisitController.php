<?php
require_once __DIR__ . '/../helpers.php';

class VisitController {

      public function form(){
        require_login();
        require_role(['medico','admin']);                           // só médico cria consulta
        $patient_id=(int)($_GET['patient_id']??0);
        $p=db()->prepare("SELECT id,name FROM patients WHERE id=?");
        $p->execute([$patient_id]); $p=$p->fetch() ?: exit('Paciente não encontrado.');
        $triage=null; $triage_id=(int)($_GET['triage_id']??0);
        if ($triage_id){
          $t=db()->prepare("SELECT * FROM triages WHERE id=? AND patient_id=?");
          $t->execute([$triage_id,$patient_id]); $triage=$t->fetch();
        }
        render('visits/form', compact('p','triage'));
    }

    public function save(){
      require_login(); require_role(['medico','admin']);
      check_csrf(); $pdo=db();

      $fields=['patient_id','visit_date','complaint','hpi','ros','personal_history','family_history',
              'habits','physical_exam','bp_systolic','bp_diastolic','hr','rr','temp_c','spo2',
              'weight_kg','height_m','bmi','capillary_glucose','diagnosis','plan','tests','reassessment','triage_id'];
      $data=[]; foreach($fields as $f) $data[$f]=$_POST[$f]??null;
      $data['patient_id']=(int)$data['patient_id'];
      $data['triage_id']=$data['triage_id']? (int)$data['triage_id']: null;
      if (empty($data['visit_date'])) $data['visit_date']=date('Y-m-d H:i:s');

      $sql="INSERT INTO visits
        (patient_id,visit_date,complaint,hpi,ros,personal_history,family_history,habits,physical_exam,
        bp_systolic,bp_diastolic,hr,rr,temp_c,spo2,weight_kg,height_m,bmi,capillary_glucose,diagnosis,plan,tests,reassessment,triage_id,created_by)
        VALUES
        (:patient_id,:visit_date,:complaint,:hpi,:ros,:personal_history,:family_history,:habits,:physical_exam,
        :bp_systolic,:bp_diastolic,:hr,:rr,:temp_c,:spo2,:weight_kg,:height_m,:bmi,:capillary_glucose,:diagnosis,:plan,:tests,:reassessment,:triage_id,:created_by)";
      $st=$pdo->prepare($sql); $data['created_by']=current_user()['id']; $st->execute($data);

      if ($data['triage_id']) { db()->prepare("UPDATE triages SET status='atendido' WHERE id=?")->execute([$data['triage_id']]); }
      redirect(APP_URL.'/?r=visits/view&id='.(int)$pdo->lastInsertId());
    }


    public function view(){
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $st = db()->prepare("SELECT v.*, p.name as patient_name, p.cpf,
                              t.motive as triage_motive, t.risk as triage_risk, t.triage_time,
                              t.bp_systolic as t_bp_s, t.bp_diastolic as t_bp_d,
                              t.hr as t_hr, t.rr as t_rr, t.spo2 as t_spo2, t.tax as t_tax,
                              t.weight_kg as t_w, t.height_m as t_h, t.bmi as t_bmi
                       FROM visits v
                       JOIN patients p ON p.id=v.patient_id
                       LEFT JOIN triages t ON t.id=v.triage_id
                       WHERE v.id=?");
        $st->execute([$id]);
        $v = $st->fetch() ?: exit('Consulta não encontrada.');
        render('visits/view', compact('v'));
    }

}
