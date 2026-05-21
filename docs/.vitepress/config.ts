import { defineConfig } from 'vitepress'

function mainSidebar() {
  return [
    {
      text: 'Guide',
      items: [
        { text: 'Overview', link: '/guide/' },
        { text: 'Installation', link: '/guide/installation' },
        { text: 'Preconditions', link: '/guide/preconditions' },
        { text: 'Upgrade Guide', link: '/guide/upgrade' },
      ],
    },
    {
      text: 'Database',
      items: [
        { text: 'UUID Type', link: '/database/uuid' },
        { text: 'Time Type', link: '/database/time' },
        { text: 'Year Type', link: '/database/year' },
        { text: 'Array Type', link: '/database/array' },
      ],
    },
    {
      text: 'Datasource',
      items: [
        { text: 'NumericPaginator', link: '/datasource/numeric-paginator' },
        { text: 'LegacyModelAwareTrait', link: '/datasource/legacy-model-aware-trait' },
      ],
    },
    {
      text: 'Model',
      items: [
        { text: 'Table', link: '/model/table' },
        { text: 'Entity', link: '/model/entity' },
        { text: 'Nullable Behavior', link: '/model/nullable' },
      ],
    },
    {
      text: 'Controller',
      items: [
        { text: 'Controller', link: '/controller/' },
        { text: 'CastTrait', link: '/controller/cast-trait' },
        { text: 'RedirectOutOfBoundsTrait', link: '/controller/redirect-out-of-bounds-trait' },
      ],
    },
    {
      text: 'Component',
      items: [
        { text: 'RequestHandler', link: '/component/request-handler' },
      ],
    },
    {
      text: 'View Helpers',
      items: [
        { text: 'Configure Helper', link: '/view/configure' },
        { text: 'Cookie Helper', link: '/view/cookie' },
        { text: 'Number Helper', link: '/view/number' },
      ],
    },
    {
      text: 'Utility',
      items: [
        { text: 'Inflector', link: '/utility/inflector' },
      ],
    },
    {
      text: 'Testing',
      items: [
        { text: 'TestSuite', link: '/testing/' },
      ],
    },
  ]
}

export default defineConfig({
  title: 'cakephp-shim',
  description: 'A CakePHP plugin that shims functionality up and down across major framework versions to ease upgrades.',
  base: '/cakephp-shim/',
  lastUpdated: true,
  cleanUrls: true,
  sitemap: {
    hostname: 'https://dereuromark.github.io/cakephp-shim/',
  },
  head: [
    ['link', { rel: 'icon', href: '/cakephp-shim/favicon.svg', type: 'image/svg+xml' }],
  ],
  themeConfig: {
    logo: '/logo.svg',
    nav: [
      { text: 'Guide', link: '/guide/', activeMatch: '/guide/' },
      { text: 'Database', link: '/database/uuid', activeMatch: '/database/' },
      { text: 'Model', link: '/model/table', activeMatch: '/(model|datasource)/' },
      { text: 'Controller', link: '/controller/', activeMatch: '/(controller|component)/' },
      { text: 'View', link: '/view/configure', activeMatch: '/(view|utility|testing)/' },
      {
        text: 'Links',
        items: [
          { text: 'GitHub', link: 'https://github.com/dereuromark/cakephp-shim' },
          { text: 'Packagist', link: 'https://packagist.org/packages/dereuromark/cakephp-shim' },
          { text: 'Issues', link: 'https://github.com/dereuromark/cakephp-shim/issues' },
        ],
      },
    ],
    sidebar: {
      '/guide/': mainSidebar(),
      '/database/': mainSidebar(),
      '/datasource/': mainSidebar(),
      '/model/': mainSidebar(),
      '/controller/': mainSidebar(),
      '/component/': mainSidebar(),
      '/view/': mainSidebar(),
      '/utility/': mainSidebar(),
      '/testing/': mainSidebar(),
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/dereuromark/cakephp-shim' },
    ],
    search: {
      provider: 'local',
    },
    editLink: {
      pattern: 'https://github.com/dereuromark/cakephp-shim/edit/master/docs/:path',
      text: 'Edit this page on GitHub',
    },
    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright Mark Scherer',
    },
  },
})
