<?php
// includes/fe/footer.php - Footer chuẩn, icon màu vàng
?>
<style>
    .footer {
        background: #0f172a;
        color: #cbd5e1;
        padding: 60px 0 30px;
        margin-top: 60px;
    }
    .footer .container {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        margin-bottom: 40px;
    }
   
    .footer-col h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 2px;
    }
    .footer-col p {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .social-icons {
        display: flex;
        gap: 16px;
        margin-top: 16px;
    }
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: #1e293b;
        border-radius: 50%;
        font-size: 1.3rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-icon.facebook:hover { background: #1877f2; color: white; }
    .social-icon.instagram:hover { background: linear-gradient(45deg, #f09433, #d62976); color: white; }
    .social-icon.youtube:hover { background: #ff0000; color: white; }
    .copyright {
        text-align: center;
        padding-top: 24px;
        border-top: 1px solid #1e293b;
        font-size: 0.85rem;
        color: #94a3b8;
    }
    /* Hotline fixed */
    .hotline-fixed {
        position: fixed;
        bottom: 30px;
        right: 30px;
        color: #0f172a;
        padding: 12px 24px;
        border-radius: 60px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        text-decoration: none;
        z-index: 100;
        box-shadow: 0 8px 20px rgba(250,204,21,0.4);
        transition: all 0.3s;
    }
    .hotline-fixed:hover {
        transform: translateY(-4px);
        background: linear-gradient(135deg, #eab308, #ca8a04);
        box-shadow: 0 12px 28px rgba(250,204,21,0.5);
    }
    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 30px;
        }
        .footer-col h4::after {
            left: 50%;
            transform: translateX(-50%);
        }
        .footer-col p {
            justify-content: center;
        }
        .social-icons {
            justify-content: center;
        }
        .hotline-fixed {
            padding: 8px 18px;
            font-size: 0.85rem;
            bottom: 20px;
            right: 20px;
        }
    }
</style>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Về HueShow</h4>
                <p>HueShow chuyên tổ chức team building, sự kiện doanh nghiệp chuyên nghiệp sáng tạo.</p>
            </div>
            <div class="footer-col">
                <h4>Liên hệ</h4>
                <p><i class="fas fa-map-marker-alt"></i> 26 Nguyễn Công Trứ, Huế</p>
                <p><i class="fas fa-phone"></i> 097 966 37 27</p>
                <p><i class="fas fa-envelope"></i> sukienhueshow@gmail.com</p>
            </div>
            <div class="footer-col">
                <h4>Kết nối</h4>
                <div class="social-icons">
                    <a href="https://www.facebook.com/huesukien" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@HueShowMediaEvent" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">© 2026 HueShow - Media &Events. All rights reserved.</div>
    </div>
</footer>

<a href="tel:0979663727" class="hotline-fixed">
    <i class="fas fa-phone-alt"></i> Hotline: 0979.663.727
</a>

<script>
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('navLinks')?.classList.remove('show');
            document.body.classList.remove('menu-open');
            const toggleIcon = document.querySelector('.menu-toggle i');
            if(toggleIcon) {
                toggleIcon.classList.remove('fa-times');
                toggleIcon.classList.add('fa-bars');
            }
        });
    });
</script>