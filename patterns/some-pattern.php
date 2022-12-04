<?php
/**
 * Title: Some pattern
 * Slug: sage/some-pattern
 * Categories: header
 * Description: The description of an useful block pattern.
 * Keywords: some, pattern, block, custom
 * Block Types: core/group, core/heading, core/paragraph
 *
 * @see https://wordpress.stackexchange.com/a/398395/134384
 * @see https://fullsiteediting.com/lessons/introduction-to-block-patterns/#h-registering-block-patterns-using-the-patterns-folder
 */
?>
<!-- wp:group {"templateLock":"all","align":"full","className":"custom-class-if-needed"} -->
<div class="wp-block-group alignfull some-custom-class-if-needed">
  <!-- wp:heading --><h3>Some heading that belongs to this block pattern.</h3><!-- /wp:heading -->
  <!-- wp:paragraph --><p>Some paragraph that belongs to this block pattern.</p><!-- /wp:paragraph -->
  <!-- wp:paragraph --><p>Some other paragraph that belongs to this block pattern.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
