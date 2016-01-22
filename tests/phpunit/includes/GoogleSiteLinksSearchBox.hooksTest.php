<?php

class GoogleSiteLinksSearchBoxHooksTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideOnBeforePageDisplay
	 */
	public function testOnBeforePageDisplay( Title $title, $result ) {
		$context = new RequestContext();
		$context->setTitle( $title );
		$skin = $context->getSkin();
		$out = $context->getOutput();

		GoogleSiteLinksSearchBox\Hooks::onBeforePageDisplay( $out, $skin );
		$this->assertEquals( (bool)$out->getHeadItemsArray(), $result );
	}

	public function provideOnBeforePageDisplay() {
		return array(
			array( Title::newMainPage(), true ),
			array( Title::newFromText( 'TestPage' ), false ),
		);
	}
}
