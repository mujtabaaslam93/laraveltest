<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <h1 class="text-center mb-4">Laravel Test</h1>

        <!-- Button to open modal -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary addBtn">Add Product</button>
        </div>

        <!-- Table Section -->
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-secondary text-white">
                <h4 class="card-title mb-0">Product List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Submitted</th>
                            <th>Total Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTable">
                        @foreach ($products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->quantity * $product->price }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editBtn" data-id="{{ $product->id }}">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <td colspan="4"><strong>Total</strong></td>
                            <td colspan="2">
                                <strong>
                                    {{ $products->sum(function($product) { return $product->quantity * $product->price; }) }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit Product -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        @csrf
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter product name" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity in Stock</label>
                            <input type="number" id="quantity" class="form-control" placeholder="Enter quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price Per Item</label>
                            <input type="number" step="0.01" id="price" class="form-control" placeholder="Enter price" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle form submission to add/edit product
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let id = document.getElementById('productId').value;
            let url = id ? '/update' : '/store';
            let method = 'POST';

            fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    id: id,
                    name: document.getElementById('name').value,
                    quantity: document.getElementById('quantity').value,
                    price: document.getElementById('price').value
                })
            })
            .then(res => res.json())
            .then(product => {
                // Close modal
                let myModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                myModal.hide();

                // Update or add row to the table
                if (id) {
                    let row = document.querySelector(`tr[data-id="${id}"]`);
                    row.querySelector('td:nth-child(1)').innerText = product.name;
                    row.querySelector('td:nth-child(2)').innerText = product.quantity;
                    row.querySelector('td:nth-child(3)').innerText = product.price;
                    row.querySelector('td:nth-child(4)').innerText = product.created_at;
                    row.querySelector('td:nth-child(5)').innerText = product.quantity * product.price;
                } else {
                    let table = document.getElementById('productTable');
                    let newRow = table.insertRow();
                    newRow.setAttribute('data-id', product.id);
                    newRow.innerHTML = `
                        <td>${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${product.price}</td>
                        <td>${product.created_at}</td>
                        <td>${product.quantity * product.price}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editBtn" data-id="${product.id}">Edit</button>
                        </td>
                    `;
                }
                updateTotal();
            });
        });

        // Handle Edit button click to load product data into the modal
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                fetch('/edit', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _token: '{{ csrf_token() }}', id: this.dataset.id })
                })
                .then(res => res.json())
                .then(product => {
                    document.getElementById('productId').value = product.id;
                    document.getElementById('name').value = product.name;
                    document.getElementById('quantity').value = product.quantity;
                    document.getElementById('price').value = product.price;

                    // Open modal for editing
                    let myModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                    if(!myModal){
                        myModal = new bootstrap.Modal(document.getElementById('productModal'));
                    }
                    myModal.show();
                });
            });
        });
        document.querySelectorAll('.addBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                let myModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                if(!myModal){
                    myModal = new bootstrap.Modal(document.getElementById('productModal'));
                }
                clearForm();
                myModal.show();
            });
        });
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#productTable tr').forEach(row => {
                let quantity = parseFloat(row.querySelector('td:nth-child(2)').innerText);
                let price = parseFloat(row.querySelector('td:nth-child(3)').innerText);
                if (!isNaN(quantity) && !isNaN(price)) {
                    total += quantity * price;
                }
            });
            // Update the total row at the bottom of the table
            let totalRow = document.querySelector('tfoot tr');
            totalRow.querySelector('td:nth-child(2) strong').innerText = total;
        }
        function clearForm(){
            document.getElementById('productId').value = "";
            document.getElementById('name').value = "";
            document.getElementById('quantity').value = "";
            document.getElementById('price').value = "";
        }
    </script>
</body>
</html>
