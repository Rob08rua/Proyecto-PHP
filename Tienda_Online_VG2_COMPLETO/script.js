document.addEventListener('DOMContentLoaded', () => {
    const carritoIcon = document.querySelector('.carrito a');
    const carritoContent = document.getElementById('carrito-content');
    const botonesAgregar = document.querySelectorAll('.item button');
    const carrito = [];
    const loginModal = document.getElementById('login-modal');
    const loginForm = document.getElementById('login-form');
    const loginBtn = document.getElementById('login-btn');
    const closeBtn = document.querySelector('.close');

    // Muestra el modal de inicio de sesión
    loginBtn.addEventListener('click', () => {
        loginModal.style.display = 'block';
    });

    // Cierra el modal cuando se hace clic en el botón de cerrar
    closeBtn.addEventListener('click', () => {
        loginModal.style.display = 'none';
    });

    // Cierra el modal si se hace clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target == loginModal) {
            loginModal.style.display = 'none';
        }
    });

    // Captura el envío del formulario de inicio de sesión
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Evita el envío por defecto del formulario

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Enviar datos al backend
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si la autenticación es exitosa, guarda el nombre del usuario
                localStorage.setItem('username', data.username);
                loginModal.style.display = 'none';
                mostrarNombreUsuario(data.username);
            } else {
                alert('Correo o contraseña incorrectos');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al iniciar sesión.');
        });
    });

    // Función para mostrar el nombre del usuario en la interfaz
    function mostrarNombreUsuario(username) {
        const loginContainer = document.querySelector('.login-container');
        loginContainer.innerHTML = `<span>Bienvenido, ${username}</span> <button id="logout-btn">Cerrar Sesión</button>`;
        
        // Agregar evento al botón de Cerrar Sesión
        const logoutBtn = document.getElementById('logout-btn');
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('username');
            location.reload(); // Recargar la página para volver al estado sin sesión
        });
    }

    // Si ya hay una sesión iniciada, muestra el nombre del usuario al cargar la página
    const username = localStorage.getItem('username');
    if (username) {
        mostrarNombreUsuario(username);
    }

    // Funcionalidad del carrito (sin cambios)
    carritoIcon.addEventListener('click', () => {
        carritoContent.classList.toggle('active');
    });

    botonesAgregar.forEach(boton => {
        boton.addEventListener('click', (event) => {
            const item = event.target.closest('.item');
            const nombre = item.querySelector('h2').textContent;
            const precio = item.querySelector('.price').textContent.replace('$', '');
            const producto = { nombre, precio: parseFloat(precio) };

            carrito.push(producto);
            actualizarCarrito();
        });
    });

    function actualizarCarrito() {
        const productosDiv = document.getElementById('productos');
        productosDiv.innerHTML = '';
        carrito.forEach(producto => {
            const div = document.createElement('div');
            div.textContent = `${producto.nombre} - $${producto.precio}`;
            productosDiv.appendChild(div);
        });
    }

    const pagarBtn = document.getElementById('pagar-btn');
    pagarBtn.addEventListener('click', () => {
        document.getElementById('paypal-button-container').innerHTML = ''; // Clear previous PayPal buttons

        paypal.Buttons({
            createOrder: (data, actions) => {
                const total = carrito.reduce((sum, producto) => sum + producto.precio, 0);

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(2) // Total en dólares
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(details => {
                    const nombre = details.payer.name.given_name + ' ' + details.payer.name.surname;
                    const correo = details.payer.email_address;
                    generarRecibo(nombre, correo, carrito);
                    alert('Pago completado por ' + details.payer.name.given_name);
                });
            },
            onCancel: (data) => {
                alert('Pago cancelado');
            },
            onError: (err) => {
                console.error('Error en el pago: ', err);
                alert('Ocurrió un error durante el proceso de pago.');
            }
        }).render('#paypal-button-container');
    });

    function generarRecibo(nombre, correo, carrito) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text('Recibo de Compra', 10, 10);
        doc.text(`Nombre: ${nombre}`, 10, 20);
        doc.text(`Correo: ${correo}`, 10, 30);

        let y = 40;
        carrito.forEach((producto, index) => {
            doc.text(`${index + 1}. ${producto.nombre} - $${producto.precio}`, 10, y);
            y += 10;
        });

        const total = carrito.reduce((sum, producto) => sum + producto.precio, 0);
        doc.text(`Total: $${total.toFixed(2)}`, 10, y + 10);

        doc.save('recibo.pdf');
    }

});






/*

document.addEventListener('DOMContentLoaded', () => {
    const carritoIcon = document.querySelector('.carrito a');
    const carritoContent = document.getElementById('carrito-content');
    const botonesAgregar = document.querySelectorAll('.item button');
    const carrito = [];

    carritoIcon.addEventListener('click', () => {
        carritoContent.classList.toggle('active');
    });

    botonesAgregar.forEach(boton => {
        boton.addEventListener('click', (event) => {
            const item = event.target.closest('.item');
            const nombre = item.querySelector('h2').textContent;
            const precio = item.querySelector('.price').textContent.replace('$', '');
            const producto = { nombre, precio: parseFloat(precio) };
            
            carrito.push(producto);
            actualizarCarrito();
        });
    });

    function actualizarCarrito() {
        const productosDiv = document.getElementById('productos');
        productosDiv.innerHTML = '';
        carrito.forEach(producto => {
            const div = document.createElement('div');
            div.textContent = `${producto.nombre} - $${producto.precio}`;
            productosDiv.appendChild(div);
        });
    }

    const pagarBtn = document.getElementById('pagar-btn');
    pagarBtn.addEventListener('click', () => {
        document.getElementById('paypal-button-container').innerHTML = ''; // Clear previous PayPal buttons

        paypal.Buttons({
            createOrder: (data, actions) => {
                const total = carrito.reduce((sum, producto) => sum + producto.precio, 0);

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(2) // Total en dólares
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(details => {
                    const nombre = details.payer.name.given_name + ' ' + details.payer.name.surname;
                    const correo = details.payer.email_address;
                    generarRecibo(nombre, correo, carrito);
                    alert('Pago completado por ' + details.payer.name.given_name);
                });
            },
            onCancel: (data) => {
                alert('Pago cancelado');
            },
            onError: (err) => {
                console.error('Error en el pago: ', err);
                alert('Ocurrió un error durante el proceso de pago.');
            }
        }).render('#paypal-button-container');
    });

    function generarRecibo(nombre, correo, carrito) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text('Recibo de Compra', 10, 10);
        doc.text(`Nombre: ${nombre}`, 10, 20);
        doc.text(`Correo: ${correo}`, 10, 30);

        let y = 40;
        carrito.forEach((producto, index) => {
            doc.text(`${index + 1}. ${producto.nombre} - $${producto.precio}`, 10, y);
            y += 10;
        });

        const total = carrito.reduce((sum, producto) => sum + producto.precio, 0);
        doc.text(`Total: $${total.toFixed(2)}`, 10, y + 10);

        doc.save('recibo.pdf');
    }


    // Manejar el modal de inicio de sesión 
    const loginBtn = document.getElementById('login-btn'); 
    const modal = document.getElementById('login-modal'); 
    const span = document.getElementsByClassName('close')[0]; 

    loginBtn.addEventListener('click', () => { 
        modal.style.display = 'block'; 
    }); 
    
    span.addEventListener('click', () => { 
        modal.style.display = 'none'; 
    }); 
    
    window.addEventListener('click', (event) => { 
        if (event.target == modal) 
            { 
                modal.style.display = 'none'; 
            } 
    });

});


*/