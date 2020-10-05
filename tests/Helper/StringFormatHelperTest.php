<?php declare(strict_types=1);

namespace Tests\Helper;

use App\Helper\StringFormatHelper;
use PHPUnit\Framework\TestCase;

class StringFormatHelperTest extends TestCase
{
    /**
     * @test
     */
    public function adjustString_withStringArgument_shouldRemoveDuplicateEnglishWordsAndSendChineseLettersToTheEnd(): void
    {
        $str = 'drinking giving jogging 喝 喝 passing 制图 giving 跑步 吃';
        $strExpected = 'drinking giving jogging passing 喝 喝 制图 跑步 吃';
        $stringFormatHelper = new StringFormatHelper();
        $this->assertEquals($strExpected, $stringFormatHelper->adjustStringString($str));
    }
}