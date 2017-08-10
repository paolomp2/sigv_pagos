<?php
	use sigc\Http\Controllers\scheduleController;
	use sigc\Student;

	$students = Student::all();
	$oSchedule = new scheduleController;
	foreach ($students as $student) {
		$oSchedule->refresh_debts_students($student->id);
	}
?>