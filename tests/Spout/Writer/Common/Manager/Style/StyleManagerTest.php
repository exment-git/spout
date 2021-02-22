<?php

namespace Box\Spout\Writer\Common\Manager\Style;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class StyleManagerTest
 */
class StyleManagerTest extends TestCase
{
    /**
     * @return StyleManager
     */
    private function getStyleManager() : StyleManager
    {
        $style = (new StyleBuilder())->build();
        $styleRegistry = new StyleRegistry($style);

        return new StyleManager($styleRegistry);
    }

    public function testApplyExtraStylesIfNeededShouldApplyWrapTextIfCellContainsNewLine() : void
    {
        $style = (new StyleBuilder())->build();
        $this->assertFalse($style->shouldWrapText());

        $styleManager = $this->getStyleManager();
        $managedStyle = $styleManager->applyExtraStylesIfNeeded(new Cell("multi\nlines", $style));

        $this->assertTrue($managedStyle->isUpdated());
        $this->assertTrue($managedStyle->getStyle()->shouldWrapText());
    }

    public function testApplyExtraStylesIfNeededShouldReturnNullIfWrapTextNotNeeded() : void
    {
        $style = (new StyleBuilder())->build();
        $this->assertFalse($style->shouldWrapText());

        $styleManager = $this->getStyleManager();
        $managedStyle = $styleManager->applyExtraStylesIfNeeded(new Cell('oneline', $style));

        $this->assertFalse($managedStyle->isUpdated());
    }

    public function testApplyExtraStylesIfNeededShouldReturnNullIfWrapTextAlreadyApplied() : void
    {
        $style = (new StyleBuilder())->setShouldWrapText()->build();
        $this->assertTrue($style->shouldWrapText());

        $styleManager = $this->getStyleManager();
        $managedStyle = $styleManager->applyExtraStylesIfNeeded(new Cell("multi\nlines", $style));

        $this->assertFalse($managedStyle->isUpdated());
    }
}
