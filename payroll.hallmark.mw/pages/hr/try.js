const payslipData = {
    email: 'crisnickchristian@gmail.com',
    name: 'Christian Baluti',
    department: 'ICT',
    gender: 'Male',
    salary: 800000,
    paye: 160000,
    netSalary: 640000,
    pension: 40000,
    additions: 10000,
    deductions: 5000,
    welfareFund: 2000,
    bankName: 'National Bank',
    bankAccountName: 'Christian Baluti',
    bankAccountNumber: '123456789',
    bankBranch: 'Mzuzu'
};

fetch('http://localhost/hallmark/clients/payroll.hallmark.mw/pages/hr/send_payslip.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(payslipData)
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('Payslip sent successfully!');
    } else {
        console.error('Error:', data.error);
        console.log('Something went wrong.');
    }
})
.catch(error => {
    console.error('Fetch Error:', error);
    console.log('Request failed.');
});
