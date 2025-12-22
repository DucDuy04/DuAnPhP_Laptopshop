<?php


?>
<!-- Featurs Section Start -->
<div class="container-fluid featurs py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon mb-4 mx-auto">
                        <i class="fas fa-car-side fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Free Shipping</h5>
                        <p class="mb-0">Hỏa tốc trong 2h</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon mb-4 mx-auto">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Security Payment</h5>
                        <p class="mb-0">Giao dịch an toàn</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon mb-4 mx-auto">
                        <i class="fas fa-exchange-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>30 Day Return</h5>
                        <p class="mb-0">Đổi trả miễn phí</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon mb-4 mx-auto">
                        <i class="fa fa-phone-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>24/7 Support</h5>
                        <p class="mb-0">Hỗ trợ nhiệt tình</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Featurs Section End -->

<style>
    /* Features Section - Pin/Marker Style */
    .featurs .featurs-item {
        transition: 0.3s;
    }

    .featurs .featurs-item:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .featurs .featurs-icon {
        position: relative;
        width: 100px;
        height: 100px;
        background-color: #ffb524;
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-bottom: 10px;
    }

    .featurs .featurs-icon::after {
        content: "";
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-top: 20px solid #ffb524;
    }

    .featurs .featurs-icon i {
        color: #fff;
        position: relative;
        top: -5px;
    }
</style>