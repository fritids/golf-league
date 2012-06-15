<?php

class CourseDAO {
	// 	select b.*, a.* from courses a inner join holes b on a.id = b.courseId where a.id = %s;
	const GET_COURSE_SQL = "select b.*, a.* from courses a inner join holes b on a.id = b.courseId where a.id = %s;";
	const GET_HOLES_SQL = "select * from holes where courseId = %s and side = '%s' order by number asc";
	
	public static function getCourseById($id) {
		$data = DBUtils::escapeData(array($id));
		$query = vsprintf(self::GET_COURSE_SQL, $data);
		$course = new Course();
		$result = mysql_query($query) or die("Unable to get the course with the specified id ($id)");
		if ($result) {
			$count = mysql_num_rows($result);
			for ($i = 0; $i < $count; $i++) {
			    $row = mysql_fetch_assoc($result);
		    	$course->id = $row["courseId"];
		    	$course->name = $row["name"]; 
				$hole = new Hole();
				$hole->number = $row["number"];
				$hole->par = $row["par"];
				$hole->mensHandicap = $row["mens_handicap"];
				$hole->womensHandicap = $row["womens_handicap"];
				$hole->side = $row["side"];
				array_push($course->holes, $hole);
			}
		}
		return $course;
	}
	
	public static function getHolesPerSide($courseId, $side) {
		$data = DBUtils::escapeData(array($courseId, $side));
		$query = vsprintf(self::GET_HOLES_SQL, $data);
		$holes = array();
		$result = mysql_query($query) or die("Could not search for the holes for the course ($courseId) and side ($side)");
		$count = mysql_num_rows($result);
		for ($i = 0; $i < $count; $i++) {
			$row = mysql_fetch_assoc($result);
			$hole = new Hole();
			$hole->number = $row["number"];
			$hole->par = $row["par"];
			$hole->mensHandicap = $row["mens_handicap"];
			$hole->womensHandicap = $row["womens_handicap"];
			$hole->side = $row["side"];
			array_push($holes, $hole);
		}
		return $holes;
	}
	
}