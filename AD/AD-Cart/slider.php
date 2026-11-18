<?php
$slides = [
    ['name' => 'Black T-shirt One8', 'label' => 'Featured collection', 'image' => 'clothes1.jpg'],
    ['name' => 'Nothing Phone (4b) RCB Edition,', 'label' => 'Latest collection', 'image' => 'product_6aa0f0385e0a03.83400380.jpg'],
    ['name' => 'One8 Shoes', 'label' => 'New arrivals', 'image' => 'images.jpg'],
    ['name' => 'Refrigerator', 'label' => 'Home appliances', 'image' => 'product_6aa0f18bb62949.36893579.jpg'],
    ['name' => 'Backpack', 'label' => 'Travel collection', 'image' => 'product_6aa0f213eedc08.21315936.jpg'],
    ['name' => 'ONE8 cover drive 10 pro cricket spikes', 'label' => 'Sports collection', 'image' => 'product_6aa0f09ad96fb5.40390292.jpg']
];

$slideCount = count($slides);
$slideCount = $slideCount > 0 ? $slideCount : 1;
$slideInterval = 3;
$slideDuration = $slideCount * $slideInterval;
$visibleEnd = 90 / $slideCount;
$fadeEnd = 100 / $slideCount;
?>

<style>
    .poster-slider {
        width: calc(100% - 40px);
        max-width: 1200px;
        height: 280px;
        margin: 0 auto 30px;
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        background: #111827;
    }

    .poster-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        animation: changeSlide <?php echo $slideDuration; ?>s infinite;
        animation-fill-mode: both;
    }

    @keyframes changeSlide {
        0% { opacity: 1; visibility: visible; }
        <?php echo $visibleEnd; ?>% { opacity: 1; visibility: visible; }
        <?php echo $fadeEnd; ?>% { opacity: 0; visibility: hidden; }
        100% { opacity: 0; visibility: hidden; }
    }

    .poster-slide img {
        width: 70%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
        margin: 0 auto;
    }

    .poster-slide::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0, 0, 0, .72), rgba(0, 0, 0, .08));
        pointer-events: none;
    }

    .poster-caption {
        position: absolute;
        left: 30px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        z-index: 1;
    }

    .poster-caption small {
        color: #ffb11b;
        font-weight: bold;
        text-transform: uppercase;
    }

    .poster-caption h1 {
        max-width: 430px;
        margin: 8px 0 0;
        font-size: 48px;
        line-height: 1.05;
    }

    @media (max-width: 600px) {
        .poster-slider { height: 220px; }
        .poster-slide img { width: 70%; }
        .poster-caption { left: 24px; }
        .poster-caption h1 { max-width: 260px; font-size: 32px; }
    }
</style>

<?php if (count($slides) > 0) { ?>
<section class="poster-slider" aria-label="Featured products">
    <?php foreach ($slides as $number => $slide) { ?>
        <?php $shortName = strlen($slide['name']) > 32 ? substr($slide['name'], 0, 32) . '...' : $slide['name']; ?>
        <div class="poster-slide" style="animation-delay: -<?php echo $number * $slideInterval; ?>s;">
            <img src="admin/img/<?php echo htmlspecialchars($slide['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($slide['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="poster-caption">
                <small><?php echo $slide['label']; ?></small>
                <h1 title="<?php echo htmlspecialchars($slide['name'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($shortName, ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
        </div>
    <?php } ?>
</section>
<?php } ?>
