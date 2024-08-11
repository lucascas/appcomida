function logError(message, error) {
    const timestamp = new Date().toISOString();
    const logMessage = `${timestamp} - ${message}: ${error.toString()}\n`;
    
    // En una aplicación real, aquí se enviaría el error a un servicio de registro
    // Por ahora, solo lo imprimimos en la consola
    console.error(logMessage);
}

fetch('save_plan.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(plan),
})
.then(response => response.json())
.then(data => {
    if (data.error) {
        console.error('Error saving plan:', data.error);
    } else {
        console.log('Plan saved successfully!', data);
    }
})
.catch(error => {
    console.error('Error saving plan:', error);
});
