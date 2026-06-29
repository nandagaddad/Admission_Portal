function getDepartments(course_id)
{
    let departmentDropdown = document.getElementById('department');

    if (!course_id) {
        departmentDropdown.innerHTML = '<option value="">Select Department</option>';
        return;
    }
    departmentDropdown.innerHTML = '<option value="">Loading...</option>';

    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/Admission_Portal/Controllers/StudentController.php?action=getDepartments&course_id=' + encodeURIComponent(course_id), true);
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {   //console.log(xhr.responseText);
            let departments = JSON.parse(xhr.responseText);
            let options = '<option value="">Select Department</option>';
            for (let i = 0; i < departments.length; i++)
            {
                options += '<option value="' + departments[i].id + '">' + departments[i].department_name + '</option>';
            }
            departmentDropdown.innerHTML = options;
        }
    };
    xhr.send();
}

function getYear(courseId) 
{
    let yearDropdown = document.getElementById('year');
    let semesterDropdown = document.getElementById('semester');

    semesterDropdown.innerHTML = '<option value="">Select Semester</option>';

    if (!courseId) {
        yearDropdown.innerHTML = '<option value="">Select Year</option>';
        yearDropdown.dataset.durationYears = '';
        return;
    }
    yearDropdown.innerHTML = '<option value="">Loading...</option>';
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/Admission_Portal/Controllers/StudentController.php?action=getYear&course_id=' + encodeURIComponent(courseId), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let years = JSON.parse(xhr.responseText);
            let options = '<option value="">Select Year</option>';
            let durationYears = parseInt(years[0].duration_years, 10) || 0;
            yearDropdown.dataset.durationYears = durationYears;

            for (let i = 1; i <= durationYears; i++) {
                options += '<option value="' + i + '">' + i + '</option>';
            }
            yearDropdown.innerHTML = options;
        }
    };
    xhr.send();
}

function getSemester(year) {
    let semesterDropdown = document.getElementById('semester');
    let yearDropdown = document.getElementById('year');
    let durationYears = parseInt(yearDropdown.dataset.durationYears, 10) || 0;

    if (!year || !durationYears) {
        semesterDropdown.innerHTML = '<option value="">Select Semester</option>';
        return;
    }
    
    let selectedYear = parseInt(year, 10);
    let firstSemester = (selectedYear - 1) * 2 + 1;
    let lastSemester = Math.min(firstSemester + 1, durationYears * 2);

    let options = '<option value="">Select Semester</option>';
    options += '<option value="' + firstSemester + '">Semester ' + firstSemester + '</option>';
    if (lastSemester > firstSemester) {
        options += '<option value="' + lastSemester + '">Semester ' + lastSemester + '</option>';
    }
    semesterDropdown.innerHTML = options;
}

function loadDepartments(courseId, selectedDepartmentId) {
    var departmentDropdown = document.getElementById('department_id');
    if (!courseId) {
        departmentDropdown.innerHTML = '<option value="">Select Department</option>';
        return;
    }
    departmentDropdown.innerHTML = '<option value="">Loading...</option>';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/Admission_Portal/Controllers/StudentController.php?action=getDepartments&course_id=' + encodeURIComponent(courseId), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var departments = JSON.parse(xhr.responseText);
            var options = '<option value="">Select Department</option>';
            for (var i = 0; i < departments.length; i++) {
                options += '<option value="' + departments[i].id + '">' + departments[i].department_name + '</option>';
            }
            departmentDropdown.innerHTML = options;
            if (selectedDepartmentId) {
                departmentDropdown.value = selectedDepartmentId;
            }
        }
    };
    xhr.send();
}

function loadYears(courseId, selectedYear, selectedSemester) {
    var yearDropdown = document.getElementById('year');
    var semesterDropdown = document.getElementById('semester');

    semesterDropdown.innerHTML = '<option value="">Select Semester</option>';

    if (!courseId) {
        yearDropdown.innerHTML = '<option value="">Select Year</option>';
        yearDropdown.dataset.durationYears = '';
        return;
    }

    yearDropdown.innerHTML = '<option value="">Loading...</option>';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/Admission_Portal/Controllers/StudentController.php?action=getYear&course_id=' + encodeURIComponent(courseId), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var years = JSON.parse(xhr.responseText);
            var options = '<option value="">Select Year</option>';
            var durationYears = parseInt(years[0].duration_years, 10) || 0;
            yearDropdown.dataset.durationYears = durationYears;

            for (var i = 1; i <= durationYears; i++) {
                options += '<option value="' + i + '">Year ' + i + '</option>';
            }
            yearDropdown.innerHTML = options;
            if (selectedYear) {
                yearDropdown.value = selectedYear;
                setSemesterOptions(selectedYear, selectedSemester);
            }
            }
    };
    xhr.send();
}

function setSemesterOptions(year, selectedSemester) {
    var semesterDropdown = document.getElementById('semester');
    var yearDropdown = document.getElementById('year');
    var durationYears = parseInt(yearDropdown.dataset.durationYears, 10) || 0;

    if (!year || !durationYears) {
        semesterDropdown.innerHTML = '<option value="">Select Semester</option>';
        return;
    }

    var selectedYear = parseInt(year, 10);
    var firstSemester = (selectedYear - 1) * 2 + 1;
    var lastSemester = Math.min(firstSemester + 1, durationYears * 2);

    var options = '<option value="">Select Semester</option>';
    options += '<option value="' + firstSemester + '">Semester ' + firstSemester + '</option>';
    if (lastSemester > firstSemester) {
        options += '<option value="' + lastSemester + '">Semester ' + lastSemester + '</option>';
    }
    semesterDropdown.innerHTML = options;
    if (selectedSemester) {
        semesterDropdown.value = selectedSemester;
    }
}
