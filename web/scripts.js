let poltronas = [];
let poltronaSelecionadaId = null;

document.addEventListener('DOMContentLoaded', () => {
    carregarDadosDoBanco();
});

const carregarDadosDoBanco = async () => {
    poltronas = [];
    try {
        const requisicao = await fetch("http://localhost/github/cinema/poltronas");
        
        if (!requisicao.ok) {
            throw new Error(`Erro de rede: ${requisicao.status}`);
        }

        const dados = await requisicao.json();

        dados.forEach(element => {
            poltronas.push({
                id: element.id, 
                fileira: element.fileira, 
                coluna: element.coluna, 
                ocupada: element.status === 'O' 
            });
        }); 

        renderizarPoltronas();
        atualizarContador();
    } catch (error) {
        console.error(error);
    }
};

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

const fazerLogin = async () => {
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

async function confirmarCompra() {
    const p = poltronas.find(item => item.id === poltronaSelecionadaId);
    
    if (!p) {
        alert("Erro: Poltrona não encontrada.");
        return;
    }

    const dadosParaEnviar = {
        poltrona_id: p.id
    };

    try {
        const resposta = await fetch("http://localhost/github/cinema/comprar", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dadosParaEnviar) 
        });

        if (resposta.ok) {
            p.ocupada = true;
            
            renderizarPoltronas();
            atualizarContador();
            fecharModal();
            alert(`Poltrona ${p.fileira}-${p.coluna} comprada com sucesso!`);
            
        } else {
            const erroData = await resposta.json();
            alert(`Falha na compra: ${erroData.message || 'Erro desconhecido do servidor.'}`);
        }

    } catch (error) {
        console.error(error);
        alert("Não foi possível conectar ao servidor para finalizar a compra.");
    }
}