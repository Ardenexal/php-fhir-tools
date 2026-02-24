<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;

$lexer  = new FHIRPathLexer();
$parser = new FHIRPathParser();
$eval   = new FHIRPathEvaluator();

function run(string $expr, mixed $res, FHIRPathLexer $lexer, FHIRPathParser $parser, FHIRPathEvaluator $eval): void
{
    $result = $eval->evaluate($parser->parse($lexer->tokenize($expr)), $res);
    echo $expr . ' => [' . implode(', ', array_map('json_encode', $result->toArray())) . ']' . PHP_EOL;
}

// collection-wide via member access
run('(1 | 2 | 3).count()', null, $lexer, $parser, $eval);
run('(1 | 2 | 3).isDistinct()', null, $lexer, $parser, $eval);
run('(1 | 2 | 1).isDistinct()', null, $lexer, $parser, $eval);
run('(1 | 2).subsetOf(1 | 2 | 3)', null, $lexer, $parser, $eval);
run('(1 | 2 | 3).supersetOf(1 | 2)', null, $lexer, $parser, $eval);
run('true.not()', null, $lexer, $parser, $eval);
run('false.not()', null, $lexer, $parser, $eval);

// first/last via member access
$res = ['items' => [10, 20, 30]];
run('items.first()', $res, $lexer, $parser, $eval);
run('items.last()', $res, $lexer, $parser, $eval);
run('items.count()', $res, $lexer, $parser, $eval);
run('items.empty()', $res, $lexer, $parser, $eval);
