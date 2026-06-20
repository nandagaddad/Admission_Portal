function searchStudent()
{
    let year = document.getElementById('year').value;

    let tbody = document.getElementById('studentTableBody');

    tbody.innerHTML =
    `
    <tr>
        <td colspan="10" class="text-center">
            Loading...
        </td>
    </tr>
    `;

    let xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "/Admission_Portal/ajax/filterYear.php?year=" + encodeURIComponent(year),
        true
    );

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState === 4)
        {
            if(xhr.status === 200)
            {   //console.log(xhr.responseText);
                
                let data = JSON.parse(xhr.responseText);

                let output = "";

                if(data.length > 0)
                {
                    data.forEach(student => {

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

                            <td>${student.course}</td>

                            <td>${student.admission_year}</td>

                            <td>${student.status}</td>
                        </tr>
                        `;
                    });
                }
                else
                {
                    output =
                    `
                    <tr>
                        <td colspan="9"
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