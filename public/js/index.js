document.addEventListener('DOMContentLoaded', function()
{
    addButtonListeners();
    ratingListeners();
    removeButtonListeners();
    loadCart();
});

/**
 * Add listeners for "add product to cart" buttons
 */
function addButtonListeners()
{
    let addButtons = document.getElementsByClassName('add-button');
    for(let i=0; i < addButtons.length; i++)
    {
        addButtons[i].onclick = function(event)
        {
            let target = event.target;
            let productContainer = target.parentNode.parentNode;
            let quantity = parseInt(productContainer.querySelector('input[name="quantity"]').value);

            if (quantity <= 0)
            {
                alert('Please enter a valid quantity!');
                return;
            }

            if (quantity > 100)
            {
                alert('The quantity exceeds the limit (100)!');
                return;
            }

            let data = {
                product: {
                    id: productContainer.querySelector('[data-id]').getAttribute('data-id'),
                    title: productContainer.querySelector('[data-title]').getAttribute('data-title'),
                    price: productContainer.querySelector('[data-price]').getAttribute('data-price'),
                    quantity: quantity,
                }
            };

            ajax('/cart/addProduct/', 'POST', JSON.stringify(data), renderCart, addProductOnError, true);
        }
    }
}

/**
 * Add listeners handling product rating
 */
function ratingListeners()
{
    let ratingRadios = document.querySelectorAll('input[name="rating"]');
    for(let i=0; i < ratingRadios.length; i++)
    {
        ratingRadios[i].onclick = function(event)
        {
            let target = event.target;
            let productContainer = target.parentNode.parentNode;
            let rating = parseInt(target.value);
            let productId = parseInt(productContainer.querySelector('[data-id]').getAttribute('data-id'));

            if (productId && rating)
            {
                ajax('/product/rating/' + productId + '/' + rating, 'POST');
            }
        }
    }
}

/**
 * Add listeners for "remove product from cart" buttons
 */
function removeButtonListeners()
{
    let removeButtons = document.getElementsByClassName('remove-button');
    for(let i=0; i < removeButtons.length; i++)
    {
        removeButtons[i].onclick = function(event)
        {
            let target = event.target;
            let tr = target.parentNode.parentNode;
            let productId = target.getAttribute('data-id');

            ajax('/cart/removeProduct/' + productId, 'DELETE', null, updateTotalSum);
            tr.remove();
        }
    }
}

/**
 * Add listener for pay action button
 */
function addPayButtonListener()
{
    let payButton = document.getElementById('pay-button');

    if (payButton !== null)
    {
        payButton.onclick = function()
        {
            let shippingRadios = document.querySelectorAll('input[name="shipping"]'),
                shippingMethod = '';

            for(let i=0; i < shippingRadios.length; i++)
            {
                if (shippingRadios[i].checked)
                {
                    shippingMethod = shippingRadios[i].value;
                    break;
                }
            }

            let shippingErrorElement = document.getElementById('shipping-error');
            if (shippingMethod === '')
            {
                shippingErrorElement.style.display = 'block';
            }
            else
            {
                ajax('/order/pay/' + shippingMethod, 'POST', null, payActionSuccess);

                if (shippingErrorElement !== null)
                {
                    shippingErrorElement.style.display = 'none';
                }
            }
        }
    }
}

/**
 * Load cart content
 */
function loadCart()
{
    ajax('/cart/cart/', 'GET', null, renderCart, function (xhr) {
        console.warn(xhr.target.responseText);
    });
}

/**
 * Update cart total sum
 * @param xhr
 */
function updateTotalSum(xhr)
{
    if (xhr.target.responseText !== '')
    {
        let data = JSON.parse(xhr.target.responseText);

        if (typeof data.totalSum !== 'undefined')
        {
            document.getElementById('total-sum').innerHTML = data.totalSum;
        }
    }
}

/**
 * Load cart success callback
 * @param xhr
 */
function renderCart(xhr)
{
    if (xhr.target.responseText !== '')
    {
        document.getElementById('shopping-cart').innerHTML = xhr.target.responseText;
        removeButtonListeners();
        addPayButtonListener();
    }
}

/**
 * Pay action success callback
 * @param xhr
 */
function payActionSuccess(xhr)
{
    if (xhr.target.responseText !== '')
    {
        let response = JSON.parse(xhr.target.responseText);

        if (response.paymentStatus === 'success')
        {
            document.getElementById('shopping-cart').innerHTML = response.content;
        }
        else
        {
            let id = 'payment-failure-container';
            let paymentFailureContainer = document.getElementById(id);

            if (paymentFailureContainer !== null)
            {
                paymentFailureContainer.innerHTML = response.content;
            }
            else
            {
                let div = document.createElement('div');
                div.id = id;
                div.innerHTML = response.content;
                document.getElementById('shopping-cart').appendChild(div);
            }
        }
    }
}

/**
 * Add product to cart error callback
 * @param xhr
 */
function addProductOnError(xhr)
{
    console.error(xhr.target.responseText);
}

/**
 * AJAX function
 * @param url
 * @param method
 * @param data
 * @param onLoad
 * @param onError
 * @param async
 */
function ajax(url, method, data, onLoad, onError, async)
{
    method = typeof method !== 'undefined' ? method : 'GET';
    async = typeof async !== 'undefined' ? async : false;

    if (window.XMLHttpRequest)
    {
        let xhReq = new XMLHttpRequest();
    }
    else
    {
        let xhReq = new ActiveXObject("Microsoft.XMLHTTP");
    }


    if (method == 'POST')
    {
        xhReq.open(method, url, async);
        xhReq.setRequestHeader("Content-type", "application/json; charset=utf-8");
        xhReq.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        if (typeof onLoad !== 'undefined')
        {
            xhReq.onload = onLoad;
        }

        if (typeof onerror !== 'undefined')
        {
            xhReq.onerror = onError;
        }

        xhReq.send(data);
    }
    else
    {
        if(typeof data !== 'undefined' && data !== null)
        {
            url = url+'?'+data;
        }
        xhReq.open(method, url, async);
        xhReq.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        if (typeof onLoad !== 'undefined')
        {
            xhReq.onload = onLoad;
        }

        if (typeof onerror !== 'undefined')
        {
            xhReq.onerror = onError;
        }

        xhReq.send(null);
    }
}
