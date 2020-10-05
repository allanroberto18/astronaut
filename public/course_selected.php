<?php

require_once './container.php';

/**
 * @var \App\Service\CourseService $courseService
 */

$id = $_GET['id'];
if ($id === '' || $id === 0 || is_numeric($id) === false) {
    header('Location: /course_list.php');
}

$courseService = $ioc['CourseService'];
$course = $courseService->getCourse(intval($id));

$table = '<td colspan="2">Course not found</td>';
$students = '<td colspan="2">This course doesn\'t have student</td>';
if ($course !== null) {
    /** @var \App\Model\Course $item */
    $table = '<tr>';
    $table .= '<td>' . $course->getId() . '</td>';
    $table .= '<td>' . $course->getName() . '</td>';
    $table .= '</tr>';
    if (sizeof($course->getStudents()) > 0) {
        $students = '<td>Students</td><td>';
        foreach ($course->getStudents() as $item) {
            $students .= $item->getName() . '<br />';
        }
        $students .= '</td>';
    }
    $table .= $students;
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Course List</title>
</head>
<body>

<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="course_list.php">Course List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Course Selected</li>
        </ol>
    </nav>
    <h3>Course List</h3>
    <a class="btn btn-primary" href="course_form.php" role="button">New Course</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
            <?= $table ?>
        </tbody>
    </table>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
</body>
</html>

