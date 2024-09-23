<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div class="user-cont">
        <?php include_once __DIR__ . "/patials/message.php" ?>
        <h2>Welcome, <?= $user->getFirstname() ?></h2>
        <p>Firstname: <?= $user->getFirstname() ?></p>
        <p>Lastname: <?= $user->getLastname() ?></p>
        <p>Email: <?= $user->getEmail() ?></p>
        <p>
            Is Email Verified: <?php echo ($user->getIsEmailVerified()? 'True': 'False') ?>
            <?php if(!$user->getIsEmailVerified()): ?>
            <a href="/sendverification?user-id=<?= $user->getId() ?>">Verify Email</button>
            <?php endif ?>
        </p>
    </div>
    <a href="/user/edit">Edit Profile</a> | <a href="/user/delete">Delete Account</a> | <a href="/logout">Logout</a>
</body>
</html>