<?php 
include "P3.php";
include "header.php";

// Category filter handle karne ke liye logic
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

if (!empty($category_filter)) {
    // Secure against SQL injection
    $safe_cat = mysqli_real_escape_string($cn, $category_filter);
    
    $result = mysqli_query($cn, "SELECT * FROM product WHERE category = '$safe_cat'") or die(mysqli_error($cn));
} else 
{
    // Agar koi category select nahi hai toh sabhi products aayenge
    $result = mysqli_query($cn, "SELECT * FROM product") or die(mysqli_error($cn));
}
?>

<style>
  .modern-shop-wrap {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .modern-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }

  .modern-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border: 1px solid rgba(0,0,0,0.04);
    min-height: 440px; 
  }

  .modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.15);
  }

  .modern-img-box {
    width: 100%;
    height: 240px;
    background: #f8fafc;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modern-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 15px;
    transition: transform 0.4s ease;
  }

  .modern-card:hover .modern-img { transform: scale(1.08); }

  .modern-body {
    padding: 18px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
  }

  .modern-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
  }

  .modern-desc {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0 0 16px;
    line-height: 1.5;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }

  .modern-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
  }

  .modern-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
  }

  .modern-btn {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.78rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: all 0.3s ease;
  }

  .modern-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 100%, #1e40af 100%);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
    color: #fff;
  }

  /* --- MODERN CATEGORY SLIDER DESIGN --- */
  .category-slider-wrap {
    max-width: 1200px;
    margin: 30px auto 10px;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .category-slider {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 10px;
    scrollbar-width: none;
  }

  .category-slider::-webkit-scrollbar {
    display: none;
  }

  .category-btn {
    background: #ffffff;
    color: #475569;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
  }

  .category-btn:hover {
    background: #f8fafc;
    color: #2563eb;
    border-color: #cbd5e1;
    transform: translateY(-2px);
  }

  .category-btn.active {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  }

  @media (max-width: 900px) {
    .modern-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 500px) {
    .modern-grid { grid-template-columns: repeat(1, 1fr); }
  }
</style>

<?php include "slider.php"; ?>

<div class="category-slider-wrap">
    <div class="category-slider">
        <!-- ALL PRODUCTS -->
        <a href="index.php" class="category-btn <?php echo empty($category_filter) ? 'active' : ''; ?>">
            All Products
        </a>

        <!-- CLOTHS -->
        <a href="index.php?category=cloths" class="category-btn <?php echo ($category_filter == 'cloths') ? 'active' : ''; ?>">
            Cloths
        </a>

        <!-- FOOTWARE -->
        <a href="index.php?category=footware" class="category-btn <?php echo ($category_filter == 'footware') ? 'active' : ''; ?>">
            Footware
        </a>

        <!-- ELECTRONICS -->
        <a href="index.php?category=electronics" class="category-btn <?php echo ($category_filter == 'electronics') ? 'active' : ''; ?>">
            Electric Itms
        </a>

        <!-- MOBILE -->
        <a href="index.php?category=mobile" class="category-btn <?php echo ($category_filter == 'mobile') ? 'active' : ''; ?>">
            Mobile
        </a>

        <!-- BAGS -->
        <a href="index.php?category=bags" class="category-btn <?php echo ($category_filter == 'bags') ? 'active' : ''; ?>">
            Bags
        </a>
    </div>
</div>

<div class="modern-shop-wrap">
    <div class="modern-grid">
        <?php 
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
                <!-- Modern Card Design -->
                <div class="modern-card">
                    <div class="modern-img-box">
                        <img src="admin/img/<?php echo htmlspecialchars($row['p_photo']); ?>"
                             class="modern-img"
                             alt="Product Image">
                    </div>

                    <div class="modern-body">
                        <div>
                            <h3 class="modern-title">
                                <?php echo htmlspecialchars($row['p_name']); ?>
                            </h3>
    
                            <p class="modern-desc">
                                <?php echo htmlspecialchars($row['p_description']); ?>
                            </p>
                        </div>

                        <div class="modern-footer">
                            <div class="modern-price">
                                ₹<?php echo htmlspecialchars($row['p_price']); ?>
                            </div>

                            <a href="view_detaile.php?id=<?php echo (int)$row['p_id']; ?>" class="modern-btn">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p style="grid-column: 1 / -1; text-align:center; color:#64748b; padding: 40px;">No products found in this category.</p>';
        }
        ?>
    </div>
</div>

<?php
include "footer.php";
?>