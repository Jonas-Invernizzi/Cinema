let poltronas = [];
let poltronaSelecionadaId = null;

document.addEventListener('DOMContentLoaded', () => {
    carregarDadosDoBanco();
});

function carregarDadosDoBanco() {
    poltronas = [];
    const totalPoltronas = 32;
    const colunasPorFileira = 8;
    const fileiras = ['A', 'B', 'C', 'D'];

    for (let i = 0; i < totalPoltronas; i++) {
        const rowIndex = Math.floor(i / colunasPorFileira);
        const colIndex = (i % colunasPorFileira) + 1;
        
        poltronas.push({
            id: i,
            fileira: fileiras[rowIndex],
            coluna: colIndex,
            ocupada: Math.random() < 0.3
        });
    }

    renderizarPoltronas();
    atualizarContador();
}

function renderizarPoltronas() {
    const grid = document.getElementById('seats-grid');
    grid.innerHTML = '';

    poltronas.forEach(poltrona => {
        const seatDiv = document.createElement('div');
        seatDiv.classList.add('seat');
        seatDiv.classList.add(poltrona.ocupada ? 'taken' : 'free');
        
        seatDiv.onclick = () => abrirModalCompra(poltrona);

        grid.appendChild(seatDiv);
    });
}

function atualizarContador() {
    const disponiveis = poltronas.filter(p => !p.ocupada).length;
    const total = poltronas.length;
    document.getElementById('availability-counter').innerText = `${disponiveis}/${total}`;
}

function fazerLogin() {
    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;

    if (user === "admin" && pass === "1234") {
        document.getElementById('login-screen').classList.remove('active');
        document.getElementById('seats-screen').classList.add('active');
    } else {
        alert("Credenciais inválidas! Use: admin / 1234");
    }
}

function logout() {
    document.getElementById('seats-screen').classList.remove('active');
    document.getElementById('login-screen').classList.add('active');
    document.getElementById('username').value = "";
    document.getElementById('password').value = "";
}

function abrirModalCompra(poltrona) {
    const modal = document.getElementById('purchase-modal');
    const btnComprar = document.getElementById('btn-buy');
    const btnCancelar = document.getElementById('btn-cancel');

    document.getElementById('modal-row').innerText = poltrona.fileira;
    document.getElementById('modal-col').innerText = poltrona.coluna;
    
    poltronaSelecionadaId = poltrona.id;

    if (poltrona.ocupada) {
        document.getElementById('modal-status').innerText = "Ocupada";
        document.getElementById('modal-status').style.color = "red";
        btnComprar.style.display = "none";
        btnCancelar.style.display = "block";
    } else {
        document.getElementById('modal-status').innerText = "Disponível";
        document.getElementById('modal-status').style.color = "lightgreen";
        btnComprar.style.display = "block";
        btnCancelar.style.display = "none";
    }

    modal.style.display = "flex";
}

function fecharModal() {
    document.getElementById('purchase-modal').style.display = "none";
}

function confirmarCompra() {
    const p = poltronas.find(item => item.id === poltronaSelecionadaId);
    if (p) {
        p.ocupada = true;
        renderizarPoltronas();
        atualizarContador();
        fecharModal();
    }
}

function cancelarReserva() {
    const p = poltronas.find(item => item.id === poltronaSelecionadaId);
    if (p) {
        p.ocupada = false;
        renderizarPoltronas();
        atualizarContador();
        fecharModal();
    }
}