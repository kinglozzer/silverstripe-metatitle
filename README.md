# MetaTitle
[![Build Status](https://api.travis-ci.org/kinglozzer/silverstripe-metatitle.svg?branch=master)](https://travis-ci.org/kinglozzer/silverstripe-metatitle) [![Latest Stable Version](https://poser.pugx.org/kinglozzer/metatitle/version.svg)](https://packagist.org/packages/kinglozzer/metatitle) [![Total Downloads](https://poser.pugx.org/kinglozzer/metatitle/downloads.svg)](https://packagist.org/packages/kinglozzer/metatitle) [![License](https://poser.pugx.org/kinglozzer/metatitle/license.svg)](https://packagist.org/packages/kinglozzer/metatitle)

Re-adds the “Meta Title” field that was removed in SilverStripe 3.1.

By:
Loz Calver - [Bigfork Ltd](http://www.bigfork.co.uk/).

## Contributing:

Translations were pulled from SilverStripe CMS v3.0.2 (before the `SiteTree.METATITLE` entity was removed). Pull requests are welcome for improving those translations and adding `SiteTree.METATITLEHELP` translations.

## Installation:

Installation with Composer is preferred, but not required. Both methods of installation require a `dev/build`.

#### Composer

```bash
$ composer require kinglozzer/metatitle:^1.0
```

#### ZIP Download

Simply download the [zip version](https://github.com/kinglozzer/silverstripe-metatitle/archive/master.zip) of this repository, extract it, copy the directory into your SilverStripe installation folder and rename it to “metatitle”.

## Usage:

#### CMS

Content authors can edit the meta title via the new “Title” field that’s added to the “Metadata” toggle fields:

<img src="images/cms.png" width="569" height="145" />

#### Templates

You can access the meta title with the `$MetaTitle` variable. For example, to output the meta title (with a fall-back to page title) you can use the following in templates:

```html
<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
$MetaTags(false)
```
