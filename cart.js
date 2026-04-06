const products =[
    { id: 1, name: "Виски Jack Daniel's Honey (Медовый)", price: 2500, image: "https://static.decanter.ru/upload/images/413079/413079-viski-jack-daniels-honey-1-l-mb.jpg", category: "whiskey", link: "whiskey_jack_honey.html"},
    { id: 2, name: "Водка Beluga", price: 3000, image: "https://s2.wine.style/images_gen/729/72932/0_0_695x600.webp", category: "vodka", link:"vodka_beluga.html" },
    { id: 3, name: "Ром Barcelo Anejo", price: 2800, image: "https://amwine.ru/upload/resize_cache/iblock/7e5/620_620_1/7e5538d1ba4e40078e374c2e2797474c.png", category: "rum", link: "rum_barcelo_anejo.html" },
    { id: 4, name: "Виски Chivas Regal 12 Years", price: 3500, image: "https://amwine.ru/upload/iblock/bd0/bd072f16597f97f6ff2135ac7152b36b.png", category: "whiskey", link: "whiskey_chivas.html" },
    { id: 5, name: "Текила Jose Cuervo Silver", price: 2200, image: "https://amwine.ru/upload/resize_cache/iblock/44a/620_620_1/44a7f466976d280c0cc7bf1f8f63e08e.png", category: "tequila" , link: "tequila_josecuerva.html"},
    { id: 6, name: "Джин Hendrick's", price: 3200, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS7W3utX900mxexXXXZw5itmFxM6IpGUnqGHg&s", category: "gin", link: "gin_hendricks.html"  }
];

let cart = [];
const calculateTotal = () => {
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
};

const calculateTotalItems = () => {
    return cart.reduce((sum, item) => sum + item.quantity, 0);
};

const displayProducts = (filteredProducts = products) => {
    const container = document.getElementById('products-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    filteredProducts.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <div class="image-container">
                <a href="${product.link}" class="product-link">
                    <img src="${product.image}" alt="${product.name}">
                </a>
            </div>
            <a href="${product.link}" class="product-link">
                <h3>${product.name}</h3>
            </a>
            <p class="price">${product.price} ₽</p>
            <button class="add-to-cart" data-id="${product.id}">Добавить в корзину</button>
        `;
        container.appendChild(productCard);
    });
        document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', addToCart);
    });
};

const addToCart = (event) => {
    const productId = parseInt(event.target.dataset.id);
    const product = products.find(p => p.id === productId);
    
    if (product) {
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1; 
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        updateCartDisplay();
        alert(`Товар "${product.name}" добавлен в корзину!`);
    }
};

const updateCartDisplay = () => {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = calculateTotalItems();
    }
    
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    
    if (cartItems && cartTotal) {
        if (cart.length === 0) {
            cartItems.innerHTML = '<p>Корзина пуста</p>';
            cartTotal.textContent = '0';
        } else {
            cartItems.innerHTML = '';
            cart.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <span>${item.name} - ${item.price} ₽ x ${item.quantity}</span>
                    <button class="remove-item" data-index="${index}">Удалить</button>
                `;
                cartItems.appendChild(itemElement);
            });
            
            cartTotal.textContent = calculateTotal();
            
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', removeFromCart);
            });
        }
    }
};

const removeFromCart = (event) => {
    const index = parseInt(event.target.dataset.index);
    cart.splice(index, 1);
    updateCartDisplay();
};

const clearCart = () => {
    cart = [];
    updateCartDisplay();
};

const checkout = () => {
    if(cart.length === 0) {
        alert('Корзина пуста! Добавьте товары перед оплатой.');
    }
    else{
        alert('Покупка прошла успешно! Спасибо за заказ.');
        clearCart();
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
};

const filterByPrice = () => {
    const minPrice = parseInt(document.getElementById('min-price').value) || 0;
    const maxPrice = parseInt(document.getElementById('max-price').value) || Infinity;

    const filteredProducts =products.filter(product =>
        product.price >= minPrice && product.price <=maxPrice);
        displayProducts(filteredProducts);
};

const resetFilter = () => {
    document.getElementById('min-price').value=0;
    document.getElementById('max-price').value=10000;
    displayProducts(products);
};

const initProductPage = () => {
    const productTitle = document.querySelector('h1')?.textContent;
    if (!productTitle) return;
    const product = products.find(p => productTitle.includes(p.name) || p.name.includes(productTitle));
    
    if (product) {
        let priceElement = document.querySelector('.product-price');
        if (!priceElement) {
            priceElement = document.createElement('div');
            priceElement.className = 'product-price';
            const h1 = document.querySelector('h1');
            if (h1) {
                h1.insertAdjacentElement('afterend', priceElement);
            }
        }
        priceElement.innerHTML = `Цена: ${product.price} ₽`;

        let addButton = document.querySelector('.add-to-cart-page');
        if (!addButton) {
            addButton = document.createElement('button');
            addButton.className = 'add-to-cart-page';
            addButton.textContent = 'Добавить в корзину';
            addButton.dataset.id = product.id;
            if (priceElement) {
                priceElement.insertAdjacentElement('afterend', addButton);
            }
            
            addButton.addEventListener('click', addToCart);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('products-container')) {
        displayProducts();
    }    
    if (document.querySelector('.product-page')) {
        initProductPage();
    }
    
    const cartLink = document.getElementById('cart-link');
    if (cartLink) {
        cartLink.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById('cart-modal');
            if (modal) {
                modal.style.display = 'block';
                updateCartDisplay();
            }
        });
    }
    
    document.getElementById('apply-filter')?.addEventListener('click', filterByPrice);
    document.getElementById('reset-filter')?.addEventListener('click', resetFilter);
    
    document.getElementById('checkout-btn')?.addEventListener('click', checkout);
    document.getElementById('clear-cart-btn')?.addEventListener('click', () => {
        clearCart();
        alert('Корзина очищена');
    });
    
    document.getElementById('close-cart')?.addEventListener('click', () => {
        const modal = document.getElementById('cart-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    });
    
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('cart-modal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
