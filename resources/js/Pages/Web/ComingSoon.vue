<script setup>
import { Head } from '@inertiajs/vue3'
</script>

<style scoped>
.coming-soon {
    position: relative;
    display: flex;
    min-height: 100vh;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 20px;
    color: #fff;
    background: #080b14;
    /* هم‌خوان با web.css و admin.css */
    font-family: 'Estedad-Regular', 'Estedad', Tahoma, sans-serif;
}

.coming-soon::before,
.coming-soon::after {
    position: absolute;
    border-radius: 9999px;
    content: '';
    filter: blur(120px);
    pointer-events: none;
}

.coming-soon::before {
    top: -130px;
    right: -130px;
    width: 380px;
    height: 380px;
    background: rgba(99, 102, 241, 0.28);
}

.coming-soon::after {
    bottom: -180px;
    left: -130px;
    width: 480px;
    height: 480px;
    background: rgba(6, 182, 212, 0.15);
}

.grid-background {
    position: absolute;
    inset: 0;
    opacity: 0.035;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.9) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.9) 1px, transparent 1px);
    background-size: 42px 42px;
    pointer-events: none;
}

.content {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 680px;
    text-align: center;
}

.card {
    padding: 56px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 32px;
    background: rgba(255, 255, 255, 0.045);
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.icon-wrapper {
    display: flex;
    width: 80px;
    height: 80px;
    margin: 0 auto 32px;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(165, 180, 252, 0.2);
    border-radius: 24px;
    color: #c7d2fe;
    background: linear-gradient(
        135deg,
        rgba(99, 102, 241, 0.3),
        rgba(124, 58, 237, 0.2)
    );
    box-shadow: 0 12px 32px rgba(49, 46, 129, 0.35);
}

.icon-wrapper svg {
    width: 36px;
    height: 36px;
}

.badge {
    display: inline-flex;
    margin-bottom: 20px;
    padding: 7px 16px;
    border: 1px solid rgba(165, 180, 252, 0.16);
    border-radius: 999px;
    color: #c7d2fe;
    background: rgba(129, 140, 248, 0.1);
    font-family: 'Estedad-Medium', 'Estedad', Tahoma, sans-serif;
    font-size: 14px;
    font-weight: 400;
}

.title {
    margin: 0;
    color: #fff;
    font-family: 'Estedad-Black', 'Estedad', Tahoma, sans-serif;
    font-size: clamp(42px, 7vw, 72px);
    font-weight: 400;
    line-height: 1.2;
    letter-spacing: -1px;
}

.description {
    max-width: 480px;
    margin: 24px auto 0;
    color: #cbd5e1;
    font-family: 'Estedad-Regular', 'Estedad', Tahoma, sans-serif;
    font-size: 17px;
    line-height: 2;
}

.progress-wrapper {
    max-width: 360px;
    margin: 40px auto 0;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #94a3b8;
    font-family: 'Estedad-Regular', 'Estedad', Tahoma, sans-serif;
    font-size: 12px;
}

.progress {
    height: 8px;
    overflow: hidden;
    padding: 2px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.1);
}

.progress-bar {
    width: 66%;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #22d3ee, #818cf8, #8b5cf6);
    animation: pulse-progress 1.8s ease-in-out infinite;
}

.copyright {
    margin-top: 28px;
    color: #64748b;
    font-family: 'Estedad-Regular', 'Estedad', Tahoma, sans-serif;
    font-size: 13px;
}

@keyframes pulse-progress {
    0%,
    100% {
        opacity: 0.55;
    }

    50% {
        opacity: 1;
    }
}

@media (max-width: 640px) {
    .card {
        padding: 38px 22px;
        border-radius: 26px;
    }

    .icon-wrapper {
        width: 68px;
        height: 68px;
        margin-bottom: 26px;
        border-radius: 20px;
    }

    .description {
        font-size: 15px;
    }
}
</style>
<template>
    <Head title="به‌زودی" />
    <main dir="rtl" class="coming-soon">
        <div class="grid-background" />

        <section class="content">
            <div class="card">
                <div class="icon-wrapper">
                    <!-- SVG icon -->
                </div>

                <span class="badge">در حال آماده‌سازی</span>

                <h1 class="title">به‌زودی</h1>

                <p class="description">
                    در حال ساخت تجربه‌ای سریع‌تر، بهتر و متفاوت‌تر هستیم.
                    خیلی زود برمی‌گردیم.
                </p>

                <div class="progress-wrapper">
                    <div class="progress-info">
                        <span>پیشرفت توسعه</span>
                        <span>در حال انجام</span>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" />
                    </div>
                </div>
            </div>

            <p class="copyright">
                © {{ new Date().getFullYear() }} تمامی حقوق محفوظ است.
            </p>
        </section>
    </main>
</template>
