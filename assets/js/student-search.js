function searchStudent()
{
    let year = document.getElementById('year').value;

    let tbody = document.getElementById('studentTableBody');

    tbody.innerHTML =
    `
    <tr>
        <td colspan="12" class="text-center">
            Loading...
        </td>
    </tr>
    `;

    let xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "/Admission_Portal/Controllers/StudentSearchYearController.php?year=" + encodeURIComponent(year),
        true
    );

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4)
        {
            if(xhr.status === 200)
            {   
                
                let data = JSON.parse(xhr.responseText);

                let output = "";

                if(data.length > 0)
                {
                    data.forEach(student => {
                        const address = student.address ?? '';
                        const courseName = student.course_name ?? '';
                        const departmentName = student.department_name ?? '';
                        const academicYear = student.academic_year ?? '';
                        const semester = student.semester ?? '';

                        output +=
                        `
                        <tr>
                            <td>${student.id}</td>

                            <td>${student.application_no}</td>

                            <td>
                                ${student.first_name}
                                ${student.last_name}
                            </td>

                            <td>${student.gender}</td>

                            <td>${student.email}</td>

                            <td>${student.phone}</td>

                            <td>${courseName}</td>

                            <td>${departmentName}</td>

                            <td>${academicYear}</td>

                            <td>${semester}</td>

                            <td>${address}</td>

                            <td>${student.admission_year}</td>
                            
                        </tr>
                        `;
                    });
                }
                else
                {
                    output =
                    `
                    <tr>
                        <td colspan="12"
                            class="text-center text-danger">

                            No Students Found

                        </td>
                    </tr>
                    `;
                }

                tbody.innerHTML = output;
            }
        }
    };

    xhr.send();
}