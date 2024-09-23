<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <div class="edit-cont">
        <div class="edit-inner">
            <?php if(isset($errors) && count($errors) > 0): ?>
            <div class="errors-cont">
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>

            <div class="form-cont">
                <form action="/user/edit" method="post">
                    <div class="form-row">
                        <label for="fname">
                            Firstname: <input type="text" name="fname" id="fname" value="<?= @$user->getFirstname() ?>">
                        </label>
                    </div>
                    <div class="form-row">
                        <label for="lname">
                            Lastname: <input type="text" name="lname" id="lname" value="<?= @$user->getLastname() ?>">
                        </label>
                    </div>
                    <div class="form-row">
                        <label for="email">
                            Email: <input type="text" name="email" id="email" value="<?= @$user->getEmail() ?>">
                        </label>
                    </div>
                    <div class="form-row">
                        <label for="password">
                            Password: <input type="text" name="password" id="password">
                        </label>
                    </div>
                    <div class="form-row">
                        <label for="confirm-password">
                            Confirm Password: <input type="text" name="confirm-password" id="confirm-password">
                        </label>
                    </div>
                    <div class="form-row">
                        <input type="submit" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>