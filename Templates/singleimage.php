<main class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img class="mw-100" title="<?= $picture["title"] ?>" src="<?= $picture["url"] ?>">
        </div>

        <div class="col-md-6">

            <form method="post" action="/image/<?=$picture['id']?>/edit">
                <div class="form-group">
                    <label for="title">Kép címe: </label>
                    <input type="text" name="title" id="title" value="<?=$picture['title']?>" class="form-control" placeholder="Ide írd a kép címét.">
                </div>
                <button type="submit" class="btn btn-primary">Frissítés</button>
            </form>

            <form method="post" action="/image/<?=$picture['id']?>/delete" class="mt-5">
                <div class="form-group">
                    <label for="title">Kép törlése: </label>
                </div>
                <button type="submit" class="btn btn-danger">Törlés</button>
            </form>

        </div>
    </div>
</main>