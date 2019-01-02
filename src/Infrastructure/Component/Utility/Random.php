<?php

namespace App\Infrastructure\Component\Utility;


class Random
{

    /**
     * Generate a string that looks like a word (letters only, alternating consonants and vowels).
     *
     * @param int $length
     *   The desired word length.
     *
     * @return string
     */
    public function word($length) {
        mt_srand((double) microtime() * 1000000);

        $vowels = ["a", "e", "i", "o", "u"];
        $cons = ["b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
            "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr",
            "sl", "cl", "sh",
        ];

        $num_vowels = count($vowels);
        $num_cons = count($cons);
        $word = '';

        while (strlen($word) < $length) {
            $word .= $cons[mt_rand(0, $num_cons - 1)] . $vowels[mt_rand(0, $num_vowels - 1)];
        }

        return substr($word, 0, $length);
    }


}