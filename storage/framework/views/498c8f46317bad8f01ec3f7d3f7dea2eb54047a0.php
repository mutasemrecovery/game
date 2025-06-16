<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منتجاتنا - Our Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 0;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 50px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #3498db, #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }

        .product-image {
            position: relative;
            height: 280px;
            overflow: hidden;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            cursor: pointer;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .product-info {
            padding: 25px;
        }

        .product-number {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .product-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .product-description {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #3498db;
            border: 2px solid #3498db;
        }

        .btn-outline:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            animation: scaleIn 0.3s ease;
        }

        .modal-header {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 24px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .gallery-container {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .main-image {
            width: 100%;
            max-width: 600px;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .thumbnail:hover {
            transform: scale(1.1);
            border-color: #3498db;
        }

        .thumbnail.active {
            border-color: #e74c3c;
            transform: scale(1.1);
        }

        .image-counter {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .navigation-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navigation-btn:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .prev-btn {
            left: 20px;
        }

        .next-btn {
            right: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Arabic text alignment improvements */
        .product-name, .product-description {
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }

            .modal-content {
                max-width: 95%;
                max-height: 95%;
            }

            .gallery-container {
                padding: 20px;
            }

            .main-image {
                height: 300px;
            }

            .thumbnail {
                width: 60px;
                height: 60px;
            }

            .navigation-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .main-image {
                height: 250px;
            }

            .thumbnail-container {
                gap: 10px;
            }

            .thumbnail {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>منتجاتنا المميزة</h1>
            <p>اكتشف مجموعتنا الواسعة من المنتجات عالية الجودة المصممة خصيصاً لتلبية احتياجاتكم</p>
        </header>

        <div class="products-grid" id="productsGrid">
            <!-- Laravel Product Cards -->
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-card">
                <div class="product-image" onclick="openGallery(<?php echo e($product->id); ?>, '<?php echo e($product->name_ar); ?>')">
                    <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" 
                         alt="<?php echo e($product->name_ar); ?>" loading="lazy">
                    <!-- If you have multiple images, use this instead:
                    <?php if($product->productImages->count() > 1): ?>
                    <div class="image-counter"><?php echo e($product->productImages->count()); ?> صور</div>
                    <?php endif; ?>
                    -->
                </div>
                <div class="product-info">
                    <span class="product-number">#<?php echo e($product->number); ?></span>
                    <h3 class="product-name"><?php echo e($product->name_ar); ?></h3>
                    <p class="product-description"><?php echo e($product->description_ar); ?></p>
                    <div class="product-price"><?php echo e($product->selling_price); ?> JD</div>
                 
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Image Gallery Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close-btn" onclick="closeModal()">&times;</button>
                <h2 class="modal-title" id="modalTitle">معرض الصور</h2>
            </div>
            <div class="gallery-container">
                <div style="text-align: center; position: relative;">
                    <img id="mainImage" class="main-image" src="" alt="صورة المنتج">
                    <button class="navigation-btn prev-btn" onclick="changeImage(-1)">‹</button>
                    <button class="navigation-btn next-btn" onclick="changeImage(1)">›</button>
                </div>
                <div class="thumbnail-container" id="thumbnailContainer">
                    <!-- Thumbnails will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product images data from Laravel
        const productImages = {
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($product->id); ?>: [
                <?php if(is_object($product->productImages)): ?>
                    // Single image relationship
                    '<?php echo e(asset("assets/admin/uploads/" . $product->productImages->first()->photo)); ?>'
                <?php elseif(is_iterable($product->productImages)): ?>
                    // Multiple images relationship
                    <?php $__currentLoopData = $product->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e(asset("assets/admin/uploads/" . $image->photo)); ?>'<?php echo e(!$loop->last ? ',' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            ]<?php echo e(!$loop->last ? ',' : ''); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        };

        let currentProduct = null;
        let currentImageIndex = 0;

        function openGallery(productId, productName) {
            currentProduct = productId;
            currentImageIndex = 0;
            
            const modal = document.getElementById('imageModal');
            const modalTitle = document.getElementById('modalTitle');
            const mainImage = document.getElementById('mainImage');
            const thumbnailContainer = document.getElementById('thumbnailContainer');
            
            modalTitle.textContent = productName;
            
            const images = productImages[productId];
            
            // Set main image
            mainImage.src = images[0];
            mainImage.alt = productName;
            
            // Clear and populate thumbnails
            thumbnailContainer.innerHTML = '';
            images.forEach((imageSrc, index) => {
                const thumbnail = document.createElement('img');
                thumbnail.src = imageSrc;
                thumbnail.className = 'thumbnail' + (index === 0 ? ' active' : '');
                thumbnail.onclick = () => selectImage(index);
                thumbnailContainer.appendChild(thumbnail);
            });
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function selectImage(index) {
            const images = productImages[currentProduct];
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail');
            
            currentImageIndex = index;
            mainImage.src = images[index];
            
            // Update active thumbnail
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        function changeImage(direction) {
            const images = productImages[currentProduct];
            let newIndex = currentImageIndex + direction;
            
            if (newIndex < 0) {
                newIndex = images.length - 1;
            } else if (newIndex >= images.length) {
                newIndex = 0;
            }
            
            selectImage(newIndex);
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
            if (e.key === 'ArrowLeft') {
                changeImage(1);
            }
            if (e.key === 'ArrowRight') {
                changeImage(-1);
            }
        });

        // Add loading animation and smooth scrolling
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            const images = document.querySelectorAll('.product-image img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\game\resources\views/layouts/product.blade.php ENDPATH**/ ?>