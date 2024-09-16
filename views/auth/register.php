<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
</head>
<body>
    <div class="form-cont">
        <div class="form-inner">
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
                <form action="/register" method="post">
                    <div class="form-row">
                        <input type="text" name="fname" value="<?= @$fname ?>" placeholder="Firstname">
                    </div>
                    <div class="form-row">
                        <input type="text" name="lname" value="<?= @$lname ?>" placeholder="Lastname">
                    </div>
                    <div class="form-row">
                        <input type="email" name="email" value="<?= @$email ?>" placeholder="Email Address">
                    </div>
                    <div class="form-row">
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-row"></div>
                        <input type="submit" name="submit">
                    </div>
                </form>
            </div>
        </div>
        <div class="form-cont-inner">
            
        </div>
    </div>
</body>
</html>