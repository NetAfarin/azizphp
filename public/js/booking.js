document.getElementById('employee_select').addEventListener('change', function () {
    const employeeId = this.value;
    if (!employeeId) return;

    fetch(`${BASE_URL}/admin/booking/getServices/${employeeId}`)
        .then(res => res.json())
        .then(data => {
            const servicesSelect = document.getElementById('service_select');
            servicesSelect.innerHTML = '';
            data.forEach(service => {
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = service.fa_title;
                servicesSelect.appendChild(option);
            });
        });
});

document.getElementById('service_select').addEventListener('change', function () {
    const serviceId = this.value;
    const employeeId = document.getElementById('employee_select').value;
    if (!serviceId || !employeeId) return;

    fetch(`${BASE_URL}/admin/booking/getServiceDuration/${employeeId}/${serviceId}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('service_time').value = data.estimated_duration_hhmm;
        });
});

