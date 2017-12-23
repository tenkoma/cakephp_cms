<?php
/**
 * @var array $tags
 * @var App\Model\Entity\Article[] $articles
 */
?>
<h1>
    Articles tagged with
    <?= $this->Text->toList(h($tags), 'or') ?>
</h1>

<section>
    <?php if ($articles->count() === 0): ?>
    <p>記事が見つかりませんでした。</p>
    <?php endif; ?>
    <?php foreach ($articles as $article): ?>
        <article>
            <!-- リンクの作成に HtmlHelper を使用 -->
            <h4><?= $this->Html->link(
                    $article->title,
                    ['controller' => 'Articles', 'action' => 'view', $article->slug]
                ) ?></h4>
            <span><?= h($article->created) ?>
        </article>
    <?php endforeach; ?>
</section>
