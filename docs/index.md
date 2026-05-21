---
layout: home

hero:
  name: cakephp-shim
  text: Bridge CakePHP versions
  tagline: A zero-dependency compatibility layer that shims functionality up and down across major CakePHP versions — so large applications can be upgraded one step at a time.
  image:
    src: /logo.svg
    alt: cakephp-shim
  actions:
    - theme: brand
      text: Get Started
      link: /guide/
    - theme: alt
      text: Upgrade Guide
      link: /guide/upgrade
    - theme: alt
      text: View on GitHub
      link: https://github.com/dereuromark/cakephp-shim

features:
  - icon: 🌉
    title: Smooth Upgrades
    details: Keep large legacy codebases running on the new major version first, then refactor at your own pace. Touch far fewer lines to get green again.
  - icon: 🪶
    title: Zero Dependencies
    details: Apart from the framework core itself, this plugin pulls in nothing. It is safe to have around — nothing loads unless you actually use it.
  - icon: 🧩
    title: Pick What You Need
    details: Use only the pieces you want. Database types, ORM shims, controller traits, view helpers, and test utilities are all opt-in.
  - icon: 🗃️
    title: ORM Compatibility
    details: Re-use legacy table properties and behaviors, custom database types, and a paginator that still understands contain and conditions options.
  - icon: 🛡️
    title: Type-Safe Controllers
    details: Assert and cast request data into the right scalar types with the CastTrait — static-analyzer friendly for PHPStan and strict types.
  - icon: 🧪
    title: Test Helpers
    details: A TestSuite trait for invoking protected methods and properties, capturing console output, and conditional debug output for faster tests.
---
