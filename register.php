<?php
require_once 'auth.php';

// If post, check user
$error = null;
if (!empty($_POST['email']) && !empty($_POST['pass']) && !empty($_POST['conf_pass'])) {
    if($_POST['pass'] !== $_POST['conf_pass']) {
        $error = 'Mismatched confirmation password';
        $_SESSION['userlogin'] = FALSE;
    } else {
        // Verify user and password
        if (isValidRegister($_POST['email'], $_POST['pass'])) {
            // Log in
            $_SESSION['userlogin'] = $_POST['email'];
            header('Location: index.php');
            exit();
        }
        else
        {
            $error = 'Already Exists';
            $_SESSION['userlogin'] = FALSE;
        }
    }

}

// The user login page
include 'templates/header.php';
?>

    <!--login modal-->
    <div id="registerModal" class="modal show bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form class="form" method="POST" action="register.php">
                    <?php
                        if($error) {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <?=$error?>
                        </div>
                    <?php }?>
                    <div class="modal-header">
                        <h3 class="text-center">User Registration</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="email" class="form-control input-sm" placeholder="Email" name="email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control input-sm" placeholder="password" name="pass">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control input-sm" placeholder="confirmation password" name="conf_pass">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
include 'templates/footer.php';
