function updateButtonAndSelectAllCheckbox(id) {
    let $bulkActions = document.querySelectorAll('button[data-bulk-id='+ id +']');
    let $checkAllCheckbox = document.querySelector('[data-bulk-id='+ id + '][data-select=all]');
    let $checkboxes = document.querySelectorAll('[data-bulk-id=' + id + '][data-select=single]');
    let $checkedCheckboxes = document.querySelectorAll('[data-bulk-id=' + id + '][data-select=single]:checked');

    for(let $button of $bulkActions) {
        $button.disabled = $checkedCheckboxes.length === 0;
    }
}

function selectAll(id) {
    let $checkboxes = document.querySelectorAll('[data-bulk-id=' + id + '][data-select=single]');

    for(let $checkbox of $checkboxes) {
        $checkbox.checked = true;
    }

    updateButtonAndSelectAllCheckbox(id);
}

function unselectAll(id) {
    let $checkboxes = document.querySelectorAll('[data-bulk-id=' + id + '][data-select=single]');

    for(let $checkbox of $checkboxes) {
        $checkbox.checked = false;
    }

    updateButtonAndSelectAllCheckbox(id);
}

for(let $selectAllCheckbox of document.querySelectorAll('[data-select=all]')) {
    updateButtonAndSelectAllCheckbox($selectAllCheckbox.getAttribute('data-bulk-id'));

    $selectAllCheckbox.addEventListener('change', () => {
        if($selectAllCheckbox.checked) {
            selectAll($selectAllCheckbox.getAttribute('data-bulk-id'));
        } else {
            unselectAll($selectAllCheckbox.getAttribute('data-bulk-id'));
        }
    });
}

for(let $selectCheckbox of document.querySelectorAll('[data-select=single]')) {
    $selectCheckbox.addEventListener('change', () => {
        updateButtonAndSelectAllCheckbox($selectCheckbox.getAttribute('data-bulk-id'));
    });
}

for(let $actionButton of document.querySelectorAll('button[data-bulk-id]')) {
    $actionButton.addEventListener('click', (ev) => {
        ev.preventDefault();
        let id = $actionButton.getAttribute('data-bulk-id');

        let $checkedCheckboxes = document.querySelectorAll('[data-bulk-id=' + id + '][data-select=single]:checked');
        let ids = [ ];

        for(let $checkbox of $checkedCheckboxes) {
            ids.push($checkbox.value);
        }

        let url = $actionButton.getAttribute('data-url')
            + '?'
            + $actionButton.getAttribute('data-param-name')
            + '='
            + ids.join(',');

        window.location.href = url;
    });
}