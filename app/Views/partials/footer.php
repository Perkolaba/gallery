<footer class="section hero is-light">
    <div class="container">
        <div class="content has-text-centered">
            <div class="tabs">
                <ul>
                    <li class="is-active"><a>Главная</a></li>
                    <?php foreach(getAllCategories() as $category):?>

                        <li><a href="/category/<?=$category['id']?>"><?=$category['title']?></a></li>

                    <?php endforeach ?>
                </ul>
            </div>
            <p>
                <strong>Onizuka</strong> - BENE VOBIS!
            </p>

        </div>
    </div>
</footer>