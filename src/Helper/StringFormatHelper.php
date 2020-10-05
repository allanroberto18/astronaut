<?php declare(strict_types=1);

namespace App\Helper;

class StringFormatHelper
{
    /**
     * @param string $str
     * @return string
     */
    public function adjustStringString(string $str): string
    {
        preg_match_all('/\p{Latin}+/u', $str, $englishWords);
        preg_match_all('/\p{Han}+/u', $str, $chineseWords);

        $englishWordsWithUniqueWords = array_unique($englishWords[0]);
        $sentence = array_merge($englishWordsWithUniqueWords, $chineseWords[0]);
        return implode(' ', $sentence);
    }
}