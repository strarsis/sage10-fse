<?php
/**
 * Title: Some part
 * Slug: sage/some-part
 * Categories: header
 * Description: The description of an useful block part.
 * Keywords: some, part, block, custom
 * Block Types: core/group, core/heading, core/paragraph
 *
 * @see https://wordpress.stackexchange.com/a/398395/134384
 * @see https://fullsiteediting.com/lessons/introduction-to-block-patterns/#h-registering-block-patterns-using-the-patterns-folder
 */
?>
<!-- wp:group {"templateLock":"all","align":"full","className":"custom-class-if-needed"} -->
<div class="wp-block-group alignfull some-custom-class-if-needed">
  <!-- wp:heading --><h3>Some heading that belongs to this block part.</h3><!-- /wp:heading -->
  <!-- wp:paragraph --><p>Some paragraph that belongs to this block part.</p><!-- /wp:paragraph -->
  <!-- wp:paragraph --><p>Some other paragraph that belongs to this block part.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
