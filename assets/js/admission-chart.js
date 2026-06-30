document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('admissionChart');
    if (!ctx || !window.admissionChartData) {
        return;
    }

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: window.admissionChartData.labels,
            datasets: [{
                label: 'Admissions by Year',
                data: window.admissionChartData.values,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796'
                ],
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Student Admissions by Year',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
});
