<?php
/**
 * File containing the eZContentClassAttributeTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZContentClassAttributeTest extends ezpDatabaseTestCase
{
    /*public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentClass Unit Tests" );
    }*/

    /**
     * #15898: Cannot translate a user content object
     * Make sure datatype translation flag is honored.
     *
     * @link http://issues.ez.no/15898
     */
    public function testAttributeIsTranslatable()
    {
        $stringAttribute = eZContentClassAttribute::create( 0, 'ezstring' );
        $this->assertEquals( 1, $stringAttribute->attribute('can_translate'), 'ezstring class attribute should have been translatable by default' );

        $stringAttribute = eZContentClassAttribute::create( 0, 'ezuser' );
        $this->assertEquals( 0, $stringAttribute->attribute('can_translate'), 'ezuser class attribute should NOT have been translatable by default' );
    }
}

?>
