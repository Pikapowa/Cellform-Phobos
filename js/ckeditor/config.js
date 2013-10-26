/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	// Smileys
	// Android Style
	/*config.smiley_path = CKEDITOR.basePath+'plugins/smiley/images/android/';
	config.smiley_images = ['angel_smile.png', 'angry_smile.png', 'confused_smile.png', 'cry_smile.png', 'embarrassed_smile.png', 'heart.png', 'kiss.png', 'omg_smile.png', 'regular_smile.png', 'sad_smile.png', 'shades_smile.png', 'wink_smile.png'];
	config.smiley_descriptions = ['', '', '', '', '', '', '', '', '', '', '', ''];*/

	// Japan style
	config.smiley_path = CKEDITOR.basePath+'plugins/smiley/images/japan/';
	config.smiley_images = ['angel_smile.gif', 'angry_smile.gif', 'confused_smile.gif', 'dance_smile.gif', 'dead_smile.gif', 'fear_smile.gif', 'heart1.gif', 'heart2_smile.gif', 'heu_smile.gif', 'laugh1_smile.gif', 'laugh2_smile.gif', 'like_smile.gif', 'omg_smile.gif', 'sad_smile.gif', 'xmas_smile.gif', 'yes_smile.gif', 'youpi1_smile.gif', 'youpi2_smile.gif'];
	config.smiley_descriptions = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
};
