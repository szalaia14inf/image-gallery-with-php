<main class="container pt-5">

    <?php if ($containsError) : ?>
    <div class="alert alert-danger">
        A felhasználónév vagy a jelszó nem megfelelő...
    </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="form-group">
            <label for="email">E-mail cím: </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Írd be az E-mail címedet...">
        </div>

        <div class="form-group">
            <label for="password">Jelszó: </label>
            <input type="password" class="form-control" name="password" id="password"
                placeholder="Írd be a jelszavadat...">
        </div>

        <button type="submit" class="btn btn-primary">Belépés</button>
    </form>

</main>