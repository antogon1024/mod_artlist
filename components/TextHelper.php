<?php

namespace app\components;

/**
 * Class TextHelper
 * @package app\components
 */
class TextHelper
{
    private static $shin1 = 50;

    private static $shin2 = 30;

    public static function get_shingle($text,$n=3) {
        $shingles = [];
        $text = self::clean_text($text);
        $elements = explode(" ", $text);
        for ($i=0; $i<(count($elements) - $n + 1); $i++) {
            $shingle = '';
            for ($j=0; $j < $n; $j++){
                $shingle .= mb_strtolower(trim($elements[$i+$j]), 'UTF-8')." ";
            }
            if(strlen(trim($shingle)))
                $shingles[$i] = trim($shingle, ' -');
        }
        return $shingles;
    }

    public static function clean_text($text) {
        $new_text = preg_replace("[\,|\.|\'|\"|\\|\/]","",$text);
        $new_text = preg_replace("[\n|\t]"," ",$new_text);
        $new_text = preg_replace('/(\s\s+)/', ' ', trim($new_text));
        return $new_text;
    }

    public static function check_it($first, $second) {

        if (!$first || !$second) {
          //  echo "Отсутствуют оба или один из текстов!";
            return 0;
        }

        if (strlen($first)>200000 || strlen($second)>200000) {
         //   echo "Длина обоих или одного из текстов превысила допустимую!";
            return 0;
        }

        $result = [];

        for ($i=1; $i<3; $i++) {
            $first_shingles = array_unique(self::get_shingle($first, $i));
            $second_shingles = array_unique(self::get_shingle($second, $i));

            if(count($first_shingles) < $i-1 || count($second_shingles) < $i-1) {
             //   echo "Количество слов в тексте меньше чем длинна шинглы<br />";
                continue;
            }

            $intersect = array_intersect($first_shingles,$second_shingles);

            $merge = array_unique(array_merge($first_shingles,$second_shingles));

            $diff = (count($intersect)/count($merge))/0.01;

            $result[] = round($diff, 2);
        }

        return ($result[0] < self::$shin1 || $result[1] < self::$shin2);
    }

    public static function force_balance_tags( $text ) {
        $tagstack  = array();
        $stacksize = 0;
        $tagqueue  = '';
        $newtext   = '';
        // Known single-entity/self-closing tags
        $single_tags = array( 'area', 'base', 'basefont', 'br', 'col', 'command', 'embed', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'source' );
        // Tags that can be immediately nested within themselves
        $nestable_tags = array( 'blockquote', 'div', 'object', 'q', 'span' );

        // WP bug fix for comments - in case you REALLY meant to type '< !--'
        $text = str_replace( '< !--', '<    !--', $text );
        // WP bug fix for LOVE <3 (and other situations with '<' before a number)
        $text = preg_replace( '#<([0-9]{1})#', '&lt;$1', $text );

        while ( preg_match( '/<(\/?[\w:]*)\s*([^>]*)>/', $text, $regex ) ) {
            $newtext .= $tagqueue;

            $i = strpos( $text, $regex[0] );
            $l = strlen( $regex[0] );

            // clear the shifter
            $tagqueue = '';
            // Pop or Push
            if ( isset( $regex[1][0] ) && '/' == $regex[1][0] ) { // End Tag
                $tag = strtolower( substr( $regex[1], 1 ) );
                // if too many closing tags
                if ( $stacksize <= 0 ) {
                    $tag = '';
                    // or close to be safe $tag = '/' . $tag;

                    // if stacktop value = tag close value then pop
                } elseif ( $tagstack[ $stacksize - 1 ] == $tag ) { // found closing tag
                    $tag = '</' . $tag . '>'; // Close Tag
                    // Pop
                    array_pop( $tagstack );
                    $stacksize--;
                } else { // closing tag not at top, search for it
                    for ( $j = $stacksize - 1; $j >= 0; $j-- ) {
                        if ( $tagstack[ $j ] == $tag ) {
                            // add tag to tagqueue
                            for ( $k = $stacksize - 1; $k >= $j; $k-- ) {
                                $tagqueue .= '</' . array_pop( $tagstack ) . '>';
                                $stacksize--;
                            }
                            break;
                        }
                    }
                    $tag = '';
                }
            } else { // Begin Tag
                $tag = strtolower( $regex[1] );

                // Tag Cleaning

                // If it's an empty tag "< >", do nothing
                if ( '' == $tag ) {
                    // do nothing
                } elseif ( substr( $regex[2], -1 ) == '/' ) { // ElseIf it presents itself as a self-closing tag...
                    // ...but it isn't a known single-entity self-closing tag, then don't let it be treated as such and
                    // immediately close it with a closing tag (the tag will encapsulate no text as a result)
                    if ( ! in_array( $tag, $single_tags ) ) {
                        $regex[2] = trim( substr( $regex[2], 0, -1 ) ) . "></$tag";
                    }
                } elseif ( in_array( $tag, $single_tags ) ) { // ElseIf it's a known single-entity tag but it doesn't close itself, do so
                    $regex[2] .= '/';
                } else { // Else it's not a single-entity tag
                    // If the top of the stack is the same as the tag we want to push, close previous tag
                    if ( $stacksize > 0 && ! in_array( $tag, $nestable_tags ) && $tagstack[ $stacksize - 1 ] == $tag ) {
                        $tagqueue = '</' . array_pop( $tagstack ) . '>';
                        $stacksize--;
                    }
                    $stacksize = array_push( $tagstack, $tag );
                }

                // Attributes
                $attributes = $regex[2];
                if ( ! empty( $attributes ) && $attributes[0] != '>' ) {
                    $attributes = ' ' . $attributes;
                }

                $tag = '<' . $tag . $attributes . '>';
                //If already queuing a close tag, then put this tag on, too
                if ( ! empty( $tagqueue ) ) {
                    $tagqueue .= $tag;
                    $tag       = '';
                }
            }
            $newtext .= substr( $text, 0, $i ) . $tag;
            $text     = substr( $text, $i + $l );
        }

        // Clear Tag Queue
        $newtext .= $tagqueue;

        // Add Remaining text
        $newtext .= $text;

        // Empty Stack
        while ( $x = array_pop( $tagstack ) ) {
            $newtext .= '</' . $x . '>'; // Add remaining tags to close
        }

        // WP fix for the bug with HTML comments
        $newtext = str_replace( '< !--', '<!--', $newtext );
        $newtext = str_replace( '<    !--', '< !--', $newtext );

        return $newtext;
    }

    /**
     * Выбирает слово с правильными окончанием после числительного.
     *
     * @param int $number число
     * @param array $words варианты склонений ['яблоко', 'яблока', 'яблок']
     * @return string
     */
    public static function plural(int $number, array $words): string
    {
        return $words[($number % 100 > 4 && $number % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][min($number % 10, 5)]];
    }
}