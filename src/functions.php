<?php

namespace RoundingWell\Schematic;

/**
 * @param string $word
 * @return string
 */
function singular($word)
{
    if (substr($word, -1) !== 's') {
        return $word;
    }

    if (in_array(substr($word, -2), ['us', 'ss'])) {
        // Not a pluralized word
        return $word;
    }

    if (preg_match('/[ssz]es$/', $word) || preg_match('/[^aeioudgkprt]hes$/', $word)) {
        // Remove "es"
        return substr($word, 0, -2);
    }

    if (preg_match('/[^aeiou]ies$/', $word)) {
        // Replace "ies" with "y"
        return substr($word, 0, -3) . 'y';
    }

    // Remove "s"
    return substr($word, 0, -1);
}
