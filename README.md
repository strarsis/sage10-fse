# Sage 10 with Gutenberg Full Site Editing (FSE)

## Requirements

- The [Gutenberg Plugin](https://wordpress.org/plugins/gutenberg/) is not required, but adds extra Gutenberg-specific functionality that is missed otherwise in WordPress core.
- A [patched `roots/acorn` package](https://github.com/roots/acorn/pull/141) is used by the theme as theme runtime dependency (in theme [`composer.json`](https://github.com/strarsis/sage10-fse/blob/master/composer.json#L43-L49)) which loader preserves the block template paths.

## Theme installation

1. Clone this repository.
2. Invoke `composer install`.
3. (Optional) Ensure suitable `node` version, e.g. using `nvm` from the `.nvmrc` by invoking `nvm install && nvm use`.
4. Invoke `npm install`.
5. Invoke `npm run build`.
6. (Mount the theme into a WordPress site and activate it).
7. Open the Site Editor:
  - Admin area → Appearance → Edit (beta)
  - (Logged in as editor+) → Front end → Admin bar → Edit Site

## Theme structure

```sh
themes/your-theme-name/   # → Root of your Sage based theme
├── templates/            # → Block templates directory (❗required for a FSE theme (for the `index.php` inside)) (formerly named `block-templates`)
│   ├── index.html        # → Block template for the posts (fallback) (❗required for a FSE theme)
│   ├── page.html         # → Block template for singular page
│   ├── home.html         # → Block template for posts page (specific page selected as blog page)
│   └── front-page.html   # → Block template for front page (specific page selected as front page)
├── parts/                # → Block parts directory (can be used in block templates, among others) (formerly named `block-parts`)
│   ├── header.html       # → Block part for a header (there can be more headers, if needed)
│   └── footer.html       # → Block part for a footer (there can be more footers, if needed)
├── patterns/             # → Block patterns directory
│   └── some-pattern.php  # → Block example pattern
├── theme.json            # → Generated by Sage theme build process (`bud`) or directly edited (❗required for a FSE theme)
```

## Notes

- You can [register the frontend styles as editor styles](https://github.com/strarsis/sage10-fse/blob/master/app/setup.php#L30-L40), then Gutenberg Editor will automatically post-process those styles and wrap all selectors in `.editor-styles-wrapper` for proper styles isolation (which prevents those frontend styles leaking into the editor UI itself). This is the same as adding the frontend editor styles to TinyMCE (WordPress Classic Editor).
- You can also add editor-specific styles for adjusting the DOM elements added by the editor or the editor UI itself (for specific fixes). Those editor styles, even if they may be named "editor styles", wouldn't be added as editor style (as it is called in WordPress) in a technical sense, but rather just enqueued on the admin/backend editor pages as normal styles (which wouldn't be post-processed and hence isolated by Gutenberg editor either).
- `add_theme_support('block-templates');` is not needed when theme is already autodetected to be a FSE theme (by presence of `theme.json` and `templates/`).
- Disabling FSE using `remove_theme_support('block-templates')` is ignored when block templates are in place, `Design → Editor` is still offered to the user. – But this will currently result in an [unexpected, non-JSON-, HTML-response for `_wp-find-template`](https://github.com/WordPress/gutenberg/issues/45170#issuecomment-1287434694), preventing the Gutenberg Editor from initializing, hence the Gutenberg Editor page stays blank.
- Block styles currently can't be registered by convention (as by adding them as files into a specific folder, using meta data as comments/JSON, as this is already possible with block patterns). – Currently block styles can only be registered using [`register_block_style`](https://developer.wordpress.org/reference/functions/register_block_style/) (in server-side PHP) or [`registerBlockStyle`](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/) (in editor JavaScript). This can change with future releases of Gutenberg and WordPress core.
- Get the translation source strings using the WP CLI [`i18n`](https://developer.wordpress.org/cli/commands/i18n/), as this tool is able to parse not only the normal core PHP WordPress translation functions, but also all the metadata in `theme.json`; Block patterns and Block parts. – And recently it can also parse [Blade-PHP template files](https://github.com/wp-cli/i18n-command/pull/304). Sage 10 already uses these for its [translation-related `npm` scripts](https://github.com/strarsis/sage10-fse/blob/master/package.json#L14).

## Known issues and fixes

### Template part blocks can't render; JavaScript error `Cannot read properties of undefined (reading 'tinymce' [...]` in Gutenberg Editor

Block template or block parts contain plain, "naked" HTML without block-specific comments.

#### Related issues

- <https://core.trac.wordpress.org/ticket/55043>
- <https://github.com/bobbingwide/sb/issues/6>

### Customization by user
By default the user can override the theme-provided block templates and block parts, those modifications are stored as special posts in database.
The site editor sidebar can be opened by clicking on the logo/icon on the upper left corner in the side editor.
From that sidebar the block templates and template parts lists can be viewed and the user customizations reset.

### Home versus Front page templates
This was a gotcha for me, as I first hadn't completely understood the exact difference between those two.
This very well made article explains the differences:
https://davidsutoyo.com/articles/difference-front-page-php-home-php/

### Gutenberg Editor/JavaScript errors like `Block "core/post-comments" is not registered` (for _comment_-specific core blocks)
The [`Disable Comments Plugin`](https://wordpress.org/plugins/disable-comments/) may cause this as it can also remove the comment-specific core blocks.
Note: In block templates those blocks have markup as `<!-- wp:post-comment`.

### Gutenberg Editor/JavaScript errors like `Block "core/post-title" is not registered` (for _blog-_/_post_-specific core blocks)
The [`Disable Blog Plugin`](https://wordpress.org/plugins/disable-blog/) may cause this as it can also remove the blog-/post-specific core blocks.
Note: In block templates those blocks have markup as `<!-- wp:post-title`.

### The Gutenberg Editor page loads but stays blank
This happens when the backend sends an unexpected response (invalid JSON (so also just frontend HTML) or no response. The Gutenberg Editor [currently catches any JSON parse errors and silently stops initializing](https://github.com/WordPress/gutenberg/issues/45170) (staying blank). 
Adding or changing the order of template loaders can cause this, hence this example Sage 10 FSE theme uses a patched version of the Sage theme `acorn` runtime that doesn't respond [with a matching Blade-PHP non-block template](https://github.com/roots/acorn/issues/228).
Also the aforementioned [disabling FSE using `remove_theme_support('block-templates')` in a FSE theme](https://github.com/WordPress/gutenberg/issues/45170#issuecomment-1287434694) causes this.
