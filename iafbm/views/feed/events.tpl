<style>
.feed-list li {
    padding: 5px;
}
.feed-list li img {
    vertical-align: top;
    margin: 0 5px;
    filter: alpha(opacity=66);
    opacity: 0.5;
}
.feed-list li:hover {
    background-color: #eee;
}
.feed-list li:hover img {
    filter: alpha(opacity=100);
    opacity: 1;
}
.feed-list .feed-info {
    color: #aaa;
}
</style>

<h1>Flux d'actualités</h1>
<ul class="feed-list">
<?php foreach ($d as $line): ?>
    <li>
        <?php echo $line['event'] ?>
        <?php echo $line['info'] ?>
        <?php echo $line['delta'] ?>
    </li>
<?php endforeach ?>
</ul>
