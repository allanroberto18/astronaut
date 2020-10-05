<?php

use App\Model\Person;

require_once 'container.php';

$id = empty($_GET['id']) ? $_POST['id'] : $_GET['id'];

if ($id === '' || $id === 0 || is_numeric($id) === false) {
    header('Location: /person_list.php');
}

$personService = $ioc['PersonService'];
$person = $personService->getPerson(intval($id));

$courseRepository = $ioc['CourseRepository'];
$courses = $courseRepository->getAll();

if ($person === null) {
    header('Location: /person_form.php');
}

$data = $_POST;
if (empty($_POST) === false) {
    $name = trim($data['name']);
    $courses = $data['courses'];

    /** @var \App\Service\PersonService $personService */
    $personService = $ioc['PersonService'];
    $personService->updatePerson($id, $name, $courses);

    header('Location: /person_list.php');
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

    <title>Create Person</title>
</head>
<body>

<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="person_list.php">Person List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Person: <?= $person->getName() ?> </li>
        </ol>
    </nav>
    <h3>Update Person</h3>
    <form class="needs-validation" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
          novalidate>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="hidden" name="id" value="<?= $person->getId() ?>"/>
            <input type="text" class="form-control" id="name" name="name" value="<?= $person->getName() ?>" required/>
            <div class="invalid-feedback">
                Field name is required
            </div>
        </div>
        <div class="form-group">
            <label for="name">Select course(s)</label>
            <?php foreach ($courses as $course): ?>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="courses[]"
                           <?php
                            foreach ($person->getCourses() as $item) {
                                if ($item->getId() === $course->getId()) {
                                    echo 'checked=checked';
                                }
                            }
                           ?>
                           value="<?= $course->getId() ?>"
                    />
                    <label><?= $course->getName() ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

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