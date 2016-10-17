/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	config.uiColor = '#EFEFEF';
    config.toolbar = [
        ["Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript",],
        ["NumberedList", "BulletedList", "Indent", "Blockquote"],
        ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
        ["Link", "Image"],
        ["Format", "TextColor"],
        ["Source"]
    ];

}
