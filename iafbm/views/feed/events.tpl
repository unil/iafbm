<style>
.feed-list li {
    height: 35px;
}
.feed-list li img {
    vertical-align: top;
    margin: 0 5px;
}
</style>

<ul class="feed-list">
<?php foreach ($d as $line): ?>
    <li>
        <?php echo $line ?>
    </li>
<?php endforeach ?>
</ul>