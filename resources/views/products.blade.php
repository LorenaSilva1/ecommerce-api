<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4 text-center">🛍️ Lista de Produtos</h1>

        <div class="card p-4 shadow-sm">
            <form id="productform" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" id="name" class="form-control" placeholder="Nome do produto" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="description" class="form-control" placeholder="Descrição">
                    </div>
                    <div class="col-md-2">
                        <input type="number" id="price" class="form-control" placeholder="Preço" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <select id="category_id" class="form-select" required></select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-success w-100">Adicionar Produto</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const API_URL = 'http://127.0.0.1:8000/api'; 
        const productTableBody = document.querySelector('table tbody');
        const categorySelect = document.getElementById('category_id');
        const form = document.getElementById('productform');

        // 🟢 Função para mostrar erros no console e alerta
        function showError(context, error) {
            console.error(`❌ Erro em ${context}:`, error);
            alert(`Erro em ${context}: ${error.message || error}`);
        }

        // 🟢 Carregar categorias
        async function loadCategories() {
            try {
                const res = await fetch(`${API_URL}/categories`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const categories = await res.json();

                categorySelect.innerHTML = categories.map(cat =>
                    `<option value="${cat.id}">${cat.name}</option>`
                ).join('');
            } catch (error) {
                showError('carregar categorias', error);
            }
        }

        // 🟢 Carregar produtos (com atualização imediata após excluir)
        async function loadProducts() {
            try {
                const res = await fetch(`${API_URL}/products`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const products = await res.json();

                productTableBody.innerHTML = '';

                products.forEach(prod => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${prod.id}</td>
                        <td>${prod.name}</td>
                        <td>${prod.description || ''}</td>
                        <td>R$ ${parseFloat(prod.price).toFixed(2)}</td>
                        <td>${prod.category?.name || 'Sem categoria'}</td>
                        <td>
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </td>
                    `;

                    // 🗑️ Botão de excluir com atualização imediata
                    row.querySelector('.btn-danger').addEventListener('click', async () => {
                        if (confirm('Deseja excluir este produto?')) {
                            await deleteProduct(prod.id);
                            row.remove(); // remove da tabela sem precisar recarregar tudo
                        }
                    });

                    productTableBody.appendChild(row);
                });
            } catch (error) {
                showError('carregar produtos', error);
            }
        }

        // 🟢 Adicionar produto
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                category_id: document.getElementById('category_id').value
            };

            try {
                const res = await fetch(`${API_URL}/products`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!res.ok) {
                    const msg = await res.text();
                    throw new Error(`HTTP ${res.status} → ${msg}`);
                }

                alert('✅ Produto adicionado com sucesso!');
                form.reset();
                await loadProducts(); // recarrega a lista após adicionar
            } catch (error) {
                showError('adicionar produto', error);
            }
        });

        // 🟢 Excluir produto (função API)
        async function deleteProduct(id) {
            try {
                const res = await fetch(`${API_URL}/products/${id}`, { method: 'DELETE' });
                if (!res.ok) {
                    const msg = await res.text();
                    throw new Error(`HTTP ${res.status} → ${msg}`);
                }
                console.log(`🗑️ Produto ${id} excluído com sucesso`);
            } catch (error) {
                showError('excluir produto', error);
            }
        }

        // 🟢 Inicializa tudo
        await loadCategories();
        await loadProducts();
    });
    </script>
</body>
</html>
