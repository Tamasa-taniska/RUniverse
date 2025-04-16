function downloadPDF() {
    const element = document.getElementById('marks-content');

    // Optional config
    const options = {
        margin:       0,
        filename:     'My-Marksheet.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(options).from(element).save();
}
