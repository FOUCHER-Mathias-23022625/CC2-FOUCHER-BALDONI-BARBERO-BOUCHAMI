function updateQuantity(button, change) {
    const input = button.parentElement.querySelector('input');
    let currentValue = parseInt(input.value);
    let newValue = currentValue + change;

    if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
        input.value = newValue;
    }
}