<?php 
    $errors = validation_errors();
    $anyErrors = $errors != "";
    $anyCustomErrors = isset($custom_errors) && count($custom_errors) > 0;
    
    $titleMessage = "Sign in!";
    $infoMessage = "You need to sign in to see the Administration Panel. If you don't have the required credentials, please contact the administrator.";
    $submitButtonValue = "Login";
?>
<div class="page-content container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-wrapper">
                <div class="box">
                    <div class="content-wrap">
                        <h6>Sign In</h6>
                        <div class="alert alert-success">
                            <?php echo $infoMessage; ?>
                        </div>
                        <?php if ($anyErrors || $anyCustomErrors) : ?>
                            <div class="alert alert-danger">
                                <?php echo $errors; ?>
                                <?php if ($anyCustomErrors) : ?>
                                    <?php foreach($custom_errors as $error) : ?>
                                        <?php echo $error; ?>
                                    <?php endforeach; ?>
                                <?php  endif; ?>
                            </div>
                        <?php endif;?>
                            <?php echo form_open('adminlogin/login', array('method' => 'POST', 'id' => 'adminlogin-form')); ?>
                                <input class="form-control" name="username" type="text" placeholder="E-mail address" value="<?php echo set_value("username"); ?>">
                                <input class="form-control" name="password" type="password" placeholder="Password">
                                <div class="action">
                                    <button class="btn btn-primary signup" name="loginbtn" value="1" type="submit">Login</button>
                                </div>   
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








