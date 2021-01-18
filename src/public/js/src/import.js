window.addEventListener('load', function () {
    document.getElementById('truncate').addEventListener('click', evt => {
        let checkbox = evt.currentTarget;
        
        if (checkbox.checked) {
            let confirmation = confirm('Are you sure? This will delete all data in questions, options, and votes table.');
            if (confirmation == true) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        }
    })
});