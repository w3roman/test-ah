{if $pages > 1}
<nav class="pagination">
    {for $i=1 to $pages}
        {if $i == $page}
            <span class="current">{$i}</span>
        {else}
            <a href="?sort={$sort}&page={$i}">{$i}</a>
        {/if}
    {/for}
</nav>
{/if}