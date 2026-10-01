<article class="card">
    {if $article.image}
        <img src="{$article.image}" alt="">
    {/if}
    <h3><a href="{$app_url}/article/{$article.slug}">{$article.title|escape}</a></h3>
    <p>{$article.short_description|escape|truncate:120}</p>
    <div class="card-meta">
        {$article.published_at|date_format:'%d.%m.%Y'} · {$article.views} 👁
    </div>
</article>
