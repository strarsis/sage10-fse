/**
 * Compiler configuration
 *
 * @see {@link https://roots.io/sage/docs sage documentation}
 * @see {@link https://bud.js.org/learn/config bud.js configuration guide}
 *
 * @type {import('@roots/bud').Config}
 */
export default async (app) => {
  /**
   * Application assets & entrypoints
   *
   * @see {@link https://bud.js.org/reference/bud.entry}
   * @see {@link https://bud.js.org/reference/bud.assets}
   */
  app
    .entry({
      initial: {
        import: ["@styles/initial"],
      },

      app: {
        import: ["@scripts/app", "@styles/app"],
        dependOn: ["initial"],
      },

      editor: {
        import: ["@scripts/editor", "@styles/editor"],
      },
    })

    .assets(['images']);

  /**
   * Set public path
   *
   * @see {@link https://bud.js.org/reference/bud.setPublicPath}
   */
  app.setPublicPath('/app/themes/sage/public/');

  /**
   * Development server settings
   *
   * @see {@link https://bud.js.org/reference/bud.setUrl}
   * @see {@link https://bud.js.org/reference/bud.setProxyUrl}
   * @see {@link https://bud.js.org/reference/bud.watch}
   */
  app
    .setUrl('http://localhost:3000')
    .setProxyUrl('http://example.test')
    .watch(['resources/views', 'app']);

  /**
   * Generate WordPress `theme.json`
   *
   * @note This overwrites `theme.json` on every build.
   *
   * @see {@link https://bud.js.org/extensions/sage/theme.json}
   * @see {@link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json}
   */
  app.wpjson
    .setSettings({
      background: {
        backgroundImage: true,
      },
      color: {
        custom: false,
        customDuotone: false,
        customGradient: false,
        defaultDuotone: false,
        defaultGradients: false,
        defaultPalette: false,
        duotone: [],
      },
      custom: {
        spacing: {},
        typography: {
          'font-size': {},
          'line-height': {},
        },
      },
      spacing: {
        padding: true,
        units: ['px', '%', 'em', 'rem', 'vw', 'vh'],
      },
      typography: {
        customFontSize: false,
      },
    })


    .setOption('templateParts', [
      {
        "name": "header",
        "title": "Header",
        "area": "header",
      },

      {
        "name": "footer",
        "title": "Footer",
        "area": "footer",
      },

      {
        "name": "example",
        "title": "Generic example",
        "area": "uncategorized", // (default is `uncategorized`, its label is `General`)
      },
    ])

    .setOption('customTemplates', [
      {
        "name":  "page-without-header",
        "title": "Page without header",
        // description currently not supported (@see https://github.com/WordPress/gutenberg/issues/44097)
      },
    ])


    .useTailwindColors()
    .useTailwindFontFamily()
    .useTailwindFontSize();
};
