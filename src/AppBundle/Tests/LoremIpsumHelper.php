<?php

namespace AppBundle\Tests;

/**
 * Class LoremIpsumHelper
 *
 * @package AppBundle\Tests
 */
class LoremIpsumHelper
{
    /**
     * @param int $paragraphs
     * @param int $sentences
     * @param int $words
     * @return string
     */
    public static function loremIpsum($paragraphs = 0, $sentences = 0, $words = 0)
    {
        $lorem = self::LOREM_IPSUM;
        $text = '';
        if ($paragraphs > 0) {
            $parts = explode("\n", $lorem, $paragraphs + 1);
            $text .= implode("\n", array_slice($parts, 0, $paragraphs));
            $lorem = count($parts) > $paragraphs ? trim($parts[$paragraphs], " ,\n") : '';
        }
        if ($sentences > 0) {
            $parts = explode('.', $lorem, $sentences + 1);
            $text .= implode('.', array_slice($parts, 0, $sentences)) . '.';
            $lorem = ($text != '' ? "\n" : '') . count($parts) > $sentences ? trim($parts[$sentences], " ,\n") : '';
        }
        if ($words > 0) {
            $parts = explode(" ", $lorem, $words + 1);
            $text .= ($text != '' ? ' ' : '') . implode(" ", array_slice($parts, 0, $words));
        }
        return trim($text, " ,\n");
    }

    const LOREM_IPSUM = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.
Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
}
/*echo '0, 0, 0: ' . LoremIpsumHelper::loremIpsum(0, 0, 0) . PHP_EOL;
echo '0, 0, 1: ' . LoremIpsumHelper::loremIpsum(0, 0, 1) . PHP_EOL;
echo '0, 0, 2: ' . LoremIpsumHelper::loremIpsum(0, 0, 2) . PHP_EOL;
echo '0, 0, 5: ' . LoremIpsumHelper::loremIpsum(0, 0, 5) . PHP_EOL;
echo '0, 1, 0: ' . LoremIpsumHelper::loremIpsum(0, 1, 0) . PHP_EOL;
echo '0, 1, 1: ' . LoremIpsumHelper::loremIpsum(0, 1, 1) . PHP_EOL;
echo '0, 2, 2: ' . LoremIpsumHelper::loremIpsum(0, 2, 2) . PHP_EOL;
echo '0, 5, 0: ' . LoremIpsumHelper::loremIpsum(0, 5, 0) . PHP_EOL;
echo '1, 1, 0: ' . LoremIpsumHelper::loremIpsum(1, 1, 0) . PHP_EOL;
echo '4, 0, 1: ' . LoremIpsumHelper::loremIpsum(4, 0, 1) . PHP_EOL;
echo '1, 2, 2: ' . LoremIpsumHelper::loremIpsum(1, 2, 2) . PHP_EOL;
echo '1, 1, 5: ' . LoremIpsumHelper::loremIpsum(1, 1, 5) . PHP_EOL;*/