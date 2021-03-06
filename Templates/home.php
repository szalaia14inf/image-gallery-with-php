<main class="container">


    <!-- Lapméret váltó -->
    <div class="row">
        <form action="" method="GET">
            <div class="form-group">
                <label for="size">Oldal Mérete: </label>
                <select name="size" id="size" class="form-control">
                    <?php foreach($possiblePageSize as $pagesize): ?>
                    <option <?=$size==$pagesize? "selected" : ""?> ><?=$pagesize?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Küldés</button>
            </div>
        </form>
    </div>

    <hr>

    <!-- Lapozósáv -->
    <?php require "pagination.php"?>


    <!-- Képek megj. -->
    <?php foreach($content as $picture): ?>
        <a href="/image/<?=$picture['id']?>"><img src="<?=$picture['thumbnail']?>" alt="<?=$picture['title']?>" title="<?=$picture['title']?>"></a>
    <?php endforeach ?>

    <!-- Lapozósáv -->
    <?php require "pagination.php"?>

</main>
