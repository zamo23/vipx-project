
fetch('php/get_lanzamiento.php')
    .then(response => response.json())
    .then(data => {
        if (data.fecha_lanzamiento) {
            
            let countDownDate = new Date(data.fecha_lanzamiento).getTime();

            let countdownfunction = setInterval(function() {
                let now = new Date().getTime();
                let distance = countDownDate - now;

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById("days").innerText = days;
                document.getElementById("hours").innerText = hours;
                document.getElementById("minutes").innerText = minutes;
                document.getElementById("seconds").innerText = seconds;

                if (distance < 0) {
                    clearInterval(countdownfunction);
                    document.getElementById("countdown").innerHTML = "EXPIRED";
                }
            }, 1000);
        } else {
            console.error('No se pudo obtener la fecha de lanzamiento');
        }
    })
    .catch(error => {
        console.error('Error al obtener la fecha de lanzamiento:', error);
    });

// correos electrónicos
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    let correo = document.querySelector('input[type="email"]').value;

    fetch('php/guardar_correo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'correo=' + encodeURIComponent(correo)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('successMessage');
        } else {
            showAlert('errorMessage');
        }
    })
    .catch(error => {
        showAlert('errorMessage');
        console.error('Error:', error);
    });
});

function showAlert(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeAlert(id) {
    document.getElementById(id).style.display = 'none';
}
