<?php

function local_geldercohortcl_cohortsynctask() {
    global $DB, $CFG;
    require($CFG->dirroot.'/cohort/lib.php');
    mtrace("Gelder cohort CorpLearning started");
    $timestamp = time();
	// Find all courses where the course.idnumber = Cohort.idnuumber
    // Get all the users with "Manual enrolment" in the course
	$sql = "SELECT CONCAT(c.id, u.id), c.id as courseid, 
	        c.idnumber, u.id as userid,
            e.id as enrolid
            FROM mdl_course c
            JOIN mdl_enrol e ON e.courseid = c.id AND e.enrol = 'manual'
            JOIN mdl_user_enrolments ue ON ue.enrolid = e.id
            JOIN mdl_user u ON u.id = ue.userid
            WHERE c.idnumber IS NOT NULL
            AND c.idnumber IN (SELECT DISTINCT idnumber FROM mdl_cohort)
			";
    $userlist = $DB->get_records_sql($sql);
	
	// Add the users to the cohort where the cohort idnumber matches to the course idnumber
	foreach ($userlist as $ul) {
	   $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $ul->idnumber));
	   cohort_add_member($cohortid, $ul->userid);
	   // Check whether the user is added to the cohort
	   if ($DB->get_record('cohort_members', array('cohortid' => $cohortid, 'userid' => $ul->userid))) {
		   // Delete the Manual enrolment for the user for that course.
		   $DB->delete_records('user_enrolments', array('enrolid' => $ul->enrolid, 'userid' => $ul->userid));
	   }
    }
    mtrace("Gelder cohort Corplearning completed");
}
