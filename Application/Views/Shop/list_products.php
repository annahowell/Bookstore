<h1>Shop</h1>

<p>Quality products at competitive prices</p>

<div class="arrange cb">

    <label for="searchTerm">Search:</label>
    <input id="searchTerm" type="text" value="">
    <input id="searchSubmit" class="search" type="submit" name="submit" value="Go">

</div>
<div class="arrange cb">
    <label fpr="categoryNo">Filter:</label>
    <select id="categoryNo" name="categoryNo">
        <option value="%">All products</option>
        <?php foreach ($body['categories'] as &$category) {
            if (is_array($category)) { ?>
                <option value="<?php echo $category['categoryNo'] ?>"><?php echo $category['name'] ?></option>
            <?php }
        } ?>
    </select>

    <select id="orderBy" name="orderBy">
        <option value="name">Name</option>
        <option value="price">Price</option>
    </select>

    <select id="orderDir" name="orderDir">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
    </select>
</div>

<div class="flex-container">
</div>

<ul id="pagination">
</ul>

<script>
    // AJAX search, category filtering, pagination, order by and order direction
    $(document).ready(function() {
        function submit(pagNo) {
            $.ajax({
                method: "POST",
                url: '<?php echo URL_SUB_DIR ?>/shop/list_products',
                data: {
                    submit: true,
                    ajax: true,
                    searchTerm: $('#searchTerm').val(),
                    categoryNo: $('#categoryNo').val(),
                    pagNo: pagNo,
                    orderBy: $('#orderBy').val(),
                    orderDir: $('#orderDir').val()

                }
            }).done(function (result) {
                result = JSON.parse(result);

                if (result && result.hasOwnProperty('products') && result.products) {
                    render(result);
                } else {
                    $('.flex-container').html('<p>An error occured returning products.</p>');
                }
            }).fail(function () {
                $('.flex-container').html('<p>An error occured returning products.</p>');
            });
        }

        $('#categoryNo,#orderBy,#orderDir').change(function() {
            submit(1);
        });

        $('#searchSubmit').click(function() {
            submit(1);
        });

        $('#searchTerm').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // Enter key
            {
                submit(1)
            }
        });

        function render(result) {
            var products = $.map(result.products, function(value) {
                return [value];
            });

            // Using $ to remind ourselves it's a jquery css var
            var $fc = $('.flex-container');
            var $pag = $('#pagination');

            $fc.html('');
            $pag.html('');

            products.forEach(function(row) {
                if (row.name) {
                    if (row.stockLevel < 1) {
                        stockColour = 'D00';
                    } else {
                        stockColour = '0D0';
                    }
                    $fc.append('<section class="flex-child"> \
                                <a href="<?php echo URL_SUB_DIR ?>/shop/view_product/' + row.productNo + '"> \
                                    <div class="cb"> \
                                        <h2>' + row.name + '</h2> \
                                        <img src="<?php echo URL_SUB_DIR ?>/images/' + row.imageName + '" alt="' + row.name + ' image"> \
                                        <div class="info cb"> \
                                            <p class="author">' + row.author + '</p> \
                                            <p class="price">£' + row.price + '</p> \
                                            <p class="stock" style="color:#' + stockColour + '">' + row.stockLevel + ' in stock</p> \
                                        </div>\
                                    </div> \
                                </a> \
                            </section>');
                }
            });

            if (result.products.pagCount > 1) {
                for (i = 1; i <= result.products.pagCount; i++) {
                    if (result.pagNo == i) {
                        $pag.append('<li class="selected" href="#">' + i + '</li>');
                    } else {
                        $pag.append('<li href="#">' + i + '</li>');
                    }
                }
            }

            // We have to bind this event here, after pagination li's are appended.
            $("#pagination li").on("click", function(){
                submit($(this).text());
            });
        }

        submit(1);
    });
</script>
