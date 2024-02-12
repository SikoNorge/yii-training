<?php foreach ($userPosts as $post): ?>
    <div class="post-box">
        <div class="post">
            <p><?= $post->content ?></p>
            <div class="post-date">
                <?= date('d.m.Y', strtotime($post->created_at)) ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>