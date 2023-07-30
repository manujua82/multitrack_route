$(function () {
    $( "#driverInput").on( 'input', (event) => {
        const value = event.target.value;
        console.log(value);
    });
});