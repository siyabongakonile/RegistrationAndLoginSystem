<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <div class="login-cont">
        <div class="login-inner">
            <?php include_once __DIR__ . "/../patials/message.php" ?>
            <?php if(isset($errors) && count($errors) > 0): ?>
            <div class="errors-cont">
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>
            <div class="form-wrapper">
                <form action="/login" method="post">
                    <div class="form-row">
                        <input type="text" name="email" value="<?= @$email ?>" placeholder="Email Address">
                    </div>
                    <div class="form-row">
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-row">
                        <input type="submit" name="login">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>