<script>
    // When the document is ready
    $(document).ready(function() {
        // Save sale button click event
        $('#salvarVendaBtn').on('click', function() {
            salvarVenda();
        });

        // Checkbox to toggle readonly on input
        $('#checkboxParcelar').on('change', function() {
            if ($(this).prop('checked')) {
                $('#parcelas').removeAttr('readonly');
            } else {
                $('#parcelas').attr('readonly', true);
            }
        });

        // Function to generate installment inputs
        function generateInstallmentInputs(numberOfInstallments) {
            const container = $('#parcelas-inputs');
            container.html('');

            for (let i = 1; i <= numberOfInstallments; i++) {
                const input = $('<input>').attr({
                    type: 'number',
                    class: 'form-control installment-input mt-3',
                    id: `installment${i}`,
                    name: `installment${i}`,
                    placeholder: `Installment ${i}`,
                    value: '0.00'
                });

                const date = $('<input>').attr({
                    type: 'date',
                    class: 'form-control date-input',
                    id: `installmentDate${i}`,
                    name: `due_date${i}`
                });

                const installmentDate = new Date();
                installmentDate.setMonth(installmentDate.getMonth() + i);
                const formattedDate = installmentDate.toISOString().slice(0, 10);
                date.val(formattedDate);

                container.append(input);
                container.append(date);
            }
        }

        // Event listener for first installment change
        $('#installment1').on('change', function() {
            updateInstallments($(this).val());
        });

        // Function to calculate installments
        function calculateInstallments(updatedValue) {
            const totalValue = parseFloat($('#total').val());
            const numberOfInstallments = parseInt($('#installments').val());
            const remainingValue = totalValue - updatedValue;
            const remainingInstallments = numberOfInstallments - 1;
            const remainingInstallmentValue = remainingValue / remainingInstallments;

            for (let i = 2; i <= numberOfInstallments; i++) {
                $('#installment' + i).val(remainingInstallmentValue.toFixed(2));
            }
        }

        // Function to set installment values
        function setInstallmentValues() {
            const totalValue = parseFloat($('#total').val());
            const numberOfInstallments = parseInt($('#installments').val());
            const formattedInstallmentValue = calculateInstallments(totalValue, numberOfInstallments);
            generateInstallmentInputs(numberOfInstallments);
            $('.installment-input').val(formattedInstallmentValue);
        }

        // Event listener for adding product button
        $('#adicionarProdutoBtn').on('click', function(event) {
            event.preventDefault();
            adicionarProduto();
        });

        // Function to add product
        function adicionarProduto() {
            const selectProduto = $('#product');
            const index = selectProduto.prop('selectedIndex');
            const option = selectProduto.find('option').eq(index);

            const quantity = parseFloat($('#quantity').val());
            const value = parseFloat($('#value').val());

            if (option && !isNaN(quantity) && !isNaN(value)) {
                const productList = JSON.parse(localStorage.getItem('productList')) || [];
                const product = { name: option.text(), quantity: quantity, value: value };
                productList.push(product);
                localStorage.setItem('productList', JSON.stringify(productList));
                $('#quantity, #value').val('');
                alert('Product added to the list successfully!');
                showProductsInTable();
            } else {
                alert('Please fill in all fields correctly.');
            }
        }

        // Function to display products in the table
        function showProductsInTable() {
            const productList = JSON.parse(localStorage.getItem('productList')) || [];
            const table = $('#productsTable');
            table.html('<th>Name</th><th>Quantity</th><th>Value</th><th>Actions</th>');

            productList.forEach(function(product, index) {
                const row = table[0].insertRow();
                const cellName = row.insertCell(0);
                const cellQuantity = row.insertCell(1);
                const cellValue = row.insertCell(2);
                const cellActions = row.insertCell(3);

                cellName.textContent = product.name;
                cellQuantity.textContent = product.quantity;
                cellValue.textContent = 'R$ ' + product.value.toFixed(2);

                const editBtn = $('<button>').text('Edit').addClass('btn btn-primary btn-sm mr-2 editBtn');
                editBtn.on('click', function() {
                    if (editBtn.hasClass('editBtn')) {
                        $(row.cells[0]).html('<input type="text" value="' + product.name + '">');
                        $(row.cells[1]).html('<input type="number" value="' + product.quantity + '">');
                        $(row.cells[2]).html('<input type="number" value="' + product.value.toFixed(2) + '">');

                        editBtn.text('Save');
                        editBtn.removeClass('editBtn').addClass('saveBtn');
                    } else if (editBtn.hasClass('saveBtn')) {
                        const newName = row.cells[0].querySelector('input').value;
                        const newQuantity = parseFloat(row.cells[1].querySelector('input').value);
                  
