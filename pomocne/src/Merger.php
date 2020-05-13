<?php

class Merger {
    function merge($a, $b) {
        $atree = $this->parse($a);
        $btree = $this->parse($b);

        $atree->merge($btree);
        // $atree->print();

        return $atree->getText();
    }

    private function parse($md) {
        $parts = preg_split('/\n*(#+ [^\n]+)\n*/m', $md, -1, PREG_SPLIT_DELIM_CAPTURE);

        $root = new Node('');
        $current = $root;
        $depth = 0;
        foreach ($parts as $i => $part) {
            if (preg_match('/^(#+) (.*)/', $part, $m)) {
                $newDepth = strlen($m[1]);
                $newCurrent = new Node($part);

                if ($newDepth > $depth) {
                    $current->addChild($newCurrent);
                } elseif ($newDepth == $depth) {
                    $current->addSibling($newCurrent);
                } else {
                    while ($depth > $newDepth) {
                        $current = $current->parent;
                        $depth--;
                    }
                    $current->addSibling($newCurrent);
                }

                $current = $newCurrent;
                $depth = $newDepth;
            } else {
                $current->content = $part;
            }
        }

        return $root;
    }
}

class Node {
    private $name;
    public $content = null;
    public $parent = null;
    private $children = [];

    function __construct($name) {
        $this->name = $name;
    }

    function addChild(Node $node) {
        $this->children[$node->name] = $node;
        $node->parent = $this;
    }

    function addSibling(Node $node) {
        $this->parent->addChild($node);
    }

    function print($prefix = '') {
        echo $prefix . $this->name . "\n";
        if ($this->content) {
            echo $prefix . '    <i>' . mb_substr($this->content, 0, 50) . "…</i>\n";
        }
        foreach ($this->children as $child) {
            $child->print($prefix . '    ');
        }
    }

    function getText() {
        ob_start();
        $this->printText();
        return ob_get_clean();
    }

    private function printText() {
        if ($this->name) {
            echo $this->name, "\n\n";
        }
        if ($this->content) {
            echo $this->content, "\n\n";
        }
        foreach ($this->children as $child) {
            $child->printText();
        }
    }

    function merge(Node $other) {
        // merge content
        if ($other->content) {
            if ($this->content) {
                if (str_contains($this->content, '- ...')) {
                    $this->content = strtr($this->content, ['- ...' => $other->content]);
                } else {
                    $this->content = $other->content . "\n" . $this->content;
                }
            } else {
                $this->content = $other->content;
            }
        }

        // merge children
        foreach ($other->children as $otherChild) {
            $thisChild = $this->children[$otherChild->name] ?? null;

            if ($thisChild) {
                $thisChild->merge($otherChild);
            } else {
                $this->addChild($otherChild);
            }
        }
    }
}
