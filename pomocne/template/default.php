<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Georgia;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0 40mm;
        }
        h1, h2, h3, h4 {
            font-family: Trebuchet MS;
            font-weight: normal;
        }
        p, li {
            text-align: justify;
        }
        .titulka {
            page-break-before: always;
            page-break-after: always;
            text-align: center;
            font-size: 80pt;
            padding-top: 60mm;
        }
        hr {
            border: none;
            border-top: solid 1px #000;
            margin: 1.9em 3em; /* mimic 3 lines */
        }

        <?=$extraCss ?? ''?>
    </style>
    <script src="template/vlna.js"></script>
    <script src="template/hyphenator.js"></script>
    <script src="template/cs.js"></script>
    <script>
        Hyphenator.config({
            defaultlanguage: 'cs',
            selectorfunction: function () {
                return document.querySelectorAll('p, li')
            }
        })
        Hyphenator.run()
  </script>
</head>
<body>

<?=$content?>

<script>
    vlna('body');
</script>

</body>