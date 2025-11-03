# Whaaat? Block (Page block module for Omeka S)

[Whaaat? Block] is a page block module for [Omeka S] that allows you to easily add Frequently Asked Questions section to a page.

## Installation

Use the release zip to install it, or use the source.

* From the zip

Download the last release [WhaaatBlock.zip] from the list of releases, and uncompress it in the `modules` directory.

* From the source:

If the module was installed from the source, rename the name of the folder of the module to `WhaaatBlock`, and copy it in the `modules` directory.

Then install it like any other Omeka S module.

See general end user documentation for [Installing a module](http://omeka.org/s/docs/user-manual/modules/#installing-modules)

## Usage

You can add this FAQ block to the content of your pages.

It is possible to add multiple FAQ blocks within the same page.

In a block, you can:
* add a title and introduction to the FAQ section
* add as many questions/answers as you wish

If you want to export your FAQ and re-import it to another page or site, you can use the Export/Import buttons.

The export file is in JSON format and is structured as follows:

```
{
  "title": "À propos du bloc FAQ",
  "description": "Quelques réponses à vos question sur le bloc FAQ",
  "faqs": [
    {
      "question": "Qu'est-ce qu'Omeka S ?",
      "answer": "Omeka S est un CMS open-source pour la publication de collections numériques et la gestion de contenus culturels."
    },
    {
      "question": "Comment ajouter une FAQ sur ma page ?",
      "answer": "Dans l'éditeur de pages du site, ajoutez un bloc FAQ, saisissez le titre et les questions/réponses, puis configurez le comportement de l'accordéon selon vos besoins."
    }
  ]
}
```

# Copyright

Whaaat? Block is Copyright © 2025-present Kaeness, France https://kaeness.fr

Kaeness distributes this module source code under the GNU General Public License, version 3 (GPLv3).
The full text of this license is given in the license file.

The Omeka name is a registered trademark of the Corporation for Digital Scholarship.

Third-party copyright in this distribution is noted where applicable.

All rights not expressly granted are reserved.

[Whaaat? Block]: https://github.com/Kaeness/Omeka-S-module-WhaaatBlock
[Omeka S]: https://omeka.org/s
[WhaaatBlock.zip]: https://github.com/Kaeness/Omeka-S-module-WhaaatBlock/releases