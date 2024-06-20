# Sage 10 with Gutenberg Full Site Editing (FSE)

## Requirements
- Compatible `acorn`
  - `acorn` >=`4.1.0` with official FSE support (now used by this theme) 
  - or previously, a [patched `roots/acorn` package](https://github.com/roots/acorn/pull/141).
- `remove_theme_support('block-templates')` must be absent, as FSE will not work otherwise (important).
- `templates/` directory, `index.html` and `theme.json` (see the [FSE-specific theme files](#theme-structure) below).

### Optional
- The [Gutenberg Plugin](https://wordpress.org/plugins/gutenberg/) is not required, but adds extra Gutenberg-specific functionality. With earlier WordPress core versions, FSE was not supported and the Gutenberg plugin was required for FSE features, now FSE support is part of WordPress core. Using the Gutenberg plugin allows to lock-in a specific Gutenberg version, ensuring base styles and DOM staying the same for the theme. On the other hand, an additional plugin has to be used and maintained in relation to theme adjustments.
On a `roots.io` Bedrock site you usually would add the plugin to the Bedrock site (not the theme!) `composer.json` as a [WPackagist dependency](https://wpackagist.org/search?q=gutenberg&type=plugin).

## Please note
***Double-check whether `remove_theme_support('block-templates')` is absent from your theme setup, as otherwise FSE will not work.***


## `acorn` >=`4.2.0` inverted classic/FSE template priority
With `acorn` release `4.2.0` the priority of (classic) Blade-PHP and FSE template files was inverted, 
with FSE template files now taking predence over (classic) Blade-PHP template files, restoring the previous priority behavior of previously used, patched `acorn`.
The `views-classic/` directory was renamed back to `views/` as those will not interfere with FSE template files anymore.

### (legacy) `acorn` `4.1.0` built-in FSE support – classic/FSE template priority
With `acorn` release `4.1.0` initial FSE templates support has been added. Then, (classic) Blade-PHP template files (in `views/`) take precedence over FSE template files.
This is in contrast to to the patched `acorn` previously used by this theme. For this reason the `views/` directory was [renamed to `views-classic/`](https://github.com/strarsis/sage10-fse?tab=readme-ov-file#theme-structure:~:text=resources/views%2Dclassic/), to prevent any classic (Blade-PHP) template files overriding the FSE template files.
If you want to use a (classic) Blade-PHP template file for some post types/pages/etc. or plugins that do not support FSE/shortcodes yet (a "hybrid" FSE theme), 
put the corresponding (classic) Blade-PHP file into `views/` (create directory if needed), or move those files from `views-classic/` into it.


## Theme installation
1. Clone this repository.
(Note: If you are already having a parent repository above this theme (as often done with a Bedrock site), you may want to either manage the theme separately (e.g. submodule) or remove the git repo folder of theme to manage it in the parent repository, as it had been created like a plain Sage 10 theme using `composer create-project`).
2. Invoke `composer install`.
3. (Optional) Ensure suitable `node` version, e.g. using `nvm` from the `.nvmrc` by invoking `nvm install && nvm use`.
4. Invoke either `npm install` or `yarn install` (`npm install -g yarn` for installing/updating yarn classic (`1.x`)).
5. (Remove either `yarn.lock` or `package-lock.json`, depending on the package manager you are using, as `bud` will not build otherwise in order to prevent potential issues.)
6. Invoke `npm run build`.
7. (Mount the theme into a WordPress site and activate it).
8. Open the Site Editor:
  - Admin area → `Appearance` → `Editor` (not `beta` anymore).
  - (Logged in as `editor`+) → Front end → Admin bar → `Edit site`


## Theme structure

```sh
themes/your-theme-name/          # → Root of your Sage based theme
├── templates/                   # → Block templates directory (❗required for a FSE theme (for the `index.php` inside)) (formerly named `block-templates`)
│   ├── index.html               # → Block template for the posts (fallback) (❗required for a FSE theme)
│   ├── page.html                # → Block template for singular page, default
│   ├── page-without-header.html # → Block template for singular page, custom template (without header block part)
│   ├── home.html                # → Block template for posts page (specific page selected as blog page)
│   └── front-page.html          # → Block template for front page (specific page selected as front page)
├── parts/                       # → Block parts directory (can be used in block templates, among others) (formerly named `block-parts`)
│   ├── header.html              # → Block part for a header (there can be more headers, if needed)
│   ├── footer.html              # → Block part for a footer (there can be more footers, if needed)
│   └── example.html             # → Block part for a generic block part (uncategorized)
├── patterns/                    # → Block patterns directory
│   └── some-pattern.php         # → Block example pattern
├── theme.json                   # → Generated by Sage theme build process (`bud`) or directly edited (❗required for a FSE theme)
├── resources/views/             # → (Classic) Blade-PHP template files, overriden by FSE template files (`acorn` >=`4.2.0`), previously overrode FSE template files (`acorn` `4.1.0`)
```

## Notes

- You can [register the frontend styles as editor styles](https://github.com/strarsis/sage10-fse/blob/master/app/setup.php#L35-L40), then Gutenberg Editor will automatically post-process those styles and wrap all selectors in `.editor-styles-wrapper` for proper styles isolation (which prevents those frontend styles leaking into the editor UI itself). The editor styles are added as they are/were added to the TinyMCE editor (WordPress Classic Editor).
This allows the theme to be agnostic towards the technique with which the Gutenberg editor isolates the frontend styles (from its UI). This becomes more important now as in the near future Gutenberg will use `iframe`s. Therefore hardcoded style isolation (by prefixing a editor-styles-wrapper CSS class selector) would then either be unnecessary or even require additional post-processing which itself was intended to be avoided in the first place.
- You can also add editor-specific styles for adjusting the DOM elements added by the editor or the editor UI itself (for specific fixes). Those editor styles, even if they may be named "editor styles", would not be added as editor style (as it is called in WordPress) in a technical sense, but rather just [enqueued on the admin/backend editor pages](https://github.com/strarsis/sage10-fse/blob/master/app/setup.php#L21-L28) as normal styles (which would not be post-processed and hence isolated by Gutenberg editor either) (as by using `enqueue_block_editor_assets`).
- `add_theme_support('block-templates')` is not needed when theme is already autodetected to be a FSE theme (by presence of `theme.json` and `templates/`).
- Disabling FSE using `remove_theme_support('block-templates')` is ignored when block templates are in place, `Design → Editor` is still offered to the user. – But this will currently result in an [unexpected, non-JSON-, HTML-response for `_wp-find-template`](https://github.com/WordPress/gutenberg/issues/45170#issuecomment-1287434694), preventing the Gutenberg Editor from initializing, hence the Gutenberg Editor page stays blank.
- Block styles currently can not be registered by convention (as by adding them as files into a specific folder, using meta data as comments/JSON, as this is already possible with block patterns). – Currently block styles can only be registered using [`register_block_style`](https://developer.wordpress.org/reference/functions/register_block_style/) (in server-side PHP; recommended `init` action hook) or [`registerBlockStyle`](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/) (in editor JavaScript). This can change with future releases of Gutenberg and WordPress core.
- Get the translation source strings using the WP CLI [`i18n`](https://developer.wordpress.org/cli/commands/i18n/), as this tool is able to parse not only the normal core PHP WordPress translation functions, but also all the metadata in `theme.json`; Block patterns and Block parts for color, font-size, block part names, etc. – And recently it can also parse [Blade-PHP template files](https://github.com/wp-cli/i18n-command/pull/304). Sage 10 already uses these for its [translation-related `npm` scripts](https://github.com/strarsis/sage10-fse/blob/master/package.json#L14).
- In case you are wondering why no page header/footer is visible, this depends on the WordPress Reading settings (`Your latest post` or `A static page` under `Settings` → `Reading` → `Your homepage displays`) and whether (classic) Blade-PHP templates files are also existing to be used as fallback.
- You can easily add SCSS/SASS (+ PostCSS) support, just apply the [adjustments](https://github.com/strarsis/sage10-scss/commits/master) made to the [Sage 10 SCSS](https://github.com/strarsis/sage10-scss) theme.
- The great [`bud-wp-editor-query`](https://github.com/talss89/bud-wp-editor-query) plugin allows to add editor-specific styles directly in the frontend styles, using a custom and valid CSS media query syntax. An example is included in the [`app.css`](https://github.com/strarsis/sage10-fse/blob/master/resources/styles/app.css#L6-L12).
- With `acorn` >=`4.1.0` FSE support has been added, and with it (Classic) Blade-PHP template files have precedence over FSE templates. See [this section](#acorn-410-built-in-fse-support--classicfse-template-priority) about template file priority.


## Known issues and fixes

### Template part blocks can not render; JavaScript error `Cannot read properties of undefined (reading 'tinymce' [...]` in Gutenberg Editor

Block template or block parts contain plain, "naked" HTML without block-specific comments.

#### Related issues

- <https://core.trac.wordpress.org/ticket/55043>
- <https://github.com/bobbingwide/sb/issues/6>

### Customization by user
By default, the user can override the theme-provided block templates and block parts, those modifications are stored as special posts in database.
The site editor sidebar can be opened by clicking on the logo/icon on the upper left corner in the side editor.
From that sidebar the block templates and template parts lists can be viewed and the user customizations reset.

### Home versus Front page templates
This was a gotcha for me, as I first had not completely understood the exact difference between those two.
This very well made article explains the differences:
https://davidsutoyo.com/articles/difference-front-page-php-home-php/

#### No header/footer template part shown
Note that albeit the `index.html` would be utltimatively used when there are no other templates – but there are also (classic) Blade-PHP template files which would be used instead.
Either a FSE template alternative have to be added or those (classic) Blade-PHP templates files be removed.

### Gutenberg Editor/JavaScript errors like `Block "core/post-comments" is not registered` (for _comment_-specific core blocks)
The [`Disable Comments Plugin`](https://wordpress.org/plugins/disable-comments/) may cause this as it can also remove the comment-specific core blocks.
Note: In block templates those blocks have markup as `<!-- wp:post-comment`.

### Gutenberg Editor/JavaScript errors like `Block "core/post-title" is not registered` (for _blog-_/_post_-specific core blocks)
The [`Disable Blog Plugin`](https://wordpress.org/plugins/disable-blog/) may cause this as it can also remove the blog-/post-specific core blocks.
Note: In block templates those blocks have markup as `<!-- wp:post-title`.

### Template part suddenly not found after switching theme
Template parts can be locked [to a specific theme](https://github.com/search?q=repo%3Astrarsis%2Fsage10-fse+path%3Atemplates%2F+%5C%22theme%5C%22%3A%5C%22sage10-&type=code). This is useful when non-generic parts are used that are specific to a theme. 
Generic parts (`header`; `footer`), with no theme enforced, would automatically remap when switching themes. But when generic parts are locked to a specific theme, they will not be found when switching to another theme.

### PHP error `Target class [sage.view] does not exist.`
This can occur when the patched `acorn` library is installed, FSE is enabled, but no FSE-specific files being available in the theme.

### The Gutenberg Editor page loads, but stays blank! 😮
This happens when the backend sends an unexpected response (invalid JSON (so also just frontend HTML)) or no response. The Gutenberg Editor [currently catches any JSON parse errors and silently stops initializing](https://github.com/WordPress/gutenberg/issues/45170) (staying blank). 
Adding or changing the order of template loaders can cause this, hence this example Sage 10 FSE theme uses a patched version of the Sage theme `acorn` runtime that does not respond [with a matching Blade-PHP non-block template](https://github.com/roots/acorn/issues/228).
Also the aforementioned [disabling FSE using `remove_theme_support('block-templates')` in a FSE theme](https://github.com/WordPress/gutenberg/issues/45170#issuecomment-1287434694) causes this.

### The FSE theme template is not used, but something else (classic) Blade-PHP template files instead
With `acorn` `4.1.0` FSE support has been added, and with it (Classic) Blade-PHP template files have precedence over FSE templates.
See [this section](#acorn-410-built-in-fse-support--classicfse-template-priority) about template file priority.
With `acorn` >=`4.2.0` the priority was inverted, with FSE template files overriding (classic) Blade-PHP template files.
