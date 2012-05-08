<style>
.feed-list li {
    height: 35px;
}
.feed-list li img {
    vertical-align: top;
    margin: 0 5px;
}
.feed-list .feed-info {
    color: #aaa;
}
</style>

<h1>Flux d'actualités</h1>
<br/>
<ul class="feed-list">
<?php foreach ($d as $line): ?>
    <li>
        <?php echo $line['event'] ?>
        <?php echo $line['info'] ?>
        <?php echo $line['delta'] ?>
    </li>
<?php endforeach ?>
</ul>