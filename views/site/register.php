<?php
use yii\widgets\ActiveForm;
?>
<?php
    $session = Yii::$app->session;
    $errors = $session->getFlash('errorMessages');
    $success = $session->getFlash('successMessage');
    if(isset($errors) && (count($errors)>0))
    {
        foreach ($session->getFlash('errorMessages') as $error)
        {
            echo "<div class='alert alert-danger' role='alert'>$error[0]</div>";
        }
    }
    if (isset($success))
    {
        echo "<div class='alert alert-success' role='alert'>$success</div>";
    }
?>
<head>
    <title>Register</title>
</head>
<h1>Sign-Up</h1>

<?php ActiveForm::begin([
        "action" => ["site/sign-up"],
        "method"=>"post"
]); ?>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" placeholder="Name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" placeholder="Email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password_confirm" placeholder="Confirm Password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>

<?php ActiveForm::end(); ?>