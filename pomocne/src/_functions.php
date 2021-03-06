<?php

function join_multiline_listitems($s) {
    $re = '/^ +-[^\n]+(\n?\n +[^- ][^\n]+)+/m';

    return preg_replace_callback($re, function ($m) {
        return strtr($m[0], ["\n" => '<br>']);
    }, $s);
}

function strangeformat_to_markdown($s) {
    $re = '/^[^:\n]+:(\n +-[^\n]+)+/m';
    $reIn = [
        '/^    /m'      => '',
        '/^([^:]+):\n/' => '## $1'."\n\n",
    ];

    return preg_replace_callback($re, function ($m) use ($reIn) {
        return preg_replace(array_keys($reIn), array_values($reIn), $m[0]);
    }, $s);
}

function markdown_to_html($md) {
    // speed https://github.com/kzykhys/Markbench#readme
    return (new ParsedownExtra)->text($md);
}

function markdown_merge($primary, $secondary) {
    return (new Merger)->merge($primary, $secondary);
}

function html_to_pdf($htmlString, $pdfOut) {
    $tmp = __DIR__ . '/../tmp.html'; // must be .html and must be in same directory as template
    file_put_contents($tmp, $htmlString);

    $args = [
        __DIR__ . '/../vendor/bin/wkhtmltopdf-amd64',
        '-T', '30mm', '-R', '0mm', '-B', '60mm', '-L', '0mm',
        // '--print-media-type',
        '--zoom', '1.25',
        // '--javascript-delay', '2000',
        // hack na počkání do dokončení js (pokud bude potřeba)
        // '--run-script',  "setInterval(function(){ if(document.readyState=='complete') window.status='done'; }, 100)",
        // '--window-status',  'done',
        '--debug-javascript',
        $tmp,
        $pdfOut,
    ];

    system(implode(' ', array_map('escapeshellarg', $args)));

    unlink($tmp);
}

function postprocess($text, $male) {
    $replacements = [
        // typography
        '/"(\w[^"]+)"/'  => '„$1“',
        '/(\S) \- (\S)/' => '$1 – $2', // ndash
        '/\.\.\./'       => '…',

        // marcos
        '/\(titulka:\s*([^\)]+)\)/' => '<div class="titulka">$1</div>',
        '/\(konec strany\)/'        => '<div class="konecstrany"></div>',

        // gendered verbs
        '/(\w)\/(a|á)(\W)/' => $male ? '$1$3' : '$1$2$3',

        // comments
        '/ *\/\/.*$/m' => '',
        '/TODO/'       => '<span class="todo">TODO</span>',
    ];

    return preg_replace(array_keys($replacements), array_values($replacements), $text);
}

function str_contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

function fnmatch_any($patterns, $string) {
    foreach ($patterns as $pattern) {
        if (fnmatch($pattern, $string)) {
            return true;
        }
    }

    return false;
}

function read_mtz($file) {
    $text = file_get_contents($file);
    preg_match_all("/\/\/ MTZ ([^\n]*)/", $text, $mtzs);
    $out = implode('', array_map(fn ($e) => "- $e\n", $mtzs[1]));
    return $out;
}
