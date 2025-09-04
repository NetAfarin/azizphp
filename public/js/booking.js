document.getElementById('employee_select').addEventListener('change', function () {
    const servicesSelect = document.getElementById('service_select');
    const employeeId = this.value;
    if (!employeeId) return;
    document.getElementById('service_select').disabled = (employeeId == 0);
    if(employeeId == 0){
        servicesSelect.innerHTML = '';
        return;
    }
    fetch(`${BASE_URL}/admin/booking/getServices/${employeeId}`)
        .then(res => res.json())
        .then(data => {
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
    const servicesSelect = document.getElementById('service_select').value;
    document.getElementById('duration_select').disabled = (servicesSelect == 0);

    fetch(`${BASE_URL}/admin/booking/getServiceDuration/${employeeId}/${serviceId}`)
        .then(res => res.json())
        .then(data => {
            durationSelect.innerHTML = '<option value="0">انتخاب مدت زمان</option>';
            data.forEach(duration => {
                const option = document.createElement('option');
                option.value = duration.id;
                option.textContent = duration.estimated_duration_hhmm;
                durationSelect.appendChild(option);
            });
            durationSelect.disabled = false;
        })
        .catch(err => {
            console.error("خطا در دریافت مدت زمان:", err);
            durationSelect.disabled = true;
        });
});
