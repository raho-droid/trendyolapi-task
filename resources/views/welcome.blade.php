<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Trendyol API</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h1>Trendyol Ürünlerini Çek</h1>
    <button id="save-products-btn">Ürünleri Çek</button>
    <h1>Trendyol Ürün Listesi</h1>
    <input type="text" id="search-input" placeholder="Ürün adı, marka veya cinsiyet ara">
    <button id="search-btn">Ara</button>

    <table id="products-table" style="width:100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>Ürün ID</th>
                <th>Ürün Adı</th>
                <th>Marka</th>
                <th>Cinsiyet</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div id="product-list" style="margin-top: 20px;"></div>

    <script>
        function loadProducts(page, searchQuery = '') {
            $.ajax({
                url: '/get-products?page=' + page + '&search=' + encodeURIComponent(searchQuery),
                method: 'GET',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {
                    var products = response.data;
                    var tbody = '';
                    $.each(products, function(index, product) {
                        tbody += '<tr>';
                        tbody += '<td>' + product.product_id + '</td>';
                        tbody += '<td>' + product.title + '</td>';
                        tbody += '<td>' + product.brand + '</td>';
                        tbody += '<td>' + product.gender + '</td>';
                        tbody += '</tr>';
                    });
                    $('#products-table tbody').html(tbody);
                    var pagination = '';
                    if (response.prev_page_url) {
                        pagination += '<button class="pagination-btn" data-page="' + (response.current_page - 1) + '" data-search="' + searchQuery + '">Önceki</button>';
                    }
                    if (response.next_page_url) {
                        pagination += '<button class="pagination-btn" data-page="' + (response.current_page + 1) + '" data-search="' + searchQuery + '">Sonraki</button>';
                    }
                    $('#product-list').html(pagination);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Ürünleri yüklerken bir hata oluştu:', textStatus, errorThrown);
                }
            });
        }

        $(document).ready(function() {
            $('#save-products-btn').click(function() {
                $.ajax({
                    url: '/save-products',
                    method: 'GET',
                    timeout: 400000,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        var products = response;
                        var output = '<h2>Ürünler</h2><ul>';
                        $.each(products, function(index, product) {
                            output += '<li>' + product.name + '</li>';
                        });
                        output += '</ul>';
                        $('#products-list').html(output);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Ürünleri çekerken bir hata oluştu:', textStatus, errorThrown);
                    }
                });
            });
            $('#search-btn').click(function() {
                var searchQuery = $('#search-input').val();
                loadProducts(1, searchQuery);
            });
            $(document).on('click', '.pagination-btn', function() {
                var page = $(this).data('page');
                var searchQuery = $(this).data('search');
                loadProducts(page, searchQuery);
            });
            loadProducts(1);
        });
    </script>

</body>
</html>
