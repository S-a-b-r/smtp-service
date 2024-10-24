async function sendData() {
    try {
        const response = await fetch('http://localhost:8081', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                key1: 'value1',
                key2: 'value2'
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Server response:', data);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}

// Call the function
sendData();
