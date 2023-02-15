<div class="modal fade in" id="pm_review_popup" tabindex="-1" role="dialog" aria-labelledby="pm_review_popupLabel" aria-hidden="false" ">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="lni lni-close"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-body__review-title">{l s='New review' mod='pmreview'}</p>
                <div class="modal-body__review-stars">
                    {for $i=1 to 5}
                        {*<img class="modal-body__review-stars-btn" src="{$img_dir}icon/grey_star.svg">*}
                        <div class="modal-body__review-stars-btn"><i class="lni lni-star"></i></div>
                    {/for}
                </div>
                <p class="modal-body__review-hint">{l s='Please set the rating' mod='pmreview'}</p>
                <form method="POST">
                    <div class="modal-body__review-input">
                        <input type="text" name="firstname" value="{if $customer->id}{$customer->firstname}{/if}" placeholder="{l s='Name' mod='pmreview'}">
                    </div>
                    <div class="modal-body__review-input">
                        <textarea name="review" row="6" placeholder="{l s='Write your review here...' mod='pmreview'}"></textarea>
                    </div>

                    <div id="review-frames"></div>
                    <input type="hidden" name="id_product" value="{$id_product}">
                    <input type="hidden" name="file_size" class="pmr-file_size" value="{$file_size}">
                    <input type="hidden" name="files_count" class="pmr-files_count" value="{$files_count}">
                </form>
                <div class="modal-body__review-input">
                    <input id="review-images" name="images[]"  accept=".png, .jpg, .jpeg, .svg, .mp4, .m4v, .f4v, .Irv" multiple type="file" class="form-control-file inputfile" id="avatar">
                    <label class="modal-body__review-input-upload" for="review-images"><i class="lni lni-upload"></i> {l s='Загрузить фотографии' mod='pmreview'}</label>
                    <div class="modal-body__review-hint">{l s='Maximum file size %1$d' sprintf=$files_count} {l s='(< %1$d MB)' sprintf=$file_size}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-url="{$url}" class="pmreview_save_review d-block btn btn-primary">{l s='Отправить отзыв' mod='pmreview'}</button>
            </div>
        </div>
    </div>
</div>