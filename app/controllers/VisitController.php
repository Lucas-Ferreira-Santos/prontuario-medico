<?php
require_once __DIR__ . '/../helpers.php';

class VisitController
{
    // abre o formulário de consulta
    public function form(){
        require_login();
        require_role(['medico','admin']);

        $patient_id = (int)($_GET['patient_id'] ?? 0);
        $triage_id  = isset($_GET['triage_id']) ? (int)$_GET['triage_id'] : null;

        // (opcional) exigir que venha da fila de triagem
        // if (!user_has_role(['admin']) && !$triage_id) {
        //     exit('Abra a consulta a partir da fila de triagem (pendente).');
        // }

        // paciente
        $st = db()->prepare("SELECT * FROM patients WHERE id=?");
        $st->execute([$patient_id]);
        $p = $st->fetch() ?: exit('Paciente não encontrado.');

        // carrega triagem para pré-preencher vitais
        $t = null;
        if ($triage_id) {
            $st = db()->prepare("SELECT * FROM triages WHERE id=? AND patient_id=?");
            $st->execute([$triage_id, $patient_id]);
            $t = $st->fetch(); // pode ser null
        }

        // valores padrão para o form
        $defaults = [
            'patient_id'   => $patient_id,
            'triage_id'    => $triage_id,
            'visit_date'   => date('Y-m-d H:i:s'),
            'bp_systolic'  => $t['bp_systolic'] ?? null,
            'bp_diastolic' => $t['bp_diastolic'] ?? null,
            'hr'           => $t['hr'] ?? null,
            'rr'           => $t['rr'] ?? null,
            'temp_c'       => $t['tax'] ?? null,  // na triagem chamei de 'tax'
            'spo2'         => $t['spo2'] ?? null,
            'weight_kg'    => $t['weight_kg'] ?? null,
            'height_m'     => $t['height_m'] ?? null,
            'bmi'          => $t['bmi'] ?? null,
        ];

        render('visits/form', ['p'=>$p, 'defaults'=>$defaults, 'triage'=>$t]);
    }

    // salva a consulta e finaliza a triagem
    public function save(){
        require_login();
        require_role(['medico','admin']);
        check_csrf();

        $pdo = db();

        $fields = [
            'patient_id','visit_date','complaint','hpi','ros','personal_history','family_history',
            'habits','physical_exam','bp_systolic','bp_diastolic','hr','rr','temp_c','spo2',
            'weight_kg','height_m','bmi','capillary_glucose','diagnosis','plan','tests','reassessment','triage_id'
        ];
        $data = [];
        foreach ($fields as $f) $data[$f] = isset($_POST[$f]) ? trim((string)$_POST[$f]) : null;

        $data['patient_id'] = (int)$data['patient_id'];
        $data['triage_id']  = !empty($data['triage_id']) ? (int)$data['triage_id'] : null;
        if (empty($data['visit_date'])) $data['visit_date'] = date('Y-m-d H:i:s');

        foreach (['bp_systolic','bp_diastolic','hr','rr','temp_c','spo2','weight_kg','height_m','bmi','capillary_glucose'] as $nf){
            if ($data[$nf] === '') $data[$nf] = null;
        }

        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO visits
                (patient_id, visit_date, complaint, hpi, ros, personal_history, family_history, habits, physical_exam,
                 bp_systolic, bp_diastolic, hr, rr, temp_c, spo2, weight_kg, height_m, bmi, capillary_glucose,
                 diagnosis, plan, tests, reassessment, triage_id, created_by)
                VALUES
                (:patient_id, :visit_date, :complaint, :hpi, :ros, :personal_history, :family_history, :habits, :physical_exam,
                 :bp_systolic, :bp_diastolic, :hr, :rr, :temp_c, :spo2, :weight_kg, :height_m, :bmi, :capillary_glucose,
                 :diagnosis, :plan, :tests, :reassessment, :triage_id, :created_by)";
            $st = $pdo->prepare($sql);
            $data['created_by'] = current_user()['id'];
            $st->execute($data);

            $visit_id = (int)$pdo->lastInsertId();

            if (!empty($data['triage_id'])) {
                $upd = $pdo->prepare("
                    UPDATE triages
                       SET status   = 'finalizada',
                           visit_id = ?,
                           taken_by = COALESCE(taken_by, ?),
                           called_at= COALESCE(called_at, NOW())
                     WHERE id = ? AND patient_id = ?
                ");
                $upd->execute([$visit_id, current_user()['id'], $data['triage_id'], $data['patient_id']]);
            }

            $pdo->commit();
            redirect(APP_URL.'/?r=visits/view&id='.$visit_id);

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            http_response_code(500);
            exit('Erro ao salvar a consulta.');
        }
    }
    public function view(){
    require_login();
    require_role(['medico','admin']);

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) exit('Consulta inválida.');

    $sql = "
        SELECT
            v.*,
            p.name AS patient_name,
            p.cpf  AS cpf,

            -- TRIAGEM (campos conforme a sua tabela)
            t.triage_time,
            t.motive            AS triage_motive,
            t.comorbidities     AS triage_comorbidities,
            t.meds              AS triage_meds,
            t.allergy           AS triage_allergy,
            t.bp_systolic       AS triage_bp_systolic,
            t.bp_diastolic      AS triage_bp_diastolic,
            t.hr                AS triage_hr,
            t.rr                AS triage_rr,
            t.spo2              AS triage_spo2,
            t.dxa               AS triage_dxa,
            t.tax               AS triage_tax,
            t.weight_kg         AS triage_weight_kg,
            t.height_m          AS triage_height_m,
            t.bmi               AS triage_bmi,
            t.risk              AS triage_risk,

            -- aliases “curtos” usados no bloco “Resumo da Triagem” da sua view
            t.bp_systolic       AS t_bp_s,
            t.bp_diastolic      AS t_bp_d,
            t.hr                AS t_hr,
            t.rr                AS t_rr,
            t.spo2              AS t_spo2,
            t.tax               AS t_tax,
            t.weight_kg         AS t_w,
            t.height_m          AS t_h,
            t.bmi               AS t_bmi

        FROM visits v
        JOIN patients p ON p.id = v.patient_id
        LEFT JOIN triages t ON t.id = v.triage_id
        WHERE v.id = ?
        LIMIT 1
    ";

    $st = db()->prepare($sql);
    $st->execute([$id]);
    $v = $st->fetch() ?: exit('Consulta não encontrada.');

    render('visits/view', ['v' => $v]);
}



}
