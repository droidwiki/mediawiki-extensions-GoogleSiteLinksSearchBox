<?php

namespace GoogleSiteLinksSearchBox;

use OutputPage;
use Skintemplate;
use SpecialPage;
use Html;
use FormatJson;
use WebRequest;

/**
 * Hook handler class.
 */
class Hooks {
	/**
	 * BeforePageDisplay hook handler. Checks, if this page should have
	 * the JSON-LD data in the head (only main pages) or not and adds it is needed.
	 *
	 * @param OutputPage $out
	 * @param SkinTemplate $skin
	 */
	public static function onBeforePageDisplay( OutputPage $out, SkinTemplate $skin ) {
		// get the Title object of this OutputPage
		$title = $out->getTitle();
		// check, if the page is the main page (which is, currently, the only condition for this markup)
		if ( $title->isMainPage() ) {
			// build the metadata script head element based on:
			// https://developers.google.com/structured-data/slsb-overview
			$script = Html::openElement( 'script', array( 'type' => 'application/ld+json' ) ) .
				FormatJson::encode( array(
					'@context' => 'http://schema.org',
					'@type' => 'WebSite',
					'url' => WebRequest::detectServer(),
					'potentialAction' => array(
						'@type' => 'SearchAction',
						'target' =>
							// we can't use the $subpage param of SpecialPage::getTitleFor, because
							// it'll be url encoded.
							SpecialPage::getTitleFor( 'Search' )->getCanonicalURL() .
							'/{search_term_string}',
						'query-input' => 'required name=search_term_string',
					),
				) ) .
				Html::closeElement( 'script' );

			// adds the script to the head
			$out->addHeadItem( 'googlesitelinkssearchbox', $script );
		}
	}

	/**
	 * UnitTestList hook handler to add phpunit test directory.
	 *
	 * @param array $files
	 * @return bool
	 */
	public static function onUnitTestList( &$files ) {
		$files[] = __DIR__ . '/../tests/phpunit';

		return true;
	}
}
