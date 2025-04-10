document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('sendButton');
    sendButton.addEventListener('click', function() {
        const phoneNumber = document.getElementById('phoneNumber').value.trim();

        if (phoneNumber === "") {
            alert("Por favor, completa el campo del número de teléfono.");
            return;
        }
        sendMessage(phoneNumber);
    });
});

function sendMessage(phoneNumber) {
    $.ajax({
        url: 'Reportes-Controlador.php',
        method: 'GET',
        data: { accion: 'consultaMsg' },
        dataType: 'json',
        success: function(inventario) {
            console.log("Inventario recibido:", inventario);

            let message = "Este es el inventario del día:\n";

            $.each(inventario, function(index, item) {
                message += `- ${item.nombre}: ${item.stock} ${item.unidad_medida}\n`;
            });

            const formattedNumber = phoneNumber.replace(/\D/g, '');
            const url = `https://web.whatsapp.com/send?phone=${formattedNumber}&text=${encodeURIComponent(message)}`;

            window.open(url, '_blank');
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener el inventario:", status, error);
        }
    });
}
