/**
 * Sorts an HTML table
 * 
 * @param {HTMLTableElement} table The table to sort
 * @param {number} column The index of the column
 * @param {boolean} asc Direction of sorting
 */
function sortTableByColumn(table, column, asc = false){
    const direction = asc ? 1 : -1;
    const tableBody = table.tBodies[0];
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    // Sort each row
    const sortedRows = rows.sort((firstRow, secondRow) =>{
        // Because of collspan property
        const tdNumber = column <= 1 ? column + 1 : column + 2*(column - 1) + 1;

        const firstRowValue = parseInt(firstRow.querySelector(`td:nth-child(${ tdNumber })`).textContent.trim());
        const secondRowValue = parseInt(secondRow.querySelector(`td:nth-child(${ tdNumber })`).textContent.trim());

        return firstRowValue > secondRowValue ? (1 * direction) : (-1 * direction);
    });

    // Remove all rows from the table
    while(tableBody.firstChild){
        tableBody.removeChild(tableBody.firstChild);
    }

    // Add sorted rows
    tableBody.append(...sortedRows);

    // Remember the way of sorting
    table.querySelectorAll("th").forEach(th => th.classList.remove("th-sort-asc", "th-sort-desc"));
    table.querySelector(`th:nth-child(${ column + 1})`).classList.toggle("th-sort-asc", asc);
    table.querySelector(`th:nth-child(${ column + 1})`).classList.toggle("th-sort-desc", !asc);
}

document.querySelectorAll("#gradebook th").forEach(headerCell => {
    headerCell.addEventListener("click", (e) => {
        if(e.target.tagName == "TH"){ // Не робити сортування при натисненні на кнопку, що знаходиться в шапці таблиці
            const table = document.querySelector("#gradebook");
            const colIndex = Array.prototype.indexOf.call(headerCell.parentElement.children, headerCell);
            const isAscending = headerCell.classList.contains("th-sort-asc");

            const colsCount = table.rows[0].cells.length;

            // Не сортувати першу(ПІБ) та останню колонку (стипендія) 
            if(colIndex != 0 && colIndex != colsCount - 1){
                sortTableByColumn(table, colIndex , !isAscending);
            }
        }
    });
});