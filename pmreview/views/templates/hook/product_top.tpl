<div class="pmreview-right-top-product d-flex text-align-center">
    <ul class="yao-home-stars-container m-0 d-inline-block">
        {for $i=1 to 5}
            <li><img src="{$img_dir}/icon/{if $i <= $product_rating}yellow_star{else}grey_star{/if}.svg"></li>
        {/for}
    </ul>
    <span class="pmreview-right-top-rating"> {$total_reviews}</span>
</div>
