function nextInputEnterNavigation() {
    const inputs = document.querySelectorAll('.nextInputEnter');    
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', (event) => {
            let nextInput;

            switch (event.key) {
                case 'ArrowUp':
                    event.preventDefault();
                    nextInput = inputs[index - 1]; // Input anterior
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    nextInput = inputs[index + 1]; // Siguiente input
                    break;
                case 'ArrowLeft':
                    event.preventDefault();
                    nextInput = inputs[index - 1]; // Input anterior en la fila
                    break;
                case 'ArrowRight':
                    event.preventDefault();
                    nextInput = inputs[index + 1]; // Siguiente input en la fila
                    break;
            }

            if (nextInput) {
                nextInput.focus();
                nextInput.select();
            }
        });
    });
}

function nextInputEnter () {
    const inputs = document.querySelectorAll('.nextInputEnter');    
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();                
                const nextInput = inputs[index + 1];
                if (nextInput) {
                    nextInput.focus(); 
                    nextInput.select();
                }
            }
        });
    });
}

nextInputEnterNavigation();
nextInputEnter();
