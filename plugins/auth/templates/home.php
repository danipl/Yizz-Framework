<?php if (Auth::isAuth()) {
        echo $_SESSION['user'];
        Error::printList();
} else {
    ?>
    <form method="post">
        <fieldset>
            <legend>Login</legend>
            <input type="hidden" name="c" value="auth" />
            <input type="hidden" name="v" value="auth" />
            <input type="text" name="username" />
            <input type="password" name="password" />
            <input type="submit" />
        </fieldset>
    </form>
<?php }