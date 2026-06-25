// Shared application scripts for Admission Portal

document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'add-dept') {
        const list = document.getElementById('departments-list');
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';
        wrapper.innerHTML = '<input type="text" name="departments[]" class="form-control" placeholder="Department name" required> <button class="btn btn-outline-secondary remove-dept" type="button">Remove</button>';
        list.appendChild(wrapper);
    }

    if (e.target && e.target.classList.contains('remove-dept')) {
        const list = document.getElementById('departments-list');
        const groups = list.querySelectorAll('.input-group');
        if (groups.length > 1) {
            const group = e.target.closest('.input-group');
            if (group) group.remove();
        } else {
            alert('At least one department is required.');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.deleteBtn');
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const studentId = this.getAttribute('data-student-id');
            const studentName = this.getAttribute('data-student-name');
            const idField = document.getElementById('deleteStudentId');
            const nameField = document.getElementById('deleteStudentName');
            if (idField) idField.value = studentId;
            if (nameField) nameField.textContent = studentName;
        });
    });

    document.querySelectorAll('.modal').forEach(function(modalEl) {
        modalEl.addEventListener('hide.bs.modal', function() {
            var active = document.activeElement;
            if (active && modalEl.contains(active)) {
                active.blur();
            }
        });
    });
});
