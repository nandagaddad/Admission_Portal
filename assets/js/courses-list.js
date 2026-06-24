document.addEventListener('click', function(e){
    // Edit button
    if (e.target && e.target.classList.contains('editBtn')){
        const id = e.target.getAttribute('data-id');
        fetch('../../controllers/CoursesController.php?action=getCourse&id='+encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                if (!data) return;
                document.getElementById('editCourseId').value = data.id;
                document.getElementById('editCourseName').value = data.course_name;
                document.getElementById('editDuration').value = data.duration_years;

                const list = document.getElementById('edit-departments-list');
                list.innerHTML = '';
                if (data.departments && data.departments.length) {
                    data.departments.forEach(function(d){
                        const div = document.createElement('div');
                        div.className = 'input-group mb-2';
                        const val = d.department_name || d;
                        div.innerHTML = '<input type="text" name="departments[]" class="form-control" value="'+(val.replace(/\"/g,'\&quot;'))+'" required> <button class="btn btn-outline-secondary edit-remove-dept" type="button">Remove</button>';
                        list.appendChild(div);
                    });
                }

                // show modal
                var myModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
                myModal.show();
            });
    }

    if (e.target && e.target.classList.contains('deleteBtn')){
        const id = e.target.getAttribute('data-id');
        // fetch name for display
        fetch('../../controllers/CoursesController.php?action=getCourse&id='+encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                document.getElementById('deleteCourseId').value = data.id;
                document.getElementById('deleteCourseName').textContent = data.course_name;
                var myModal = new bootstrap.Modal(document.getElementById('deleteCourseModal'));
                myModal.show();
            });
    }

    if (e.target && e.target.id === 'edit-add-dept'){
        const list = document.getElementById('edit-departments-list');
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';
        wrapper.innerHTML = '<input type="text" name="departments[]" class="form-control" placeholder="Department name" required> <button class="btn btn-outline-secondary edit-remove-dept" type="button">Remove</button>';
        list.appendChild(wrapper);
    }

    if (e.target && e.target.classList.contains('edit-remove-dept')){
        const list = document.getElementById('edit-departments-list');
        const groups = list.querySelectorAll('.input-group');
        if (groups.length > 1) {
            const group = e.target.closest('.input-group');
            if (group) group.remove();
        } else {
            alert('At least one department is required.');
        }
    }
});
