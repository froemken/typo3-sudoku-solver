<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/typo3-sudoku-solver.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Typo3SudokuSolver\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use StefanFroemken\SudokuSolver\Domain\Exception\AlreadySolvedException;
use StefanFroemken\SudokuSolver\Domain\Factory\SudokuFactory;
use StefanFroemken\SudokuSolver\Domain\Model\Cell;
use TYPO3\CMS\Core\Http\JsonResponse;

/**
 * Main controller of sudoku_solver
 */
class GetHintMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeader('ext-sudoku-solver') !== ['getHint']) {
            return $handler->handle($request);
        }

        $getParameters = $request->getQueryParams();

        if (!isset($getParameters['sudoku'])) {
            return new JsonResponse([
                'error' => 'Request uncompleted. Missing configuration for sudoku.'
            ], 400);
        }

        $sudokuFactory = new SudokuFactory();
        $sudoku = $sudokuFactory->build(json_decode($getParameters['sudoku'], true));

        try {
            $hint = $sudoku->hint();
            if ($hint instanceof Cell) {
                return new JsonResponse([
                    'error' => false,
                    'data' => [
                        'value' => $hint->getValue(),
                        'posHorizontal' => $hint->getPosHorizontal(),
                        'posVertical' => $hint->getPosVertical(),
                        'grid' => $hint->getGridPosition(),
                    ]
                ]);
            }

            return new JsonResponse([
                'error' => true,
                'message' => 'Sorry, could not find a hint.'
            ]);
        } catch (AlreadySolvedException $alreadySolvedException) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Sudoku already solved.'
            ]);
        }
    }
}
