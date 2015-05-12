/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#333333';
	config.htmlEncodeOutput = false;
	config.entities = false;
	config.allowedContent = true;
	config.extraAllowedContent = 'div(*)';
};