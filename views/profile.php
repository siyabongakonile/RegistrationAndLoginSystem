<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div class="user-cont">
        <?php if(isset($message)): ?>
        <div class="message-cont">
            <p><?= $message ?></p>
        </div>
        <?php endif ?>
        <h2>Welcome, <?= $user->getFirstname() ?></h2>
        <p>Firstname: <?= $user->getFirstname() ?></p>
        <p>Lastname: <?= $user->getLastname() ?></p>
        <p>Email: <?= $user->getEmail() ?></p>
        <p>
            Is Email Verified: <?php echo ($user->getIsEmailVerified()? 'True': 'False') ?>
            <a href="/sendverification?user-id=<?= $user->getId() ?>">Verify Email</button>
        </p>
    </div>
    <a href="/logout">Logout</a>
</body>
</html>