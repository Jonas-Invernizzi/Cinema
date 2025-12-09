let poltronas = [];
let poltronaSelecionadaId = null;

const BASE_URL = "http://localhost/cinema";

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-cancel').addEventListener('click', fecharModal); 
    document.getElementById('btn-buy').addEventListener('click', confirmarCompra);
    document.getElementById('btn-logout').addEventListener('click', logout);

    if(localStorage.getItem('token')) {
        document.getElementById('login-screen').classList.remove('active');
        document.getElementById('seats-screen').classList.add('active');
        carregarDadosDoBanco();
    }
});

function parseJwt (token) {
    try {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return JSON.parse(jsonPayload);
    } catch (e) {
        return null;
    }
}

const carregarDadosDoBanco = async () => {
    poltronas = [];
    const token = localStorage.getItem('token');
    
    if (!token) {
        alert("Sessão expirada. Faça login para carregar os dados.");
        logout();
        return;
    }

    try {
        const requisicao = await fetch(`${BASE_URL}/poltronas`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}` 
            }
        });
        
        if (requisicao.status === 401) {
            alert("Sessão inválida ou expirada. Faça login novamente.");
            logout();
            return;
        }

        if (!requisicao.ok) {
            throw new Error(`Erro ao carregar poltronas: ${requisicao.status}`);
        }

        const dados = await requisicao.json();

        poltronas = dados.map(element => ({
            id: element.id, 
            fileira: element.fileira, 
            coluna: element.coluna, 
            ocupada: element.usuario_id !== null
        }));

        renderizarPoltronas();
        atualizarContador();
    } catch (error) {
        console.error(error);
        alert("Erro ao conectar com o servidor para carregar poltronas.");
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
    const email = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    try {
        const resposta = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, password: password })
        });

        const dados = await resposta.json();

        if (resposta.ok && dados.token) {
            localStorage.setItem('token', dados.token); 

            document.getElementById('login-screen').classList.remove('active');
            document.getElementById('seats-screen').classList.add('active');
            
            carregarDadosDoBanco();
        } else {
            alert(dados.error || "Login falhou");
        }
    } catch (error) {
        console.error(error);
        alert("Erro ao tentar fazer login.");
    }
}

function logout() {
    localStorage.removeItem('token');
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
        btnCancelar.style.display = "block";
    }

    modal.style.display = "flex";
}

function fecharModal() {
    document.getElementById('purchase-modal').style.display = "none";
}

async function confirmarCompra() {
    const p = poltronas.find(item => item.id === poltronaSelecionadaId);
    const token = localStorage.getItem('token');
    
    if (!token) {
        alert("Sessão expirada. Faça login para comprar.");
        logout();
        return; 
    }

    const userData = parseJwt(token);
    const userId = userData ? userData.userId : null;

    if (!p || !userId) {
        alert("Erro: Dados inválidos para compra.");
        return; 
    }

    const dadosParaEnviar = {
        id_poltrona: p.id,
        id_usuario: userId
    };

    try {
        const resposta = await fetch(`${BASE_URL}/comprar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` 
            },
            body: JSON.stringify(dadosParaEnviar) 
        });

        if (resposta.status === 401) {
            alert("Sessão inválida ou expirada. Faça login novamente.");
            logout();
            return;
        }
        
        const dadosRetorno = await resposta.json();

        if (resposta.ok) {
            p.ocupada = true; 
            fecharModal();
            
            alert(`Sucesso: ${dadosRetorno.mensagem || 'Poltrona comprada com sucesso!'}`);
            
            renderizarPoltronas();
            atualizarContador();
            
            return; 
        } else {
            alert(`Falha na Compra: ${dadosRetorno.error || 'Erro desconhecido do servidor.'}`);
            return; 
        }

    } catch (error) {
        console.error(error);
        alert("Não foi possível conectar ao servidor para efetuar a compra.");
        return; 
    }
}