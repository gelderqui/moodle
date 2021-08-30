<?php

function local_geldercorreo_sendmailtask() {
    global $DB, $CFG;
   // require($CFG->dirroot.'/cohort/lib.php');
    mtrace("Gelder correo CorpLearning started");
	$timestamp = time();
	
	//Consulta para obtener los correos vacios
	$sql = "SELECT u.id, u.email
			FROM mdl_user as u
			WHERE u.email is null or u.email=''
			";
	$userlist = $DB->get_records_sql($sql);

	//Actualiza todos los objeto de userlist que son los correos vacios
	foreach ($userlist as $ul) {
		$ul->email='sincorreo@gmail.com';
		$DB->update_record('user', $ul);
	}

    mtrace("Gelder cambio correo Corplearning completed");
}
