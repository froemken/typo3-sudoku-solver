/**
 * JavaScript for plugin sudoku_solver
 *
 * Initialize Sudoku Solver in general
 *
 * @param $element
 * @constructor
 */
let SudokuSolver = function ($element) {
    let me = this;

    me.$element = $element;
    me.selectorSudokuSolverTable = 'table.sudoku-solver';
    me.buttonSolve = 'btnSolve';
    me.buttonHint = 'btnHint';

    /**
     * Test, if sudoku solver table exists
     *
     * @returns {boolean}
     */
    me.hasSudokuSolverTable = function () {
        return !!me.$element.querySelector(me.selectorSudokuSolverTable);
    };

    /**
     * Get sudoku solver table
     */
    me.getSudokuSolverTable = function () {
        return me.$element.querySelector(me.selectorSudokuSolverTable);
    };

    /**
     * Get current URL with additional parameters
     *
     * @param {string} additionalParameters
     * @return string
     */
    me.getCurrentUrlWithAdditionalParameters = function (additionalParameters) {
        if (window.location.origin) {
            return window.location.origin + window.location.pathname + '?' + additionalParameters;
        } else {
            return window.location.protocol + '//' + window.location.host + window.location.pathname + '?' + additionalParameters;
        }
    };

    me.addClickEventForSudokuNumbers = function($popoverTriggerButton, popover, event) {
        $popoverTriggerButton.innerText = event.target.innerText;
        popover.hide();
    }

    let $popoverContentElement = document.querySelector('table#sudokuNumbers');
    let $cells = $popoverContentElement.querySelectorAll('td span');

    document.querySelectorAll('[data-bs-toggle="popover"]').forEach($popoverTriggerButton => {
        let popover = new bootstrap.Popover($popoverTriggerButton, {
            html: true,
            content: $popoverContentElement
        });

        let bindClickEvent = me.addClickEventForSudokuNumbers.bind(this, $popoverTriggerButton, popover);

        $popoverTriggerButton.addEventListener('shown.bs.popover', () => {
            $cells.forEach($cell => {
                $cell.addEventListener('click', bindClickEvent, false);
            });
        });
        $popoverTriggerButton.addEventListener('hide.bs.popover', () => {
            $cells.forEach($cell => {
                $cell.removeEventListener('click', bindClickEvent);
            });
        });
    });

    me.$element.querySelector('.btnHint').addEventListener('click', function (event) {
        if (me.hasSudokuSolverTable()) {
            let data = {
                rows: []
            };
            let $tableElement = me.getSudokuSolverTable();
            $tableElement.querySelectorAll('tr').forEach($row => {
                let row = {
                    cells: []
                };
                $row.querySelectorAll('td').forEach($cell => {
                    let cell = {
                        value: $cell.innerText
                    }
                    row.cells.push(cell);
                });
                data.rows.push(row);
            });

            let additionalParameters = [
                'sudoku=' + JSON.stringify(data)
            ];

            fetch(me.getCurrentUrlWithAdditionalParameters(additionalParameters.join('&')), {
                headers: {
                    'Content-Type': 'application/json',
                    'ext-sudoku-solver': 'getHint'
                }
            }).then(response => {
                if (response.status === 200) {
                    return response.json();
                }
                return Promise.reject(response);
            }).then(hint => {
                if (hint.error) {
                    me.$element.querySelector('.message').innerText = hint.message;
                } else {
                    let value = hint.data.value;
                    let posHorizontal = hint.data.posHorizontal;
                    let x = hint.data.posHorizontal + 1;
                    let posVertical = hint.data.posVertical;
                    let y = hint.data.posVertical + 1;
                    me.$element.querySelector('.message').innerText = 'Set value ' + value + ' in row ' + x + ' and column ' + y + '.';
                }
                console.log(hint);
            }).catch(error => {
                console.warn('Request error', error);
            });
        }
    });
};

document.querySelectorAll('.tx-sudoku-solver').forEach($element => {
    new SudokuSolver($element);
});
